<?php
// sucesso.php
require_once __DIR__ . '/src/auth.php';
requireAuth();
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/vendor/autoload.php';

$sessionId = $_GET['session_id'] ?? null;
if ($sessionId) {
    try {
        $stripeSecretKey = getenv('STRIPE_SECRET_KEY');
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        
        if ($session && $session->payment_status === 'paid') {
            $userId = $session->client_reference_id;
            $customerId = $session->customer;
            $subId = $session->subscription;
            
            if ($userId) {
                global $pdo;
                $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'active', current_period_end = NULL, stripe_customer_id = ?, stripe_subscription_id = ? WHERE id = ?");
                $stmt->execute([$customerId, $subId, $userId]);
            }
        }
    } catch (Exception $e) {
        // Silently ignore errors on success page, they are just local sync fallbacks
    }
}

// Since the DB is updated, we can optionally clear old auth locks if needed, but it should just work on next refresh.
require_once __DIR__ . '/src/layout.php';

renderHeader('Pagamento Confirmado | Organiza+');
?>
<main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 min-h-[calc(100vh-4rem)] flex items-center justify-center p-4">
    <div class="max-w-md mx-auto w-full text-center bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-10 border border-green-100 dark:border-green-900">
        <div class="mx-auto w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mb-4">Pagamento Confirmado!</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-8">
            Sua assinatura do Organiza+ foi processada com sucesso. Muito obrigado pela preferência!
        </p>
        <a href="dashboard.php" class="inline-block w-full py-4 rounded-xl font-bold text-white bg-green-600 hover:bg-green-700 transition-colors shadow-lg">
            Ir para o Dashboard
        </a>
    </div>
</main>
</body>
</html>
