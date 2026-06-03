<?php
// api/delete_cartao.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    jsonResponse(['error' => 'ID não fornecido'], 400);
}

$user_id = getCurrentUserId();
$cartao_id = intval($data['id']);

try {
    $stmt = $pdo->prepare("DELETE FROM cartoes WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartao_id, $user_id]);
    
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao deletar cartão: ' . $e->getMessage()], 500);
}
?>
