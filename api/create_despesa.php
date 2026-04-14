<?php
// api/create_despesa.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

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
$pago = isset($data['pago']) ? (bool) $data['pago'] : false; // Default false for expense usually

$repeat_mode = isset($data['repeat_mode']) ? sanitize($data['repeat_mode']) : (isset($data['global_repeat_mode']) ? sanitize($data['global_repeat_mode']) : 'parcelado');
$frequencia = isset($data['frequencia']) ? sanitize($data['frequencia']) : 'Mensal';

$repetir = 1;
if ($repeat_mode === 'fixa') {
    $repetir = 12; // 12 default for infinite fixed costs in loop insertions
} else {
    $repetir = isset($data['repetir']) ? intval($data['repetir']) : 1;
}

if ($repetir < 1) $repetir = 1;
if ($repetir > 60) $repetir = 60; // Limit

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO despesas (user_id, descricao, valor, categoria, data, conta, pago) VALUES (?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < $repetir; $i++) {
        // Calculate date for this iteration based on frequency
        $current_date = $base_date;
        $freq_lower = strtolower($frequencia);

        switch ($freq_lower) {
            case 'diário': $current_date = date('Y-m-d', strtotime($base_date . " +$i day")); break;
            case 'semanal': $current_date = date('Y-m-d', strtotime($base_date . " +$i week")); break;
            case 'quinzena': $current_date = date('Y-m-d', strtotime($base_date . " + " . ($i * 15) . " day")); break;
            case 'mensal': $current_date = date('Y-m-d', strtotime($base_date . " +$i month")); break;
            case 'bimestral': $current_date = date('Y-m-d', strtotime($base_date . " + " . ($i * 2) . " month")); break;
            case 'trimestral': $current_date = date('Y-m-d', strtotime($base_date . " + " . ($i * 3) . " month")); break;
            case 'semestral': $current_date = date('Y-m-d', strtotime($base_date . " + " . ($i * 6) . " month")); break;
            case 'anual': $current_date = date('Y-m-d', strtotime($base_date . " +$i year")); break;
            default: $current_date = date('Y-m-d', strtotime($base_date . " +$i month")); break;
        }

        // Append (X/Y) to description if repeating AND parcelado
        $desc_final = $descricao;
        if ($repetir > 1 && $repeat_mode !== 'fixa') {
            $desc_final .= " (" . ($i + 1) . "/$repetir)";
        } else if ($repeat_mode === 'fixa') {
            $desc_final .= " (Fixa)";
        }

        $stmt->execute([$user_id, $desc_final, $valor, $categoria, $current_date, $conta, $pago ? 1 : 0]);
    }

    $pdo->commit();
    jsonResponse(['success' => true, 'message' => 'Despesa(s) adicionada(s) com sucesso']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(['error' => 'Erro ao adicionar despesa: ' . $e->getMessage()], 500);
}