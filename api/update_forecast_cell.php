<?php
// api/update_forecast_cell.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();

$dataInput = json_decode(file_get_contents('php://input'), true);

$categoria = trim($dataInput['category'] ?? '');
$mes = intval($dataInput['month'] ?? 0); // 1-12
$ano = intval($dataInput['year'] ?? date('Y'));
$tipo = $dataInput['type'] ?? ''; // 'receita' or 'despesa'
$valor = floatval($dataInput['value'] ?? 0);

if (!$categoria || !$mes || !$ano || !in_array($tipo, ['receita', 'despesa'])) {
    jsonResponse(['error' => 'Dados inválidos'], 400);
}

try {
    $pdo->beginTransaction();

    $table = ($tipo === 'receita') ? 'receitas' : 'despesas';

    // 2. Insert new entry logic
    $applyAll = $dataInput['apply_all'] ?? false;

    if ($applyAll) {
        // DELETE ALL for this year/category
        $sqlDeleteAll = "
            DELETE FROM $table 
            WHERE user_id = ? 
              AND categoria = ? 
              AND EXTRACT(YEAR FROM data) = ? 
              AND is_forecast = TRUE
        ";
        $stmtDelete = $pdo->prepare($sqlDeleteAll);
        $stmtDelete->execute([$user_id, $categoria, $ano]);

        // INSERT 12 entries
        if ($valor > 0) {
            $sqlInsert = "
                INSERT INTO $table (user_id, descricao, valor, categoria, data, is_forecast) 
                VALUES (?, ?, ?, ?, ?, TRUE)
            ";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $descricao = "Orçamento " . $categoria;

            for ($m = 1; $m <= 12; $m++) {
                $dateStr = sprintf('%04d-%02d-01', $ano, $m);
                $stmtInsert->execute([$user_id, $descricao, $valor, $categoria, $dateStr]);
            }
        }
    } else {
        // Single Cell Update
        $sqlDelete = "
            DELETE FROM $table 
            WHERE user_id = ? 
              AND categoria = ? 
              AND EXTRACT(MONTH FROM data) = ? 
              AND EXTRACT(YEAR FROM data) = ? 
              AND is_forecast = TRUE
        ";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([$user_id, $categoria, $mes, $ano]);

        if ($valor > 0) {
            $dateStr = sprintf('%04d-%02d-01', $ano, $mes);

            $sqlInsert = "
                INSERT INTO $table (user_id, descricao, valor, categoria, data, is_forecast) 
                VALUES (?, ?, ?, ?, ?, TRUE)
            ";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $descricao = "Orçamento " . $categoria;

            $stmtInsert->bindValue(1, $user_id);
            $stmtInsert->bindValue(2, $descricao);
            $stmtInsert->bindValue(3, $valor);
            $stmtInsert->bindValue(4, $categoria);
            $stmtInsert->bindValue(5, $dateStr);

            $stmtInsert->execute();
        }
    }

    $pdo->commit();
    jsonResponse(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    jsonResponse(['error' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
}
?>