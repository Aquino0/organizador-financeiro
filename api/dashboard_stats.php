<?php
// api/dashboard_stats.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

$user_id = getCurrentUserId();
$current_month = $_GET['mes'] ?? date('Y-m');

try {
    // OPTIMIZED SINGLE QUERY
    // Fetches Monthly Income, Monthly Expense, and Total All-Time Balance in one go.
    $stmt = $pdo->prepare("
        SELECT 
            -- Renda Mensal (Pago=True, Data=Mês Atual)
            COALESCE(SUM(CASE WHEN tipo = 'receita' AND TO_CHAR(data, 'YYYY-MM') = ? THEN valor ELSE 0 END), 0) as renda_mensal,
            
            -- Despesa Mensal (Pago=True, Data=Mês Atual)
            COALESCE(SUM(CASE WHEN tipo = 'despesa' AND TO_CHAR(data, 'YYYY-MM') = ? THEN valor ELSE 0 END), 0) as despesas_mensal,
            
            -- Saldo Acumulado (Total Receitas Paga - Total Despesas Paga, ATÉ O MÊS SELECIONADO)
            COALESCE(SUM(CASE WHEN tipo = 'receita' AND TO_CHAR(data, 'YYYY-MM') <= ? THEN valor ELSE 0 END), 0) - 
            COALESCE(SUM(CASE WHEN tipo = 'despesa' AND TO_CHAR(data, 'YYYY-MM') <= ? THEN valor ELSE 0 END), 0) as saldo_acumulado

        FROM (
            SELECT 'receita' as tipo, valor, data, pago FROM receitas WHERE user_id = ? AND pago = TRUE AND is_forecast = FALSE
            UNION ALL
            SELECT 'despesa' as tipo, valor, data, pago FROM despesas WHERE user_id = ? AND pago = TRUE AND is_forecast = FALSE
        ) as all_trans
    ");

    // Params: Month (Renda), Month (Despesa), Month (Saldo Inc), Month (Saldo Exp), User, User
    $stmt->execute([$current_month, $current_month, $current_month, $current_month, $user_id, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $renda_mensal = (float) $result['renda_mensal'];
    $despesas_mensal = (float) $result['despesas_mensal'];
    $saldo_acumulado = (float) $result['saldo_acumulado'];

    jsonResponse([
        'renda_mensal' => $renda_mensal,
        'despesas_mensal' => $despesas_mensal,
        'saldo_acumulado' => $saldo_acumulado
    ]);

} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao calcular dashboard: ' . $e->getMessage()], 500);
}
?>