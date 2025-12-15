<?php
// api/migration_add_fields.php
require_once __DIR__ . '/../src/db.php';

try {
    // Add columns to 'receitas'
    $pdo->exec("
ALTER TABLE receitas
ADD COLUMN IF NOT EXISTS conta VARCHAR(50) DEFAULT 'Carteira',
ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT TRUE
");
    echo "Tabela 'receitas' atualizada.<br>";

    // Add columns to 'despesas'
    $pdo->exec("
ALTER TABLE despesas
ADD COLUMN IF NOT EXISTS conta VARCHAR(50) DEFAULT 'Carteira',
ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT FALSE
");
    echo "Tabela 'despesas' atualizada.<br>";

    echo "Migração de campos concluída com sucesso.";

} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage();
}
?>