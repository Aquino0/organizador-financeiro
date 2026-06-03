<?php
// api/create_cartao.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nome']) || !isset($data['limite']) || !isset($data['fechamento']) || !isset($data['vencimento'])) {
    jsonResponse(['error' => 'Dados incompletos'], 400);
}

$user_id = getCurrentUserId();
$nome = sanitize($data['nome']);
$bandeira = isset($data['bandeira']) ? sanitize($data['bandeira']) : 'Outros';
$limite = floatval($data['limite']);
$fechamento = intval($data['fechamento']);
$vencimento = intval($data['vencimento']);
$cor = isset($data['cor']) ? sanitize($data['cor']) : '#2563eb';

if ($fechamento < 1 || $fechamento > 31 || $vencimento < 1 || $vencimento > 31) {
    jsonResponse(['error' => 'Dias inválidos'], 400);
}

try {
    $stmt = $pdo->prepare("INSERT INTO cartoes (user_id, nome, bandeira, limite, fechamento, vencimento, cor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $nome, $bandeira, $limite, $fechamento, $vencimento, $cor]);
    
    jsonResponse(['success' => true, 'message' => 'Cartão adicionado com sucesso']);
} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao adicionar cartão: ' . $e->getMessage()], 500);
}
?>
