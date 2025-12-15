<?php
// api/update_reserve.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$val = isset($input['valor']) ? floatval($input['valor']) : null;

if ($val === null || $val < 0 || $val > 100) {
    jsonResponse(['error' => 'Valor inválido (0-100)'], 400);
}

$user_id = getCurrentUserId();

try {
    $stmt = $pdo->prepare("UPDATE users SET configs_reserva = ? WHERE id = ?");
    $stmt->execute([$val, $user_id]);
    jsonResponse(['success' => true, 'message' => 'Configuração salva']);
} catch (PDOException $e) {
    jsonResponse(['error' => 'Erro ao salvar: ' . $e->getMessage()], 500);
}
?>