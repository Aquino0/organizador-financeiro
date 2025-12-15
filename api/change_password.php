<?php
// api/change_password.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$currentPass = $data['current_password'] ?? '';
$newPass = $data['new_password'] ?? '';

if (!$currentPass || !$newPass) {
    jsonResponse(['error' => 'Todos os campos são obrigatórios'], 400);
}

if (strlen($newPass) < 6) {
    jsonResponse(['error' => 'A nova senha deve ter pelo menos 6 caracteres'], 400);
}

try {
    // Get current hash
    $stmt = $pdo->prepare("SELECT senha_hash FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($currentPass, $user['senha_hash'])) {
        jsonResponse(['error' => 'Senha atual incorreta'], 401);
    }

    // Update with new hash
    $newHash = password_hash($newPass, PASSWORD_DEFAULT);
    $upd = $pdo->prepare("UPDATE users SET senha_hash = ? WHERE id = ?");
    $upd->execute([$newHash, $user_id]);

    jsonResponse(['success' => true, 'message' => 'Senha alterada com sucesso']);

} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao atualizar senha: ' . $e->getMessage()], 500);
}
?>