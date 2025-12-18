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
    if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'json') !== false || strpos($_SERVER['CONTENT_TYPE'] ?? '', 'json') !== false) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Configuração ausente: DATABASE_URL não definida no Vercel.']);
        exit;
    }
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
} catch (\PDOException $e) {
    if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'json') !== false || strpos($_SERVER['CONTENT_TYPE'] ?? '', 'json') !== false) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Erro conexao Banco: ' . $e->getMessage()]);
        exit;
    }
    echo "Erro de conexão com Neon PostgreSQL: " . $e->getMessage();
    exit;
}