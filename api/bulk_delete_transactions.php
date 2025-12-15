<?php
// api/bulk_delete_transactions.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$items = $input['items'] ?? []; // Array of objects {id: 1, tipo: 'receita'/'despesa'}

if (empty($items) || !is_array($items)) {
    jsonResponse(['error' => 'Nenhum item selecionado'], 400);
}

$user_id = getCurrentUserId();
$deletedCount = 0;

try {
    $pdo->beginTransaction();

    $stmtReceita = $pdo->prepare("DELETE FROM receitas WHERE id = ? AND user_id = ?");
    $stmtDespesa = $pdo->prepare("DELETE FROM despesas WHERE id = ? AND user_id = ?");

    foreach ($items as $item) {
        $id = intval($item['id']);
        $tipo = $item['tipo'];

        if ($tipo === 'receita') {
            $stmtReceita->execute([$id, $user_id]);
            $deletedCount += $stmtReceita->rowCount();
        } else {
            $stmtDespesa->execute([$id, $user_id]);
            $deletedCount += $stmtDespesa->rowCount();
        }
    }

    $pdo->commit();
    jsonResponse(['success' => true, 'deleted_count' => $deletedCount]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(['error' => 'Erro ao excluir itens: ' . $e->getMessage()], 500);
}
?>