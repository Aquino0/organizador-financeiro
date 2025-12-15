<?php
// src/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAuth()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

function requireAdmin()
{
    requireAuth();
    if ($_SESSION['role'] !== 'admin') {
        echo "Acesso negado.";
        exit;
    }
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}
?>