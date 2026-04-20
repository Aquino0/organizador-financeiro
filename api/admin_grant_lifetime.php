<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/auth.php';
requireAdmin();
require_once __DIR__ . '/../src/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$targetUserId = $input['user_id'] ?? null;

if (!$targetUserId) {
    echo json_encode(['success' => false, 'error' => 'Faltou o ID do usuário.']);
    exit;
}

try {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'lifetime' WHERE id = ?");
    $stmt->execute([$targetUserId]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
