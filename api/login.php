<?php
// api/login.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['senha'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$email = $data['email'];
$senha = $data['senha'];

try {
    $stmt = $pdo->prepare("SELECT id, nome, senha_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha_hash'])) {
        // Use Stateless Auth
        loginUser($user['id'], $user['nome'], $user['role']);

        jsonResponse(['success' => true, 'message' => 'Login realizado com sucesso']);
    } else {
        jsonResponse(['error' => 'Credenciais inválidas'], 401);
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro interno do servidor'], 500);
}
?>