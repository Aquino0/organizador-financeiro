<?php
// src/mailer.php

// This file handles sending emails using the Resend API.
// It relies on the generic PHP curl functions available in Vercel.

function sendEmail($to, $subject, $htmlContent)
{
    $apiKey = getenv('RESEND_API_KEY');

    if (!$apiKey) {
        error_log("RESEND_API_KEY missing. Cannot send email to $to");
        return false;
    }

    $url = 'https://api.resend.com/emails';

    // Construct the payload
    // You should verify a domain in Resend (e.g., 'updates@yourdomain.com') 
    // OR use 'onboarding@resend.dev' for testing (only works if you send to your own email).
    $fromEmail = getenv('EMAIL_FROM') ?: 'onboarding@resend.dev';

    $data = [
        'from' => "Organizador Financeiro <$fromEmail>",
        'to' => [$to],
        'subject' => $subject,
        'html' => $htmlContent
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    } else {
        error_log("Failed to send email via Resend. Code: $httpCode. Response: $response");
        return false;
    }
}
?>