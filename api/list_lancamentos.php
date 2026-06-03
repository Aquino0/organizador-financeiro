<?php
// api/list_lancamentos.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

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
        SELECT id, 'receita' as tipo, descricao, valor, categoria, data, created_at, pago, observacao, conta, NULL as cartao_id
        FROM receitas WHERE user_id = ? AND is_forecast = FALSE AND data BETWEEN ? AND ?
        UNION ALL
        SELECT id, 'despesa' as tipo, descricao, valor, categoria, data, created_at, pago, observacao, conta, cartao_id
        FROM despesas WHERE user_id = ? AND is_forecast = FALSE AND data BETWEEN ? AND ? AND cartao_id IS NULL
    ) as combined
    ORDER BY data DESC, created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $start_date, $end_date, $user_id, $start_date, $end_date]);
    $lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Invoices for this month
    $stmtCards = $pdo->prepare("SELECT * FROM cartoes WHERE user_id = ?");
    $stmtCards->execute([$user_id]);
    $cartoes = $stmtCards->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cartoes as $cartao) {
        $vencimento_dia = str_pad($cartao['vencimento'], 2, '0', STR_PAD_LEFT);
        $fechamento_dia = str_pad($cartao['fechamento'], 2, '0', STR_PAD_LEFT);
        
        $data_vencimento = $filtro_mes . '-' . $vencimento_dia; // Ex: 2026-06-10
        
        // Se o fechamento é menor que o vencimento (Ex: F: 03, V: 10), o período da fatura de Junho (venc 10/06) 
        // vai de 04/05 até 03/06
        // Se o fechamento é maior que o vencimento (Ex: F: 25, V: 05), a fatura de Junho (venc 05/06)
        // vai de 26/04 até 25/05
        
        $data_fechamento_atual = '';
        $data_fechamento_anterior = '';
        
        if ($cartao['fechamento'] < $cartao['vencimento']) {
            $data_fechamento_atual = $filtro_mes . '-' . $fechamento_dia;
            $data_fechamento_anterior = date('Y-m-d', strtotime($data_fechamento_atual . ' -1 month'));
        } else {
            $data_fechamento_atual = date('Y-m-d', strtotime($filtro_mes . '-01 -1 month')) ; // Mês anterior
            $data_fechamento_atual = date('Y-m-', strtotime($data_fechamento_atual)) . $fechamento_dia;
            $data_fechamento_anterior = date('Y-m-d', strtotime($data_fechamento_atual . ' -1 month'));
        }
        
        // Intervalo: > fechamento_anterior AND <= fechamento_atual
        $stmtFatura = $pdo->prepare("SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE cartao_id = ? AND data > ? AND data <= ?");
        $stmtFatura->execute([$cartao['id'], $data_fechamento_anterior, $data_fechamento_atual]);
        $total_fatura = (float) $stmtFatura->fetchColumn();
        
        if ($total_fatura > 0) {
            // Verificar se esta fatura já foi paga (checando se existe um lançamento manual de pagamento, por ora vamos assumir falso)
            // Inject virtual expense
            $lancamentos[] = [
                'id' => 'fatura_' . $cartao['id'],
                'tipo' => 'despesa',
                'descricao' => 'Fatura ' . $cartao['nome'],
                'valor' => $total_fatura,
                'categoria' => 'Cartão de Crédito',
                'data' => $data_vencimento,
                'created_at' => date('Y-m-d H:i:s'),
                'pago' => false,
                'observacao' => 'Gerado automaticamente',
                'conta' => 'Cartão de Crédito',
                'cartao_id' => $cartao['id']
            ];
        }
    }
    
    // Re-sort array by date DESC
    usort($lancamentos, function($a, $b) {
        if ($a['data'] === $b['data']) {
            return $b['created_at'] <=> $a['created_at'];
        }
        return $b['data'] <=> $a['data'];
    });

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