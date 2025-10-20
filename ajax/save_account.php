<?php
// Save Account AJAX Handler
header('Content-Type: application/json');

// Start session and check authentication
session_start();
require_once '../config.php';
require_once '../includes/auth.php';

// Check if user is authenticated
if (!AuthManager::is_authenticated()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Only superadmin can update accounts
$currentUser = AuthManager::get_user();
if (!$currentUser || ($currentUser['role'] ?? '') !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: insufficient permissions']);
    exit();
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if action is update_account
if (!isset($_POST['action']) || $_POST['action'] !== 'update_account') {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit();
}

// Validate required fields
$required_fields = ['account_id', 'username', 'balance', 'equity', 'margin', 'status', 'last_activity'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        echo json_encode(['success' => false, 'message' => 'Missing required field: ' . $field]);
        exit();
    }
}

try {
    // Get form data
    $account_id = intval($_POST['account_id']);
    $username = trim($_POST['username']);
    $balance = floatval($_POST['balance']);
    $equity = floatval($_POST['equity']);
    $margin = floatval($_POST['margin']);
    $status = trim($_POST['status']);
    $last_activity = $_POST['last_activity'];

    // Validate data
    if ($account_id <= 0) {
        throw new Exception('Invalid account ID');
    }

    if (empty($username)) {
        throw new Exception('Username cannot be empty');
    }

    if ($balance < 0 || $equity < 0 || $margin < 0) {
        throw new Exception('Financial values cannot be negative');
    }

    $valid_statuses = ['active', 'inactive', 'margin_call', 'suspended'];
    if (!in_array($status, $valid_statuses)) {
        throw new Exception('Invalid status');
    }

    // Convert datetime-local format to MySQL datetime format
    $last_activity_formatted = date('Y-m-d H:i:s', strtotime($last_activity));

    // Update account using DataManager (file-based)
    $success = DataManager::update_account($account_id, [
        'username' => $username,
        'balance' => $balance,
        'equity' => $equity,
        'margin' => $margin,
        'status' => $status,
        'last_activity' => $last_activity_formatted
    ]);

    if ($success) {
        echo json_encode([
            'success' => true, 
            'message' => 'Account updated successfully',
            'data' => [
                'account_id' => $account_id,
                'username' => $username,
                'balance' => $balance,
                'equity' => $equity,
                'margin' => $margin,
                'status' => $status,
                'last_activity' => $last_activity_formatted
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update account']);
    }

} catch (Exception $e) {
    error_log("Error updating account: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>