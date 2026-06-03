<?php
// api/pagar_fatura.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cartao_id']) || !isset($data['fatura_mes'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$user_id = getCurrentUserId();
$cartao_id = intval($data['cartao_id']);
$fatura_mes = $data['fatura_mes'];

try {
    // Garantir que o cartão pertence ao usuário
    $stmt = $pdo->prepare("SELECT id FROM cartoes WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartao_id, $user_id]);
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Cartão não encontrado'], 404);
    }

    // Marcar todas as compras do mês como pagas
    $stmtUpd = $pdo->prepare("UPDATE despesas SET pago = 1 WHERE cartao_id = ? AND TO_CHAR(data, 'YYYY-MM') = ?");
    $stmtUpd->execute([$cartao_id, $fatura_mes]);
    
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao pagar fatura: ' . $e->getMessage()], 500);
}
?>
