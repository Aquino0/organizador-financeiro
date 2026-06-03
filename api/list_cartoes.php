<?php
// api/list_cartoes.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';

requireAuth();

$user_id = getCurrentUserId();

try {
    $stmt = $pdo->prepare("SELECT * FROM cartoes WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $cartoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cartoes);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar cartões']);
}
?>
