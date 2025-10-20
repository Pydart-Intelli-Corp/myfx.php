<?php
require_once '../config.php';
require_once '../includes/auth.php';

// Check if user is authenticated
if (AuthManager::is_authenticated()) {
    // Refresh the session login time to prevent timeout
    $_SESSION['login_time'] = time();
    
    echo json_encode([
        'success' => true,
        'message' => 'Session refreshed',
        'timestamp' => time()
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Session expired'
    ]);
}
?>