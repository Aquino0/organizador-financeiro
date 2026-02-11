<?php
// api/subscriptions.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$user_id = getCurrentUserId();
$method = $_SERVER['REQUEST_METHOD'];

try {
    // $pdo available from src/db.php

    // Auto-fix: Criar tabela se não existir
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subscriptions (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            service_name VARCHAR(255) NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            billing_cycle VARCHAR(20) DEFAULT 'monthly', -- 'monthly', 'yearly'
            next_billing_date DATE NOT NULL,
            category VARCHAR(50) DEFAULT 'other',
            icon VARCHAR(50) DEFAULT '📦',
            status VARCHAR(20) DEFAULT 'active',
            website_url VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
    ");

    // Ensure icon column exists (migration for existing table)
    try {
        $pdo->exec("ALTER TABLE subscriptions ADD COLUMN IF NOT EXISTS icon VARCHAR(50) DEFAULT '📦'");
    } catch (Exception $e) { /* ignore if exists */
    }

    if ($method === 'GET') {
        $stmt = $pdo->prepare("
            SELECT * FROM subscriptions 
            WHERE user_id = :user_id 
            ORDER BY next_billing_date ASC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $subs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate totals
        $monthlyTotal = 0;
        $yearlyTotal = 0;

        foreach ($subs as $sub) {
            if ($sub['status'] !== 'active')
                continue;

            $amount = floatval($sub['amount']);
            if ($sub['billing_cycle'] === 'monthly') {
                $monthlyTotal += $amount;
                $yearlyTotal += $amount * 12;
            } else if ($sub['billing_cycle'] === 'yearly') {
                $monthlyTotal += $amount / 12;
                $yearlyTotal += $amount;
            }
        }

        echo json_encode([
            'subscriptions' => $subs,
            'stats' => [
                'monthly' => round($monthlyTotal, 2),
                'yearly' => round($yearlyTotal, 2)
            ]
        ]);
    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // CREATE or UPDATE
        if (isset($data['id']) && !empty($data['id'])) {
            // Update details or renew
            if (isset($data['action']) && $data['action'] === 'renew') {
                // Logic to advance date
                // Get current
                $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE id = :id AND user_id = :user_id");
                $stmt->execute(['id' => $data['id'], 'user_id' => $user_id]);
                $sub = $stmt->fetch();

                if ($sub) {
                    $currentDate = new DateTime($sub['next_billing_date']);
                    if ($sub['billing_cycle'] === 'monthly') {
                        $currentDate->modify('+1 month');
                    } else if ($sub['billing_cycle'] === 'yearly') {
                        $currentDate->modify('+1 year');
                    }

                    $stmtUpdate = $pdo->prepare("UPDATE subscriptions SET next_billing_date = :date WHERE id = :id");
                    $stmtUpdate->execute(['date' => $currentDate->format('Y-m-d'), 'id' => $data['id']]);
                    echo json_encode(['success' => true, 'message' => 'Renovado com sucesso!']);
                }
            } else {
                // Normal Update
                $stmt = $pdo->prepare("
                    UPDATE subscriptions 
                    SET service_name = :name, amount = :amount, billing_cycle = :cycle, 
                        next_billing_date = :date, category = :cat, icon = :icon, website_url = :url
                    WHERE id = :id AND user_id = :user_id
                ");
                $stmt->execute([
                    'name' => $data['service_name'],
                    'amount' => $data['amount'],
                    'cycle' => $data['billing_cycle'],
                    'date' => $data['next_billing_date'],
                    'cat' => $data['category'] ?? 'other',
                    'icon' => $data['icon'] ?? '📦',
                    'url' => $data['website_url'] ?? null,
                    'id' => $data['id'],
                    'user_id' => $user_id
                ]);
                echo json_encode(['success' => true, 'message' => 'Atualizada!']);
            }
        } else {
            // INSERT
            $stmt = $pdo->prepare("
                INSERT INTO subscriptions (user_id, service_name, amount, billing_cycle, next_billing_date, category, icon, website_url)
                VALUES (:user_id, :name, :amount, :cycle, :date, :cat, :icon, :url)
            ");
            $stmt->execute([
                'user_id' => $user_id,
                'name' => $data['service_name'],
                'amount' => $data['amount'],
                'cycle' => $data['billing_cycle'],
                'date' => $data['next_billing_date'],
                'cat' => $data['category'] ?? 'other',
                'icon' => $data['icon'] ?? '📦',
                'url' => $data['website_url'] ?? null
            ]);
            echo json_encode(['success' => true, 'message' => 'Criada!']);
        }
    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID missing']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $data['id'], 'user_id' => $user_id]);
        echo json_encode(['success' => true, 'message' => 'Removido!']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro Banco: ' . $e->getMessage()]);
}
?>