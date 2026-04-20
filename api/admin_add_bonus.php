<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/auth.php';
requireAdmin();
require_once __DIR__ . '/../src/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$targetUserId = $input['user_id'] ?? null;
$days = intval($input['days'] ?? 0);

if (!$targetUserId || $days <= 0) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos.']);
    exit;
}

try {
    global $pdo;
    // Check if user already has an active period to append to, or if we start from today
    $stmt = $pdo->prepare("SELECT current_period_end FROM users WHERE id = ?");
    $stmt->execute([$targetUserId]);
    $user = $stmt->fetch();
    
    $baseDate = 'NOW()';
    if (!empty($user['current_period_end']) && strtotime($user['current_period_end']) > time()) {
        $baseDate = 'current_period_end'; // Add to existing time
    }
    
    $updateStmt = $pdo->prepare("UPDATE users SET subscription_status = 'active', current_period_end = {$baseDate} + INTERVAL '$days days' WHERE id = ?");
    $updateStmt->execute([$targetUserId]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
