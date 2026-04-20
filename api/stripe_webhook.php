<?php
// api/stripe_webhook.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    http_response_code(500);
    exit("Missing Secret Key");
}
\Stripe\Stripe::setApiKey($stripeSecretKey);

// Recebe a notificação (Post) enviada pela Stripe
$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Stripe\Event::constructFrom(
        json_decode($payload, true)
    );
} catch(\UnexpectedValueException $e) {
    http_response_code(400); // Bad Request se o payload for invalido
    exit();
}

// Analisa qual foi a ação
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;
        
        // O ID do usuário que enviamos ao criar a sessão cai aqui de volta!
        $userId = $session->client_reference_id;
        $customerId = $session->customer;
        $subscriptionId = $session->subscription;
        
        if ($userId) {
            // Ativa o usuário mágico no banco de dados!
            $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'active', current_period_end = NULL, stripe_customer_id = ?, stripe_subscription_id = ? WHERE id = ?");
            $stmt->execute([$customerId, $subscriptionId, $userId]);
        }
        break;
        
    case 'customer.subscription.deleted':
    case 'customer.subscription.updated':
        $subscription = $event->data->object;
        $status = $subscription->status; 
        $subscriptionId = $subscription->id;
        
        // Se a assinatura foi cancelada ou pausada, nós bloqueamos
        $mappedStatus = ($status === 'active' || $status === 'trialing') ? 'active' : 'inactive';
        
        $stmt = $pdo->prepare("UPDATE users SET subscription_status = ? WHERE stripe_subscription_id = ?");
        $stmt->execute([$mappedStatus, $subscriptionId]);
        break;
}

http_response_code(200);
