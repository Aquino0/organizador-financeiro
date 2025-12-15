<?php
// Smart Router for Vercel
// Vercel sometimes treats root PHP files as static. This script handles the execution.

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/../' . basename($uri);

if ($uri === '/' || $uri === '/index.php' || !file_exists($file)) {
    // Default to Login
    require __DIR__ . '/../index.php';
} else {
    // Handle other pages (dashboard.php, etc)
    // Security check: ensure we only include PHP files from root
    if (str_ends_with($file, '.php') && file_exists($file)) {
        require $file;
    } else {
        // Fallback or 404
        require __DIR__ . '/../index.php';
    }
}
