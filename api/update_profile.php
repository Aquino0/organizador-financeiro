<?php
// api/update_profile.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    jsonResponse(['error' => 'Método inválido'], 405);
}


// Initialize response array
$response = ['success' => true, 'message' => 'Perfil atualizado com sucesso'];

try {
    // 1. Handle Name Update
    if (isset($_POST['nome'])) {
        $nome = trim($_POST['nome']);
        if (strlen($nome) < 2) {
            jsonResponse(['error' => 'O nome deve ter pelo menos 2 caracteres'], 400);
        }

        $stmt = $pdo->prepare("UPDATE users SET nome = ? WHERE id = ?");
        $stmt->execute([$nome, $user_id]);
        $_SESSION['user_name'] = $nome; // Update session
        $response['message'] = 'Perfil atualizado com sucesso';
    }

    // 2. Handle Photo Update (if file provided)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            jsonResponse(['error' => 'Formato de arquivo inválido. Apenas imagens são permitidas.'], 400);
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            jsonResponse(['error' => 'Arquivo muito grande (Máx 5MB)'], 400);
        }

        $uploadDir = '../uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newFileName = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $newFileName;
        $publicPath = 'uploads/avatars/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE id = ?");
            $stmt->execute([$publicPath, $user_id]);
            $_SESSION['foto_perfil'] = $publicPath;
            $response['foto_url'] = $publicPath;
        } else {
            throw new Exception('Falha ao mover arquivo enviado');
        }
    }

    jsonResponse($response);

} catch (Exception $e) {
    jsonResponse(['error' => 'Erro ao atualizar perfil: ' . $e->getMessage()], 500);
}
?>