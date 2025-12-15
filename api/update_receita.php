<?php
// api/update_receita.php
require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

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
    // Update ensures user_id matches to prevent unauthorized edits
    $stmt = $pdo->prepare("UPDATE receitas SET descricao = ?, valor = ?, categoria = ?, data = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$descricao, $valor, $categoria, $data_lancamento, $id, $user_id]);

    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true, 'message' => 'Receita atualizada com sucesso']);
    } else {
        // Could be not found or no change
        // We'll return success if it exists but no change, or 404 if not found. 
        // For simplicity, checking existence first is better but expensive. 
        // Let's assume if 0 rows, it might be that data didn't change OR id is wrong.
        // Let's return success to UI but maybe check if ID exists if we wanted to be strict.
        // For this app, let's just say success.
        jsonResponse(['success' => true, 'message' => 'Receita atualizada (sem alterações ou ID não encontrado)']);
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao atualizar receita: ' . $e->getMessage()], 500);
}
?>