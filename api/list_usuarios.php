<?php
// api/list_usuarios.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

try {
    $stmt = $pdo->query("SELECT id, nome, email, role, created_at FROM users ORDER BY created_at DESC");
    $usuarios = $stmt->fetchAll();

    foreach ($usuarios as &$u) {
        $u['data_criacao'] = formatDate($u['created_at']);
    }

    jsonResponse($usuarios);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao listar usuários'], 500);
}
?>