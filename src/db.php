<?php
// src/db.php

// Simple .env parser to avoid dependencies
if (!function_exists('loadEnv')) {
    function loadEnv($path)
    {
        if (!file_exists($path))
            return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0)
                continue;
            if (strpos($line, '=') === false)
                continue;
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!getenv($name)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

loadEnv(__DIR__ . '/../.env');

$connection_string = getenv('DATABASE_URL');

if (!$connection_string) {
    die("ERRO CRITICO: DATABASE_URL não definida no arquivo .env");
}

try {
    $dbopts = parse_url($connection_string);
    $host = $dbopts["host"];
    $port = $dbopts["port"] ?? 5432;
    $user = $dbopts["user"];
    $pass = $dbopts["pass"];
    $db = ltrim($dbopts["path"], '/');

    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_EMULATE_PREPARES => true,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    // --- AUTO MIGRATION (DISABLED FOR PERFORMANCE) ---
/*
// Ensure new columns exist
try {
$pdo->exec("ALTER TABLE receitas ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT FALSE");
$pdo->exec("ALTER TABLE receitas ADD COLUMN IF NOT EXISTS observacao TEXT");
$pdo->exec("ALTER TABLE receitas ADD COLUMN IF NOT EXISTS conta VARCHAR(100)");

$pdo->exec("ALTER TABLE despesas ADD COLUMN IF NOT EXISTS pago BOOLEAN DEFAULT FALSE");
$pdo->exec("ALTER TABLE despesas ADD COLUMN IF NOT EXISTS observacao TEXT");
$pdo->exec("ALTER TABLE despesas ADD COLUMN IF NOT EXISTS observacao TEXT");
$pdo->exec("ALTER TABLE despesas ADD COLUMN IF NOT EXISTS conta VARCHAR(100)");

// USER CONFIGS
$pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS configs_reserva DECIMAL(5,2) DEFAULT 20.00");

// PERFORMANCE INDICES
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_receitas_pago_mes ON receitas (user_id, pago, data)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_despesas_pago_mes ON despesas (user_id, pago, data)");

// FIX ID AUTO INCREMENT (If table created without SERIAL)
try {
// Receitas
$pdo->exec("CREATE SEQUENCE IF NOT EXISTS receitas_id_seq");
// Set default if not set (safe to run multiple times, might error if already identity but we catch)
$pdo->exec("ALTER TABLE receitas ALTER COLUMN id SET DEFAULT nextval('receitas_id_seq')");
// Sync sequence
$pdo->exec("SELECT setval('receitas_id_seq', (SELECT MAX(id) FROM receitas))");

// Despesas
$pdo->exec("CREATE SEQUENCE IF NOT EXISTS despesas_id_seq");
$pdo->exec("ALTER TABLE despesas ALTER COLUMN id SET DEFAULT nextval('despesas_id_seq')");
$pdo->exec("SELECT setval('despesas_id_seq', (SELECT MAX(id) FROM despesas))");
} catch (Exception $e) {
// Ignore if already identity or sequence exists
}

// CATEGORIES TABLE
$pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
id SERIAL PRIMARY KEY,
user_id INT NOT NULL,
nome VARCHAR(50) NOT NULL,
tipo VARCHAR(20) NOT NULL, -- 'receita' or 'despesa'
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Seed default categories if table is empty (per user) - Logic handled in API or Layout check?
// Ideally we seed when user is created, but here we can just ensure table exists.

} catch (Exception $e) {
// Ignore errors if columns exist or permissions fail
}
*/
    // ----------------------------------

} catch (\PDOException $e) {
    echo "Erro de conexão com Neon PostgreSQL: " . $e->getMessage();
    exit;
}
?>