<?php
// api/migration_ordem.php
require_once __DIR__ . '/../src/db.php';

try {
    $pdo->exec("ALTER TABLE categories ADD COLUMN IF NOT EXISTS ordem INTEGER DEFAULT 0");
    echo "Column 'ordem' added to categories table successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>