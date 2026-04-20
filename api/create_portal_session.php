<?php
// api/create_portal_session.php
require_once __DIR__ . '/../src/auth.php';
requireAuth();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
\Stripe\Stripe::setApiKey($stripeSecretKey);

$userId = getCurrentUserId();

// Fetch the Stripe Customer ID from the database
global $pdo;
$stmt = $pdo->prepare("SELECT stripe_customer_id FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$customerId = $user['stripe_customer_id'] ?? null;

if (!$customerId) {
    // Cannot open portal if the user never bought anything
    die("Você ainda não possui uma assinatura vinculada.");
}

try {
    // Authenticate your user.
    $session = \Stripe\BillingPortal\Session::create([
        'customer' => $customerId,
        'return_url' => 'http://localhost:8000/dashboard.php',
    ]);

    // Redirect to the URL for the session
    header("HTTP/1.1 303 See Other");
    header("Location: " . $session->url);
} catch (Exception $e) {
    die("Erro ao iniciar o portal do cliente: " . $e->getMessage());
}
