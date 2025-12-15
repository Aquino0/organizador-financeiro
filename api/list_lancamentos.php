<?php
// api/list_lancamentos.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

$user_id = getCurrentUserId();
$filtro_mes = $_GET['mes'] ?? date('Y-m'); // Formato YYYY-MM

try {
    // 1. Calcular Saldo Anterior (Tudo antes do dia 01 deste mês, APENAS PREVISTO/PAGO? O ideal é só pago para saldo real)
    // Se o usuário quer que o saldo atualize, assumimos saldo REALIZADO.
    $start_date = $filtro_mes . '-01';

    // 1a. Calcular Saldo Inicial Realizado (Até dia 01, APENAS PAGO)
    $stmt_real = $pdo->prepare("
        SELECT 
        (SELECT COALESCE(SUM(valor),0) FROM receitas WHERE user_id = ? AND data < ? AND pago = TRUE) 
        - 
        (SELECT COALESCE(SUM(valor),0) FROM despesas WHERE user_id = ? AND data < ? AND pago = TRUE) 
        as saldo_inicial
    ");
    $stmt_real->execute([$user_id, $start_date, $user_id, $start_date]);
    $saldo_atual = (float) $stmt_real->fetchColumn();

    // 1b. Calcular Saldo Inicial Previsto (Tudo antes do dia 01, PAGO OU NÃO)
    $stmt_prev = $pdo->prepare("
        SELECT 
        (SELECT COALESCE(SUM(valor),0) FROM receitas WHERE user_id = ? AND data < ? AND is_forecast = FALSE) 
        - 
        (SELECT COALESCE(SUM(valor),0) FROM despesas WHERE user_id = ? AND data < ? AND is_forecast = FALSE) 
        as saldo_inicial_previsto
    ");
    $stmt_prev->execute([$user_id, $start_date, $user_id, $start_date]);
    $saldo_previsto_atual = (float) $stmt_prev->fetchColumn();

    // 2. Fetch Transactions (Combined)
    // Calculate End Date
    $end_date = date('Y-m-t', strtotime($start_date));

    $sql = "
    SELECT * FROM (
        SELECT id, 'receita' as tipo, descricao, valor, categoria, data, created_at, pago, observacao, conta 
        FROM receitas WHERE user_id = ? AND is_forecast = FALSE AND data BETWEEN ? AND ?
        UNION ALL
        SELECT id, 'despesa' as tipo, descricao, valor, categoria, data, created_at, pago, observacao, conta
        FROM despesas WHERE user_id = ? AND is_forecast = FALSE AND data BETWEEN ? AND ?
    ) as combined
    ORDER BY data DESC, created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $user_id,
        $start_date,
        $end_date,
        $user_id,
        $start_date,
        $end_date
    ]);
    $lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Processar acumulado e previsto
    $lancamentos_processed = [];
    $lancamentos_asc = array_reverse($lancamentos); // Oldest first

    foreach ($lancamentos_asc as $item) {
        $val = (float) $item['valor'];
        $pago = ($item['pago'] === true || $item['pago'] === 't' || $item['pago'] === 1);

        // --- Saldo Acumulado (Realizado) ---
        if ($pago) {
            if ($item['tipo'] === 'receita') {
                $saldo_atual += $val;
            } else {
                $saldo_atual -= $val;
            }
        }

        // --- Saldo Previsto (Tudo) ---
        if ($item['tipo'] === 'receita') {
            $saldo_previsto_atual += $val;
        } else {
            $saldo_previsto_atual -= $val;
        }

        $item['saldo_acumulado'] = $saldo_atual;
        $item['saldo_previsto'] = $saldo_previsto_atual;
        $item['pago'] = $pago;
        $lancamentos_processed[] = $item;
    }

    // Reverse back to DESC for display
    $lancamentos_processed = array_reverse($lancamentos_processed);

    // 4. Retornar (Invertendo para mostrar DESC no frontend se preferir, 
    // mas o frontend já agrupa por data. Vamos mandar na ordem processada para garantir consistência)
    // O frontend agrupa e ordena os dias.

    jsonResponse($lancamentos_processed);

} catch (PDOException $e) {
    jsonResponse(['error' => 'Erro ao buscar lançamentos: ' . $e->getMessage()], 500);
}
?>