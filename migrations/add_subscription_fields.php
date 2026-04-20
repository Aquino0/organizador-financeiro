<?php
require_once __DIR__ . '/../src/db.php';

try {
    $pdo->exec("
        ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS stripe_customer_id VARCHAR(255),
        ADD COLUMN IF NOT EXISTS stripe_subscription_id VARCHAR(255),
        ADD COLUMN IF NOT EXISTS plan_type VARCHAR(50) DEFAULT 'free',
        ADD COLUMN IF NOT EXISTS subscription_status VARCHAR(50) DEFAULT 'free',
        ADD COLUMN IF NOT EXISTS current_period_end TIMESTAMP;
    ");

    echo "Migration successful! Columns added to users table.\n";
} catch (\PDOException $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
}
