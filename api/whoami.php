<?php
require '../src/auth.php';
session_start();
header('Content-Type: application/json');
echo json_encode([
    'session_user_id' => $_SESSION['user_id'] ?? null,
    'helper_id' => getCurrentUserId()
]);
