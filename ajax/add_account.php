<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../includes/auth.php';

// Check authentication
if (!AuthManager::is_authenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Only superadmin can add accounts
$currentUser = AuthManager::get_user();
if (!$currentUser || ($currentUser['role'] ?? '') !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: insufficient permissions']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    // Get form data
    $username = trim($_POST['username'] ?? '');
    $balance = floatval($_POST['balance'] ?? 0);
    $equity = floatval($_POST['equity'] ?? 0);
    $margin = floatval($_POST['margin'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    // Validate required fields
    if (empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Username is required']);
        exit();
    }

    if ($balance < 0 || $equity < 0 || $margin < 0) {
        echo json_encode(['success' => false, 'message' => 'Financial values cannot be negative']);
        exit();
    }

    if (!in_array($status, ['active', 'inactive', 'suspended', 'margin_call'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit();
    }

    // Get existing accounts to check for duplicates and generate new ID
    $accounts = DataManager::get_accounts();
    
    // Check if username already exists
    foreach ($accounts as $account) {
        if ($account['username'] === $username) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            exit();
        }
    }

    // Generate new ID (get highest ID + 1)
    $maxId = 0;
    foreach ($accounts as $account) {
        if (isset($account['id']) && $account['id'] > $maxId) {
            $maxId = $account['id'];
        }
    }
    $newId = $maxId + 1;

    // Create new account data
    $newAccount = [
        'id' => $newId,
        'username' => $username,
        'balance' => $balance,
        'equity' => $equity,
        'margin' => $margin,
        'status' => $status,
        'last_activity' => date('Y-m-d H:i:s')
    ];

    // Add account using DataManager
    $success = DataManager::add_account($newAccount);

    if ($success) {
        echo json_encode([
            'success' => true, 
            'message' => 'Account created successfully',
            'account_id' => $newId,
            'data' => $newAccount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create account']);
    }

} catch (Exception $e) {
    error_log("General error in add_account.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while adding the account: ' . $e->getMessage()]);
}
?>