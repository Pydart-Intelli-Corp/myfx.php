<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../includes/auth.php';

// Check authentication
if (!AuthManager::is_authenticated()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Check if key is provided
$key = $_GET['key'] ?? '';
if (empty($key)) {
    echo json_encode(['success' => false, 'message' => 'Metric key required']);
    exit();
}

// Get metric data
$metrics = DataManager::get_metrics();

if (!isset($metrics[$key])) {
    echo json_encode(['success' => false, 'message' => 'Metric not found']);
    exit();
}

echo json_encode([
    'success' => true,
    'metric' => $metrics[$key]
]);
?>