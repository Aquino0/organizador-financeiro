<?php
// api/check_overdue.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

$user_id = getCurrentUserId();

try {
    // Check for expenses strictly BEFORE today (overdue)
    // d.pago = FALSE
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count, COALESCE(SUM(valor), 0) as total
        FROM despesas
        WHERE user_id = ? 
        AND pago = FALSE 
        AND data < CURRENT_DATE
    ");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $count = (int) $result['count'];
    $total = (float) $result['total'];

    jsonResponse([
        'overdue' => $count > 0,
        'count' => $count,
        'total_formatted' => number_format($total, 2, ',', '.')
    ]);

} catch (PDOException $e) {
    jsonResponse(['error' => 'Erro ao verificar atrasos'], 500);
}
