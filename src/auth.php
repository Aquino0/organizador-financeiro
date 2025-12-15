<?php
// src/auth.php

// Vercel Serverless Stateless Auth
// Standard PHP Sessions via /tmp do not persist across different Lambda instances.
// We use a signed cookie to maintain state.

define('AUTH_COOKIE_NAME', 'auth_token');
define('AUTH_SECRET', getenv('AUTH_SECRET') ?: 'default_insecure_secret_change_me');

function loginUser($id, $nome, $role)
{
    $payload = base64_encode($id . '|' . $role . '|' . $nome);
    $signature = hash_hmac('sha256', $payload, AUTH_SECRET);
    $token = $payload . '.' . $signature;

    // Cookie valid for 30 days, HttpOnly, Secure if https
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    setcookie(AUTH_COOKIE_NAME, $token, time() + (86400 * 30), '/', '', $isSecure, true);

    // Fallback for legacy code
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $nome;
    $_SESSION['role'] = $role;
}

function logoutUser()
{
    setcookie(AUTH_COOKIE_NAME, '', time() - 3600, '/');
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    session_destroy();
}

function getAuthUser()
{
    if (!isset($_COOKIE[AUTH_COOKIE_NAME]))
        return null;

    $parts = explode('.', $_COOKIE[AUTH_COOKIE_NAME]);
    if (count($parts) !== 2)
        return null;

    list($payload, $signature) = $parts;

    $validSignature = hash_hmac('sha256', $payload, AUTH_SECRET);
    if (!hash_equals($validSignature, $signature))
        return null;

    $data = explode('|', base64_decode($payload));
    if (count($data) < 3)
        return null;

    return [
        'id' => $data[0],
        'role' => $data[1],
        'nome' => $data[2]
    ];
}

function requireAuth()
{
    $user = getAuthUser();
    if (!$user) {
        header('Location: /index.php');
        exit;
    }
    // Populate session for legacy compatibility if needed
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['role'] = $user['role'];
    }
}

function requireAdmin()
{
    $user = getAuthUser();
    if (!$user || $user['role'] !== 'admin') {
        header('Location: /index.php'); // Or access denied
        exit;
    }
}

function isLoggedIn()
{
    return getAuthUser() !== null;
}

function getCurrentUserId()
{
    $user = getAuthUser();
    return $user ? $user['id'] : null;
}
?>