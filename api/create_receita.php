<?php
// api/create_receita.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['descricao']) || !isset($data['valor']) || !isset($data['categoria']) || !isset($data['data'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$user_id = getCurrentUserId();
$descricao = sanitize($data['descricao']);
$valor = floatval($data['valor']);
$categoria = sanitize($data['categoria']);
$base_date = $data['data'];
$conta = isset($data['conta']) ? sanitize($data['conta']) : 'Carteira';
$pago = isset($data['pago']) ? (bool) $data['pago'] : false; // Default false so unchecked (missing) means not paid
$repetir = isset($data['repetir']) ? intval($data['repetir']) : 1;

if ($repetir < 1)
    $repetir = 1;
if ($repetir > 60)
    $repetir = 60; // Limit

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO receitas (user_id, descricao, valor, categoria, data, conta, pago) VALUES (?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < $repetir; $i++) {
        // Calculate date for this iteration
        // Be careful with day overflow (e.g. Jan 31 + 1 month -> Feb 28 or Mar 3 depending on logic)
        // PHP's strtotime handles it reasonably well usually, but keep simple for now
        $current_date = date('Y-m-d', strtotime($base_date . " +$i month"));

        // Append (X/Y) to description if repeating
        $desc_final = $descricao;
        if ($repetir > 1) {
            $desc_final .= " (" . ($i + 1) . "/$repetir)";
        }

        $stmt->execute([$user_id, $desc_final, $valor, $categoria, $current_date, $conta, $pago ? 1 : 0]);
    }

    $pdo->commit();
    jsonResponse(['success' => true, 'message' => 'Receita(s) adicionada(s) com sucesso']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(['error' => 'Erro ao adicionar receita: ' . $e->getMessage()], 500);
}
?>