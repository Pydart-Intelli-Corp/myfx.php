<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../includes/auth.php';

// Check authentication
if (!AuthManager::is_authenticated()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Get accounts data
$accounts = DataManager::get_accounts();

echo json_encode([
    'success' => true,
    'accounts' => $accounts,
    'total_accounts' => count($accounts),
    'total_balance' => array_sum(array_column($accounts, 'balance')),
    'active_accounts' => count(array_filter($accounts, function($a) { 
        return $a['status'] === 'active'; 
    }))
]);
?>