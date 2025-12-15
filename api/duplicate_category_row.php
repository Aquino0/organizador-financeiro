<?php
// api/duplicate_category_row.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();

$dataInput = json_decode(file_get_contents('php://input'), true);
$catId = intval($dataInput['id'] ?? 0);
$year = intval($dataInput['year'] ?? date('Y'));

if (!$catId)
    jsonResponse(['error' => 'ID inválido'], 400);

try {
    $pdo->beginTransaction();

    // 1. Get Original Category
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? AND user_id = ?");
    $stmt->execute([$catId, $user_id]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cat)
        throw new Exception("Categoria não encontrada");

    // 2. Create New Category
    $newName = $cat['nome'] . " (Cópia)";
    // Ensure unique name loop if needed, but for now simple copy
    $stmtNew = $pdo->prepare("INSERT INTO categories (user_id, nome, tipo, cor, ordem) VALUES (?, ?, ?, ?, ?)");
    $stmtNew->execute([$user_id, $newName, $cat['tipo'], $cat['cor'], $cat['ordem'] + 1]);
    $newId = $pdo->lastInsertId();

    // 3. Copy Forecast Data for the Year
    $table = ($cat['tipo'] === 'receita') ? 'receitas' : 'despesas';

    // Select existing rows
    $sqlData = "
        SELECT * FROM $table 
        WHERE user_id = ? 
          AND categoria = ? 
          AND EXTRACT(YEAR FROM data) = ? 
          AND is_forecast = TRUE
    ";
    $stmtData = $pdo->prepare($sqlData);
    $stmtData->execute([$user_id, $cat['nome'], $year]);
    $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);

    // Insert new rows
    $sqlInsert = "
        INSERT INTO $table (user_id, descricao, valor, categoria, data, is_forecast) 
        VALUES (?, ?, ?, ?, ?, TRUE)
    ";
    $stmtInsert = $pdo->prepare($sqlInsert);

    foreach ($rows as $row) {
        $stmtInsert->execute([
            $user_id,
            "Orçamento " . $newName,
            $row['valor'],
            $newName,
            $row['data']
        ]);
    }

    $pdo->commit();
    jsonResponse(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    jsonResponse(['error' => $e->getMessage()], 500);
}
?>