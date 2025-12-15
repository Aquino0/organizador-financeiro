<?php
// api/delete_user.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    jsonResponse(['error' => 'ID não fornecido'], 400);
}

$id = $data['id'];

// Prevent deleting self
if ($id == getCurrentUserId()) {
    jsonResponse(['error' => 'Não pode excluir a si mesmo'], 400);
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true, 'message' => 'Usuário excluído']);
    } else {
        jsonResponse(['error' => 'Usuário não encontrado'], 404);
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao excluir usuário'], 500);
}
?>