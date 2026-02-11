<?php
// api/get_goals.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';

header('Content-Type: application/json');

if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$user_id = getCurrentUserId();

try {
    // $pdo is available from src/db.php

    // Auto-fix: Criar tabela se não existir (PostgreSQL compatible)
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

    $stmt = $pdo->prepare("
SELECT * FROM financial_goals
WHERE user_id = :user_id
ORDER BY status ASC, deadline ASC
");
    $stmt->execute(['user_id' => $user_id]);
    $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular progresso para cada meta
    foreach ($goals as &$goal) {
        $target = floatval($goal['target_amount']);
        $current = floatval($goal['current_amount']);
        $goal['progress'] = ($target > 0) ? min(100, round(($current / $target) * 100, 1)) : 0;

        // Dias restantes
        if ($goal['deadline']) {
            $today = new DateTime();
            $deadline = new DateTime($goal['deadline']);
            $interval = $today->diff($deadline);
            $goal['days_left'] = $interval->invert ? 0 : $interval->days;
        } else {
            $goal['days_left'] = null;
        }
    }

    echo json_encode($goals);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar metas: ' . $e->getMessage()]);
}
?>