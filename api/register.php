<?php
// api/register.php
require_once '../src/db.php';
require_once '../src/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nome']) || !isset($data['email']) || !isset($data['senha'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$nome = sanitize($data['nome']);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$senha = $data['senha']; // Senha será hashada, não precisa sanitize agressivo aqui

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['error' => 'Email inválido'], 400);
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Email já cadastrado'], 409);
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (nome, email, senha_hash) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senha_hash]);

    jsonResponse(['success' => true, 'message' => 'Usuário cadastrado com sucesso']);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao cadastrar usuário' . $e->getMessage()], 500);
}
?>