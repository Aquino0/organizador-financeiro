<?php
// api/delete_receita.php
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

$id = $data['id'];
$user_id = getCurrentUserId();

try {
    // Garante que a receita pertence ao usuário logado antes de deletar
    $stmt = $pdo->prepare("DELETE FROM receitas WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true, 'message' => 'Receita excluída']);
    } else {
        jsonResponse(['error' => 'Receita não encontrada ou permissão negada'], 404);
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao excluir receita'], 500);
}
?>