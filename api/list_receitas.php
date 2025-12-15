<?php
// api/list_receitas.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$user_id = getCurrentUserId();
$filtro_categoria = $_GET['categoria'] ?? null;
$filtro_mes = $_GET['mes'] ?? null; // Formato YYYY-MM

$query = "SELECT * FROM receitas WHERE user_id = ? AND is_forecast = FALSE";
$params = [$user_id];

if ($filtro_categoria) {
    $query .= " AND categoria = ?";
    $params[] = $filtro_categoria;
}

if ($filtro_mes) {
    $query .= " AND TO_CHAR(data, 'YYYY-MM') = ?";
    $params[] = $filtro_mes;
}

$query .= " ORDER BY data DESC, created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $receitas = $stmt->fetchAll();

    // Format fields if needed, or send raw
    foreach ($receitas as &$r) {
        $r['valor_formatado'] = formatCurrency($r['valor']);
        $r['data_formatada'] = formatDate($r['data']);
    }

    jsonResponse($receitas);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao listar receitas'], 500);
}
?>