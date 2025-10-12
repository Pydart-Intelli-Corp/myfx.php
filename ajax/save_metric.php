<?php
require_once '../config.php';
require_once '../includes/auth.php';

// Check authentication
if (!AuthManager::is_authenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$key = $_POST['key'] ?? '';
$value = $_POST['value'] ?? '';

if (empty($key) || empty($value)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit();
}

// Load current metrics or use defaults
$default_metrics = [
    'tradingVolume' => ['value' => '$2,847,392', 'change' => 12.4, 'trend' => 'up'],
    'totalCapital' => ['value' => '$18,924,581', 'change' => 8.7, 'trend' => 'up'],
    'commission' => ['value' => '$84,573', 'change' => -2.3, 'trend' => 'down']
];

$metrics_file = '../data/trading-metrics.json';
if (file_exists($metrics_file)) {
    $stored_metrics = json_decode(file_get_contents($metrics_file), true);
    $metrics = $stored_metrics ?: $default_metrics;
} else {
    $metrics = $default_metrics;
}

// Validate metric key
if (!isset($metrics[$key])) {
    echo json_encode(['success' => false, 'message' => 'Invalid metric key']);
    exit();
}

// Calculate change percentage
$previousValue = floatval(str_replace(['$', ','], '', $metrics[$key]['value']));
$newValue = floatval(str_replace(['$', ','], '', $value));

if ($previousValue === 0) {
    $change = 0;
} else {
    $change = (($newValue - $previousValue) / $previousValue) * 100;
}

$trend = $change >= 0 ? 'up' : 'down';

// Update the metric
$metrics[$key] = [
    'value' => $value,
    'change' => round($change, 1),
    'trend' => $trend
];

// Ensure data directory exists
if (!file_exists('../data')) {
    mkdir('../data', 0755, true);
}

// Save metrics
if (file_put_contents($metrics_file, json_encode($metrics, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true, 
        'message' => 'Metric updated successfully',
        'data' => $metrics[$key]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save metric']);
}
?>