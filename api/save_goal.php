<?php
// api/save_goal.php
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

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

try {
    // $pdo is available from src/db.php

    // Auto-fix: Criar tabela se não existir (PostgreSQL)
    $pdo->exec("
CREATE TABLE IF NOT EXISTS financial_goals (
id SERIAL PRIMARY KEY,
user_id INT NOT NULL,
title VARCHAR(255) NOT NULL,
target_amount DECIMAL(10, 2) NOT NULL,
current_amount DECIMAL(10, 2) DEFAULT 0.00,
deadline DATE NULL,
color VARCHAR(50) DEFAULT 'blue',
icon VARCHAR(50) DEFAULT '🎯',
status VARCHAR(20) DEFAULT 'active',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
");

    if (isset($data['id']) && !empty($data['id'])) {
        // UPDATE
        $stmt = $pdo->prepare("
UPDATE financial_goals
SET title = :title, target_amount = :target, deadline = :deadline, color = :color, icon = :icon
WHERE id = :id AND user_id = :user_id
");
        $stmt->execute([
            'title' => $data['title'],
            'target' => $data['target_amount'],
            'deadline' => !empty($data['deadline']) ? $data['deadline'] : null,
            'color' => $data['color'] ?? 'blue',
            'icon' => $data['icon'] ?? '🎯',
            'id' => $data['id'],
            'user_id' => $user_id
        ]);
        echo json_encode(['success' => true, 'message' => 'Meta atualizada!']);
    } else {
        // INSERT
        $stmt = $pdo->prepare("
INSERT INTO financial_goals (user_id, title, target_amount, current_amount, deadline, color, icon)
VALUES (:user_id, :title, :target, :current, :deadline, :color, :icon)
");
        $stmt->execute([
            'user_id' => $user_id,
            'title' => $data['title'],
            'target' => $data['target_amount'],
            'current' => $data['initial_amount'] ?? 0,
            'deadline' => !empty($data['deadline']) ? $data['deadline'] : null,
            'color' => $data['color'] ?? 'blue',
            'icon' => $data['icon'] ?? '🎯'
        ]);
        echo json_encode(['success' => true, 'message' => 'Meta criada!']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao salvar meta: ' . $e->getMessage()]);
}
?>