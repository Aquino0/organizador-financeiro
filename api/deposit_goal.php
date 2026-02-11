<?php
// api/deposit_goal.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db_connect.php';

header('Content-Type: application/json');

if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$user_id = getCurrentUserId();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}

$amount = floatval($data['amount']);
if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Valor deve ser positivo']);
    exit;
}

try {
    $pdo = getPDO();

    // Atualizar saldo da meta
    $stmt = $pdo->prepare("
        UPDATE financial_goals 
        SET current_amount = current_amount + :amount 
        WHERE id = :id AND user_id = :user_id
    ");
    $stmt->execute([
        'amount' => $amount,
        'id' => $data['id'],
        'user_id' => $user_id
    ]);

    if ($stmt->rowCount() > 0) {
        // Buscar novo saldo e progresso para retornar (para efeito visual/confetti)
        $stmtGet = $pdo->prepare("SELECT current_amount, target_amount FROM financial_goals WHERE id = :id");
        $stmtGet->execute(['id' => $data['id']]);
        $goal = $stmtGet->fetch(PDO::FETCH_ASSOC);

        $newProgress = ($goal['target_amount'] > 0) ? round(($goal['current_amount'] / $goal['target_amount']) * 100, 1) : 0;

        echo json_encode([
            'success' => true,
            'message' => 'Depósito realizado!',
            'new_amount' => $goal['current_amount'],
            'new_progress' => $newProgress,
            'goal_reached' => ($newProgress >= 100)
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Meta não encontrada']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao depositar: ' . $e->getMessage()]);
}
?>