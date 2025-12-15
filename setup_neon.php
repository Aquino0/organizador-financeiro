<?php
// setup_neon.php
require_once 'src/db.php';

echo "<h1>Configurando IAMFinance no Neon (Postgres)</h1>";

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS receitas (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS despesas (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_receitas_user_data ON receitas(user_id, data);
CREATE INDEX IF NOT EXISTS idx_despesas_user_data ON despesas(user_id, data);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Admin Padrão
INSERT INTO users (nome, email, senha_hash, role) 
VALUES ('Admin', 'admin@empresa.com', '$2y$10$8.D./.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y', 'admin')
ON CONFLICT (email) DO NOTHING;
";

try {
    $pdo->exec($sql);
    echo "<p style='color:green'>Tabelas criadas com sucesso!</p>";
    echo "<p>Agora apague este arquivo 'setup_neon.php' por segurança e acesse o <a href='index.php'>sistema</a>.</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>Erro ao criar tabelas: " . $e->getMessage() . "</p>";
}
?>