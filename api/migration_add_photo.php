<?php
// api/migration_add_photo.php
require_once __DIR__ . '/../src/db.php';

try {
    $pdo->exec("
        ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS foto_perfil VARCHAR(255) DEFAULT NULL;
    ");
    echo "Migração executada com sucesso: Coluna 'foto_perfil' adicionada (se não existia).\n";
} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
?>