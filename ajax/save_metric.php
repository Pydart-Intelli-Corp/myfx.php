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

// Only superadmin can update metrics
$currentUser = AuthManager::get_user();
if (!$currentUser || ($currentUser['role'] ?? '') !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: insufficient permissions']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$key = $_POST['key'] ?? '';
$value = $_POST['value'] ?? '';

// Debug logging
error_log("save_metric.php called with key: $key, value: $value");

// We'll compute change and trend server-side based on previous stored value
$change = null;
$trend = null;

if (empty($key) || empty($value)) {
    error_log("save_metric.php: Missing parameters - key: $key, value: $value");
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit();
}

// Get current user for audit trail
$user = AuthManager::get_user();
$updatedBy = $user ? $user['username'] : null;

// Validate metric key
$validKeys = [
    'book_a_deposit', 'book_a_withdraw', 'book_a_trade_volume', 'book_a_profit_loss',
    'book_b_deposit', 'book_b_withdraw', 'book_b_trade_volume', 'book_b_profit_loss',
    'external_deposit', 'external_withdraw', 'external_supply', 'external_profit_loss'
];
if (!in_array($key, $validKeys)) {
    echo json_encode(['success' => false, 'message' => 'Invalid metric key']);
    exit();
}

// Update the metric using DataManager
$metrics = DataManager::get_metrics();

// sanitize incoming value (allow numbers, decimal, negative)
$raw = (string)$value;
$newValue = 0.0;
if (is_numeric($raw)) {
    $newValue = (float)$raw;
} else {
    // remove any currency symbols and commas
    $newValue = (float)preg_replace('/[^0-9.\-]/', '', $raw);
}

$previousValue = 0.0;
if (isset($metrics[$key]) && isset($metrics[$key]['value'])) {
    $previousValue = (float)$metrics[$key]['value'];
}

// Compute percentage change and trend
if ($previousValue == 0.0) {
    if ($newValue == 0.0) {
        $change = 0.0;
        $trend = 'neutral';
    } else {
        // from 0 to something: define as 100% increase for display
        $change = 100.0;
        $trend = $newValue > 0 ? 'up' : 'down';
    }
} else {
    $change = (($newValue - $previousValue) / $previousValue) * 100.0;
    if ($change > 0) $trend = 'up';
    elseif ($change < 0) $trend = 'down';
    else $trend = 'neutral';
}

// Round change to 2 decimal places for storage
$change = round($change, 2);

// Save numeric value (no formatting)
$success = DataManager::save_metric($key, $newValue, $change, $trend, $updatedBy);

error_log("save_metric.php: Save operation result: " . ($success ? 'SUCCESS' : 'FAILED'));

if ($success) {
    // Get the updated metrics to return the specific one
    $metrics = DataManager::get_metrics();
    $updatedMetric = $metrics[$key] ?? null;
    
    error_log("save_metric.php: Updated metric: " . json_encode($updatedMetric));
    
    if ($updatedMetric) {
        // Prepare a nicely formatted response
        $formattedValue = $updatedMetric['value'];
        $formattedChange = isset($updatedMetric['change']) ? round((float)$updatedMetric['change'], 2) : null;
        echo json_encode([
            'success' => true,
            'message' => 'Metric updated successfully',
            'data' => [
                'value' => $formattedValue,
                'change' => $formattedChange,
                'trend' => $updatedMetric['trend'] ?? 'neutral'
            ]
        ]);
    } else {
        error_log("save_metric.php: Failed to retrieve updated metric for key: $key");
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve updated metric']);
    }
} else {
    error_log("save_metric.php: Failed to save metric for key: $key");
    echo json_encode(['success' => false, 'message' => 'Failed to save metric']);
}
?>