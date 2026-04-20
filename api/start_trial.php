<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/auth.php';
requireAuth();
require_once __DIR__ . '/../src/db.php';

$userId = getCurrentUserId();

try {
    global $pdo;
    
    // Verifica se já teve periodo (seja trial passado ou pagto passado)
    $stmt = $pdo->prepare("SELECT current_period_end FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!empty($user['current_period_end'])) {
        echo json_encode(['success' => false, 'error' => 'Sua conta não é elegível para o teste gratuito.']);
        exit;
    }
    
    // Atualiza para free trial (20 dias)
    $updateStmt = $pdo->prepare("UPDATE users SET subscription_status = 'active', current_period_end = NOW() + INTERVAL '20 days' WHERE id = ?");
    $updateStmt->execute([$userId]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
