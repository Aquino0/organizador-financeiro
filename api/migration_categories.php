<?php
// api/migration_categories.php
require_once '../src/db.php';

try {
    // 1. Create Table (PostgreSQL Syntax)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            nome VARCHAR(50) NOT NULL,
            tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('receita', 'despesa')),
            cor VARCHAR(20) DEFAULT '#cbd5e1',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE (user_id, nome, tipo)
        );
    ");
    echo "Tabela 'categories' verificada/criada.<br>";

    // 2. Migrate Existing Data
    $stmtUsers = $pdo->query("SELECT id FROM users");
    $users = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

    $defaults = [
        'receita' => [['Salário', '#10b981'], ['Investimentos', '#3b82f6'], ['Extras', '#8b5cf6']],
        'despesa' => [
            ['Moradia', '#ef4444'],
            ['Transporte', '#f97316'],
            ['Alimentação', '#eab308'],
            ['Saúde', '#ec4899'],
            ['Lazer', '#8b5cf6'],
            ['Outros', '#64748b']
        ]
    ];

    foreach ($users as $uid) {
        // Expenses
        $stmtDesp = $pdo->prepare("SELECT DISTINCT categoria FROM despesas WHERE user_id = ?");
        $stmtDesp->execute([$uid]);
        $catsDesp = $stmtDesp->fetchAll(PDO::FETCH_COLUMN);

        foreach ($catsDesp as $catName) {
            insertCategory($pdo, $uid, $catName, 'despesa', '#64748b');
        }

        // Income
        $stmtRec = $pdo->prepare("SELECT DISTINCT categoria FROM receitas WHERE user_id = ?");
        $stmtRec->execute([$uid]);
        $catsRec = $stmtRec->fetchAll(PDO::FETCH_COLUMN);

        foreach ($catsRec as $catName) {
            insertCategory($pdo, $uid, $catName, 'receita', '#10b981');
        }

        // Defaults
        foreach ($defaults as $type => $list) {
            foreach ($list as $item) {
                insertCategory($pdo, $uid, $item[0], $type, $item[1]);
            }
        }
    }
    echo "Migração de dados completada.<br>";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

function insertCategory($pdo, $uid, $name, $type, $color)
{
    if (empty(trim($name)))
        return;
    // Postgres ON CONFLICT
    $stmt = $pdo->prepare("
        INSERT INTO categories (user_id, nome, tipo, cor) 
        VALUES (?, ?, ?, ?)
        ON CONFLICT (user_id, nome, tipo) DO NOTHING
    ");
    $stmt->execute([$uid, trim($name), $type, $color]);
}
?>