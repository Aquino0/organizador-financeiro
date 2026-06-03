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
    
    // A data de início e fim será simplesmente o primeiro e último dia do mês
    $data_inicio = $filtro_mes . '-01';
    $data_fechamento_atual = date('Y-m-t', strtotime($data_inicio));

    // Buscar despesas dessa fatura (que agora é simplesmente o mês da despesa)
    $stmtFat = $pdo->prepare("
        SELECT id, descricao, valor, categoria, data, created_at, observacao, pago 
        FROM despesas 
        WHERE cartao_id = ? 
        AND TO_CHAR(data, 'YYYY-MM') = ? 
        ORDER BY data DESC, created_at DESC
    ");
    $stmtFat->execute([$cartao_id, $filtro_mes]);
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
        'data_inicio' => $data_inicio,
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
