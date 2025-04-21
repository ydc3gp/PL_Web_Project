<?php
require_once '../includes/session.php';
header('Content-Type: application/json');

// Return user data if logged in
if (Session::isLoggedIn()) {
    echo json_encode([
        'success' => true,
        'user' => [
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email'),
            'id' => Session::get('user_id')
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
}