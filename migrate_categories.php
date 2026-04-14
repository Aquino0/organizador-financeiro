<?php
require_once __DIR__ . '/src/db.php';

try {
    // Add 'icone' column if not exists
    $pdo->exec("
        DO $$ 
        BEGIN 
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='categories' AND column_name='icone') THEN
                ALTER TABLE categories ADD COLUMN icone VARCHAR(100);
            END IF;
            IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='categories' AND column_name='parent_id') THEN
                ALTER TABLE categories ADD COLUMN parent_id INTEGER REFERENCES categories(id) ON DELETE CASCADE;
            END IF;
        END $$;
    ");
    echo "Tabela 'categories' atualizada com sucesso!";
} catch (Exception $e) {
    echo "Erro na migração: " . $e->getMessage();
}
?>
