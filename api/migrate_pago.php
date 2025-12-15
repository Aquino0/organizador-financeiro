<?php
// api/migrate_pago.php
require_once '../src/db.php';

try {
    // Add columns if they don't exist
    // PostgreSQL (Neon) syntax for IF NOT EXISTS in ALTER TABLE is tricky, usually we check via information_schema or just suppress errors.
    // simpler to just try add and catch specific error, or use a block.

    $commands = [
        "ALTER TABLE receitas ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT FALSE",
        "ALTER TABLE receitas ADD COLUMN IF NOT EXISTS observacao TEXT",
        "ALTER TABLE receitas ADD COLUMN IF NOT EXISTS conta VARCHAR(100)",

        "ALTER TABLE despesas ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT FALSE",
        "ALTER TABLE despesas ADD COLUMN IF NOT EXISTS observacao TEXT",
        "ALTER TABLE despesas ADD COLUMN IF NOT EXISTS conta VARCHAR(100)"
    ];

    foreach ($commands as $cmd) {
        try {
            $pdo->exec($cmd);
            echo "Executed: $cmd <br>";
        } catch (PDOException $e) {
            echo "Error executing $cmd: " . $e->getMessage() . "<br>";
        }
    }

    echo "Migration completed.";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
?>