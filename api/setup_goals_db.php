<?php
// api/setup_goals_db.php
require_once __DIR__ . '/../src/db_connect.php';

header('Content-Type: text/plain');

try {
    $pdo = getPDO();

    $sql = "
    CREATE TABLE IF NOT EXISTS financial_goals (
        id INT AUTO_INCREMENT PRIMARY KEY,
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sql);
    echo "Sucesso! Tabela 'financial_goals' verificada/criada.";

} catch (PDOException $e) {
    http_response_code(500);
    echo "Erro: " . $e->getMessage();
}
?>