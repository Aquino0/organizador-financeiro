<?php
// api/toggle_status.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['tipo'])) {
    jsonResponse(['error' => 'ID ou Tipo hiante'], 400);
}

$user_id = getCurrentUserId();
$id = $data['id'];
$tipo = $data['tipo']; // 'receita' or 'despesa'

$table = ($tipo === 'receita') ? 'receitas' : 'despesas';

try {
    // First, get current status
    $stmt = $pdo->prepare("SELECT pago FROM $table WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Debugging info in error
        jsonResponse(['error' => "Item não encontrado (Tabela: $table, ID: $id, UID: $user_id)"], 404);
    }

    $current = $row['pago'];

    // Toggle
    // Postgres boolean handling: 't'/'f' or true/false
    // Toggle logic
    // Postgres boolean: true/false (native)
    $isPaid = ($current === true || $current === 't' || $current === 'true' || $current === 1 || $current === '1');
    $newStatusBool = !$isPaid;
    $newStatusStr = $newStatusBool ? 'true' : 'false';

    // Debug
    error_log("ToggleStatus: ID=$id Table=$table Current=" . ($isPaid ? 'PAID' : 'NOT PAID') . " New=" . ($newStatusBool ? 'PAID' : 'NOT PAID'));

    $updateStmt = $pdo->prepare("UPDATE $table SET pago = :pago WHERE id = :id AND user_id = :uid");

    // Bind as explicit boolean type for PDO if possible, or string 't'/'f'
    // For Postgres PDO, explicit boolean binding is safest.
    $updateStmt->bindValue(':pago', $newStatusBool, PDO::PARAM_BOOL);
    $updateStmt->bindValue(':id', $id);
    $updateStmt->bindValue(':uid', $user_id);
    $updateStmt->execute();

    if ($updateStmt->rowCount() === 0) {
        // Did not update any row?
        error_log("ToggleStatus Warning: 0 rows updated (ID $id).");
    }

    jsonResponse(['success' => true, 'new_status' => $newStatusBool]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro SQL: ' . $e->getMessage()], 500);
}
?>