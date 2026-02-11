// api/delete_goal.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';

header('Content-Type: application/json');

if (!isAuthenticated()) {
http_response_code(401);
echo json_encode(['error' => 'Não autorizado']);
exit;
}

$user_id = getCurrentUserId();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
http_response_code(400);
echo json_encode(['error' => 'ID não fornecido']);
exit;
}

try {
// $pdo available from db.php
$stmt = $pdo->prepare("DELETE FROM financial_goals WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $data['id'], 'user_id' => $user_id]);

if ($stmt->rowCount() > 0) {
echo json_encode(['success' => true, 'message' => 'Meta excluída!']);
} else {
http_response_code(404);
echo json_encode(['error' => 'Meta não encontrada']);
}

} catch (PDOException $e) {
http_response_code(500);
echo json_encode(['error' => 'Erro ao excluir meta: ' . $e->getMessage()]);
}
?>