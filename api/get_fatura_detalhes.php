<?php
// api/get_fatura_detalhes.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if (!isset($_GET['id'])) {
    jsonResponse(['error' => 'ID do cartão não fornecido'], 400);
}

$user_id = getCurrentUserId();
$cartao_id = intval($_GET['id']);
$filtro_mes = $_GET['mes'] ?? date('Y-m'); // Mês da fatura

try {
    // Pegar detalhes do cartão
    $stmt = $pdo->prepare("SELECT * FROM cartoes WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartao_id, $user_id]);
    $cartao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cartao) {
        jsonResponse(['error' => 'Cartão não encontrado'], 404);
    }

    $fechamento_dia = str_pad($cartao['fechamento'], 2, '0', STR_PAD_LEFT);
    $vencimento_dia = str_pad($cartao['vencimento'], 2, '0', STR_PAD_LEFT);
    
    // Lógica de datas (idêntica a list_lancamentos)
    $data_fechamento_atual = '';
    $data_fechamento_anterior = '';
    
    if ($cartao['fechamento'] < $cartao['vencimento']) {
        $data_fechamento_atual = $filtro_mes . '-' . $fechamento_dia;
        $data_fechamento_anterior = date('Y-m-d', strtotime($data_fechamento_atual . ' -1 month'));
    } else {
        $data_fechamento_atual = date('Y-m-d', strtotime($filtro_mes . '-01 -1 month')) ; 
        $data_fechamento_atual = date('Y-m-', strtotime($data_fechamento_atual)) . $fechamento_dia;
        $data_fechamento_anterior = date('Y-m-d', strtotime($data_fechamento_atual . ' -1 month'));
    }

    // Buscar despesas dessa fatura específica
    $stmtFat = $pdo->prepare("SELECT id, descricao, valor, categoria, data, created_at, observacao, pago FROM despesas WHERE cartao_id = ? AND data > ? AND data <= ? ORDER BY data DESC, created_at DESC");
    $stmtFat->execute([$cartao_id, $data_fechamento_anterior, $data_fechamento_atual]);
    $compras = $stmtFat->fetchAll(PDO::FETCH_ASSOC);

    // Soma total e checagem de pagamento
    $total = 0;
    $todas_pagas = true;
    foreach ($compras as $c) {
        $total += (float) $c['valor'];
        if (!$c['pago']) {
            $todas_pagas = false;
        }
    }
    
    if (count($compras) === 0) {
        $todas_pagas = false;
    }

    jsonResponse([
        'cartao' => $cartao,
        'fatura_mes' => $filtro_mes,
        'data_inicio' => $data_fechamento_anterior,
        'data_fechamento' => $data_fechamento_atual,
        'data_vencimento' => $filtro_mes . '-' . $vencimento_dia,
        'total' => $total,
        'is_paga' => $todas_pagas,
        'compras' => $compras
    ]);

} catch (Exception $e) {
    jsonResponse(['error' => 'Erro interno: ' . $e->getMessage()], 500);
}
?>
