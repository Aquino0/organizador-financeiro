<?php
// api/cron/daily_notifications.php

// This script is intended to be run by Vercel Cron once a day.
// It checks for bills expiring TODAY or TOMORROW and sends a consolidated email.

require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/mailer.php';
require_once __DIR__ . '/../../src/templates/email_base.php';

// Security: Verify CRON_SECRET if desired, but for now rely on obscurity or Vercel protection
// if ($_GET['key'] !== getenv('CRON_SECRET')) die('Unauthorized');

header('Content-Type: application/json');

try {
    // 1. Find unpaid expenses due today or tomorrow
    // We group by user_id to send only one email per person
    $sql = "
        SELECT 
            u.id as user_id, 
            u.nome as user_name, 
            u.email as user_email,
            d.descricao,
            d.valor,
            d.data as vencimento
        FROM despesas d
        JOIN users u ON d.user_id = u.id
        WHERE d.pago = FALSE 
        AND d.data BETWEEN CURRENT_DATE AND (CURRENT_DATE + INTERVAL '1 day')
        ORDER BY d.user_id, d.data ASC
    ";

    $stmt = $pdo->query($sql);
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($bills)) {
        echo json_encode(['status' => 'success', 'message' => 'Nenhuma conta para notificar hoje.']);
        exit;
    }

    // 2. Group bills by user
    $notifications = [];
    foreach ($bills as $bill) {
        $userId = $bill['user_id'];
        if (!isset($notifications[$userId])) {
            $notifications[$userId] = [
                'name' => $bill['user_name'],
                'email' => $bill['user_email'],
                'items' => []
            ];
        }
        $notifications[$userId]['items'][] = $bill;
    }

    // 3. Send Emails
    $sentCount = 0;
    $log = [];

    foreach ($notifications as $userId => $data) {
        $userName = explode(' ', $data['name'])[0]; // First name
        $userEmail = $data['email'];

        // Build the list of bills
        $listHtml = '<ul style="padding-left: 20px; color: #334155;">';
        foreach ($data['items'] as $item) {
            $date = date('d/m', strtotime($item['vencimento']));
            $val = number_format($item['valor'], 2, ',', '.');
            $status = ($item['vencimento'] == date('Y-m-d')) ? '<span style="color:#ef4444;font-weight:bold;">(Vence Hoje!)</span>' : '(Vence Amanhã)';

            $listHtml .= "<li style='margin-bottom: 8px;'><strong>{$item['descricao']}</strong> - R$ {$val} {$status}</li>";
        }
        $listHtml .= '</ul>';

        $body = "
            <p>Você possui contas prestes a vencer:</p>
            $listHtml
            <p>Acesse o sistema para dar baixa ou visualizar detalhes.</p>
        ";

        // Render Template
        $finalHtml = renderEmailTemplate($userName, $body, 'https://organizador-financeiro.vercel.app', 'Ver Contas');

        // Send
        if (sendEmail($userEmail, "Lembrete: Contas a Vencer!", $finalHtml)) {
            $sentCount++;
            $log[] = "Sent to $userEmail";
        } else {
            $log[] = "Failed to send to $userEmail";
        }
    }

    echo json_encode(['status' => 'success', 'sent' => $sentCount, 'details' => $log]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>