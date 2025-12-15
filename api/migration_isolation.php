<?php
// api/migration_isolation.php
require_once '../src/db.php';

try {
    // Add is_forecast column to receitas
    $pdo->exec("ALTER TABLE receitas ADD COLUMN IF NOT EXISTS is_forecast BOOLEAN DEFAULT FALSE");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_receitas_forecast ON receitas(user_id, is_forecast)");

    // Add is_forecast column to despesas
    $pdo->exec("ALTER TABLE despesas ADD COLUMN IF NOT EXISTS is_forecast BOOLEAN DEFAULT FALSE");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_despesas_forecast ON despesas(user_id, is_forecast)");

    echo "Colunas de isolamento (is_forecast) criadas com sucesso.";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>