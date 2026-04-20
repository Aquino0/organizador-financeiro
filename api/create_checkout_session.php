<?php
// api/create_checkout_session.php
require_once __DIR__ . '/../src/auth.php';
requireAuth();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

// The library needs a secret key. You will add this to your .env
$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    die("Você precisa de uma STRIPE_SECRET_KEY configurada no painel ou no .env!");
}

\Stripe\Stripe::setApiKey($stripeSecretKey);

$plan = $_GET['plan'] ?? 'monthly';

// These IDs are generated inside your Stripe Dashboard when you create a Product.
$priceIdMonthly = getenv('STRIPE_PRICE_MONTHLY') ?: 'price_placeholder_mensal';
$priceIdYearly = getenv('STRIPE_PRICE_YEARLY') ?: 'price_placeholder_anual';

$priceId = ($plan === 'yearly') ? $priceIdYearly : $priceIdMonthly;

$userId = getCurrentUserId();
$userEmail = $_SESSION['user_email'] ?? 'teste@organizamais.com'; // Fallback if missing

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'customer_email' => $userEmail, 
        'client_reference_id' => $userId, // Important: binds the payment to our user!
        'line_items' => [[
            'price' => $priceId,
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'success_url' => 'http://localhost:8000/sucesso.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost:8000/planos.php',
    ]);

    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
    exit;

} catch (Exception $e) {
    echo "Erro ao gerar link de pagamento: " . $e->getMessage();
}
