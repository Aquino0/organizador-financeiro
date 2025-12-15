<?php
// api/update_despesa.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['descricao']) || !isset($data['valor']) || !isset($data['categoria']) || !isset($data['data'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$user_id = getCurrentUserId();
$id = $data['id'];
$descricao = sanitize($data['descricao']);
$valor = floatval($data['valor']);
$categoria = sanitize($data['categoria']);
$data_lancamento = $data['data'];

try {
    $stmt = $pdo->prepare("UPDATE despesas SET descricao = ?, valor = ?, categoria = ?, data = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$descricao, $valor, $categoria, $data_lancamento, $id, $user_id]);

    jsonResponse(['success' => true, 'message' => 'Despesa atualizada com sucesso']);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao atualizar despesa'], 500);
}
?>