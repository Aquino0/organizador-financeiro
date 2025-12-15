<?php
// api/delete_despesa.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    jsonResponse(['error' => 'ID não fornecido'], 400);
}

$id = $data['id'];
$user_id = getCurrentUserId();

try {
    $stmt = $pdo->prepare("DELETE FROM despesas WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true, 'message' => 'Despesa excluída']);
    } else {
        jsonResponse(['error' => 'Despesa não encontrada ou permissão negada'], 404);
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao excluir despesa'], 500);
}
?>