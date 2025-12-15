<?php
// api/list_despesas.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$user_id = getCurrentUserId();
$filtro_categoria = $_GET['categoria'] ?? null;
$filtro_mes = $_GET['mes'] ?? null; // Formato YYYY-MM

$query = "SELECT * FROM despesas WHERE user_id = ? AND is_forecast = FALSE";
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
    $despesas = $stmt->fetchAll();

    foreach ($despesas as &$d) {
        $d['valor_formatado'] = formatCurrency($d['valor']);
        $d['data_formatada'] = formatDate($d['data']);
    }

    jsonResponse($despesas);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao listar despesas'], 500);
}
?>