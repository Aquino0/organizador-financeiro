CREATE DATABASE IF NOT EXISTS ia_finance_crm;
USE ia_finance_crm;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Receitas
CREATE TABLE IF NOT EXISTS receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Despesas
CREATE TABLE IF NOT EXISTS despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para performance
CREATE INDEX idx_receitas_user_data ON receitas(user_id, data);
CREATE INDEX idx_despesas_user_data ON despesas(user_id, data);
CREATE INDEX idx_users_email ON users(email);

-- Usuário Admin padrão (Senha: admin123)
-- Hash gerado via password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (nome, email, senha_hash, role) VALUES 
('Admin', 'admin@empresa.com', '$2y$10$8.D./.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y.y', 'admin')
ON DUPLICATE KEY UPDATE id=id;
