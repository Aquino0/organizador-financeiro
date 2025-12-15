<?php
// api/add_transaction.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

// Validation
$tipo = $data['tipo'] ?? 'despesa';
$descricao = sanitize($data['descricao'] ?? '');
$valor = floatval($data['valor'] ?? 0);
$categoria = sanitize($data['categoria'] ?? '');
if (empty($descricao)) {
    $descricao = $categoria;
}

// Frontend sends 'data_mes' (YYYY-MM). We append '-01' or use 'data' if present.
$rawDate = $data['data_mes'] ?? ($data['data'] ?? date('Y-m-d'));
// Normalizing to YYYY-MM-01 if it looks like YYYY-MM
if (preg_match('/^\d{4}-\d{2}$/', $rawDate)) {
    $rawDate .= '-01';
}
$user_id = getCurrentUserId();

if ($valor <= 0) {
    jsonResponse(['error' => 'Valor deve ser positivo'], 400);
}

// Map 'tipo' to table name
$table = ($tipo === 'receita') ? 'receitas' : 'despesas';

$is_forecast = !empty($data['is_forecast']); // Boolean

$repeat = !empty($data['repeat']);
$repeat_count = intval($data['repeat_count'] ?? 1);
if (!$repeat)
    $repeat_count = 1;
if ($repeat_count > 60)
    $repeat_count = 60; // Hard limit

try {
    // $pdo->beginTransaction(); // Debug: Remove transaction

    $baseDate = new DateTime($rawDate);
    $ids = [];

    for ($i = 0; $i < $repeat_count; $i++) {
        $currentDate = clone $baseDate;
        $currentDate->modify("+$i month");
        $dateStr = $currentDate->format('Y-m-d');

        $descFinal = $descricao;
        if ($repeat_count > 1) {
            $descFinal .= " (" . ($i + 1) . "/$repeat_count)";
        }

        // Prepare inside or outside? Prepared statements are bound. 
        // We can reuse prepare outside loop for efficiency, but inside is fine for debug.
        // Added is_forecast column
        $stmt = $pdo->prepare("INSERT INTO $table (user_id, descricao, valor, categoria, data, is_forecast) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $user_id);
        $stmt->bindValue(2, $descFinal);
        $stmt->bindValue(3, $valor);
        $stmt->bindValue(4, $categoria);
        $stmt->bindValue(5, $dateStr);
        $stmt->bindValue(6, $is_forecast, PDO::PARAM_BOOL);
        $stmt->execute();

        $ids[] = $pdo->lastInsertId();
    }

    // $pdo->commit();
    jsonResponse(['success' => true, 'ids' => $ids, 'message' => 'Lançamento(s) adicionado(s) com sucesso']);

} catch (PDOException $e) {
    // if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("AddTransaction Error: " . $e->getMessage());
    jsonResponse(['error' => 'Erro DB: ' . $e->getMessage()], 500);
}
?>