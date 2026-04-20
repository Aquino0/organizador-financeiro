<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/auth.php';
requireAdmin();
require_once __DIR__ . '/../src/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$targetUserId = $input['user_id'] ?? null;

if (!$targetUserId) {
    echo json_encode(['success' => false, 'error' => 'ID do usuário não fornecido.']);
    exit;
}

try {
    global $pdo;
    
    // Revoga o acesso alterando para free e zerando a data de expiração (para impedir q ele volte ser ativo no dia seguinte)
    $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'free', current_period_end = NULL WHERE id = ?");
    $stmt->execute([$targetUserId]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
