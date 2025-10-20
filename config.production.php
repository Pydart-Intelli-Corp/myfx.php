<?php
// MyFX Trading Portal - Production Configuration
// Version: 1.0.0
// Author: MyFX Team

// Prevent duplicate includes
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_NAME', 'MyFX Trading Portal');
define('SITE_VERSION', '1.0.0');
define('BASE_URL', isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) : 'https://yourdomain.com');

// Production mode (IMPORTANT: Set to false in production)
define('DEVELOPMENT', false);

// Database Configuration - Update these for your production server
define('DB_HOST', 'localhost');                    // Your database host
define('DB_PORT', '3306');                         // MySQL port
define('DB_NAME', 'myfx_trading');                 // Your database name
define('DB_USER', 'myfx_user');                    // Your database username
define('DB_PASS', 'YOUR_SECURE_PASSWORD_HERE');    // Your database password
define('DB_CHARSET', 'utf8mb4');

// Authentication Configuration
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'CHANGE_THIS_PASSWORD');   // CHANGE THIS!
define('SESSION_TIMEOUT', 14400); // 4 hours in seconds

// File Paths
define('DATA_DIR', __DIR__ . '/data/');
define('INCLUDES_DIR', __DIR__ . '/includes/');
define('ASSETS_DIR', __DIR__ . '/assets/');
define('MIGRATIONS_DIR', __DIR__ . '/database/migrations/');

// Data Files
define('METRICS_FILE', DATA_DIR . 'metrics.json');
define('ACCOUNTS_FILE', DATA_DIR . 'accounts.json');
define('USERS_FILE', DATA_DIR . 'users.json');

// Security Settings
define('CSRF_TOKEN_LENGTH', 32);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Production Security Settings
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Session Security
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);  // Only if using HTTPS
ini_set('session.use_strict_mode', 1);

// Default Trading Metrics
$default_metrics = [
    'book_a_deposit' => [
        'value' => 150000,
        'change' => 12.5,
        'trend' => 'up'
    ],
    'book_a_withdraw' => [
        'value' => 60000,
        'change' => -3.2,
        'trend' => 'down'
    ],
    'book_a_trade_volume' => [
        'value' => 890000,
        'change' => 22.1,
        'trend' => 'up'
    ],
    'book_a_profit_loss' => [
        'value' => 78500,
        'change' => 15.3,
        'trend' => 'up'
    ],
    'book_b_deposit' => [
        'value' => 95000,
        'change' => 8.7,
        'trend' => 'up'
    ],
    'book_b_withdraw' => [
        'value' => 32000,
        'change' => -15.1,
        'trend' => 'down'
    ],
    'book_b_trade_volume' => [
        'value' => 650000,
        'change' => 18.9,
        'trend' => 'up'
    ],
    'book_b_profit_loss' => [
        'value' => 52300,
        'change' => 9.6,
        'trend' => 'up'
    ],
    'external_deposit' => [
        'value' => 1800000,
        'change' => 25.4,
        'trend' => 'up'
    ],
    'external_withdraw' => [
        'value' => 98000,
        'change' => -12.8,
        'trend' => 'down'
    ],
    'external_supply' => [
        'value' => 2200000,
        'change' => 18.7,
        'trend' => 'up'
    ],
    'external_profit_loss' => [
        'value' => 185000,
        'change' => 14.2,
        'trend' => 'up'
    ]
];

// Default Live Accounts
$default_accounts = [
    [
        'id' => 1001,
        'username' => 'trader_001',
        'balance' => 45230.50,
        'equity' => 47120.30,
        'margin' => 1200.00,
        'status' => 'active',
        'last_activity' => '2 min ago'
    ],
    [
        'id' => 1002,
        'username' => 'trader_002',
        'balance' => 78940.25,
        'equity' => 82150.75,
        'margin' => 2500.00,
        'status' => 'active',
        'last_activity' => '5 min ago'
    ],
    [
        'id' => 1003,
        'username' => 'trader_003',
        'balance' => 23780.00,
        'equity' => 24890.50,
        'margin' => 800.00,
        'status' => 'inactive',
        'last_activity' => '1 hour ago'
    ],
    [
        'id' => 1004,
        'username' => 'trader_004',
        'balance' => 156420.75,
        'equity' => 159870.25,
        'margin' => 5200.00,
        'status' => 'active',
        'last_activity' => '10 min ago'
    ],
    [
        'id' => 1005,
        'username' => 'trader_005',
        'balance' => 89560.00,
        'equity' => 87230.50,
        'margin' => 3200.00,
        'status' => 'margin_call',
        'last_activity' => '30 min ago'
    ]
];

// Initialize data files if they don't exist
function initialize_data_files() {
    global $default_metrics, $default_accounts;
    
    // Create data directory if it doesn't exist
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    // Create logs directory
    if (!file_exists(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    // Initialize metrics file
    if (!file_exists(METRICS_FILE)) {
        file_put_contents(METRICS_FILE, json_encode($default_metrics, JSON_PRETTY_PRINT));
    }
    
    // Initialize accounts file
    if (!file_exists(ACCOUNTS_FILE)) {
        file_put_contents(ACCOUNTS_FILE, json_encode($default_accounts, JSON_PRETTY_PRINT));
    }
    
    // Initialize users file with secure passwords
    if (!file_exists(USERS_FILE)) {
        $users = [
            [
                'id' => 1,
                'username' => 'admin',
                'password' => password_hash('Access@myfx', PASSWORD_DEFAULT),
                'email' => 'admin@yourdomain.com',
                'role' => 'admin',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'last_login' => null,
                'login_attempts' => 0,
                'locked_until' => null
            ],
            [
                'id' => 2,
                'username' => 'superadmin',
                'password' => password_hash('Access@myfx', PASSWORD_DEFAULT),
                'email' => 'superadmin@yourdomain.com',
                'role' => 'superadmin',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'last_login' => null,
                'login_attempts' => 0,
                'locked_until' => null
            ]
        ];
        file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
    }
}

// Generate CSRF Token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF Token
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check if user is authenticated
function is_authenticated() {
    return isset($_SESSION['authenticated']) && 
           $_SESSION['authenticated'] === true &&
           isset($_SESSION['username']) &&
           isset($_SESSION['login_time']) &&
           (time() - $_SESSION['login_time']) < SESSION_TIMEOUT;
}

// Require authentication
function require_auth() {
    if (!is_authenticated()) {
        header('Location: login.php');
        exit();
    }
}

// Log out user
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Format currency
function format_currency($amount) {
    if (is_string($amount) && strpos($amount, '$') === 0) {
        return $amount;
    }
    if (is_string($amount)) {
        $amount = floatval(str_replace(['$', ','], '', $amount));
    }
    return '$' . number_format($amount, 0);
}

// Format percentage
function format_percentage($percentage) {
    return number_format($percentage, 1) . '%';
}

// Get trend icon
function get_trend_icon($trend) {
    return $trend === 'up' ? '↗️' : ($trend === 'down' ? '↘️' : '➡️');
}

// Get trend color
function get_trend_color($trend) {
    return $trend === 'up' ? '#10b981' : ($trend === 'down' ? '#ef4444' : '#6b7280');
}

// Load data from JSON file
function load_data($filename) {
    $filepath = DATA_DIR . $filename;
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        return json_decode($content, true);
    }
    return null;
}

// Save data to JSON file
function save_data($filename, $data) {
    $filepath = DATA_DIR . $filename;
    return file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
}

// Security function to sanitize output
function sanitize_output($data) {
    if (is_array($data)) {
        return array_map('sanitize_output', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Log security events
function log_security_event($event, $details = '') {
    $log_entry = date('Y-m-d H:i:s') . " - " . $_SERVER['REMOTE_ADDR'] . " - $event - $details" . PHP_EOL;
    file_put_contents(__DIR__ . '/logs/security.log', $log_entry, FILE_APPEND | LOCK_EX);
}

// Rate limiting function
function check_rate_limit($identifier, $max_attempts = 10, $time_window = 300) {
    $rate_limit_file = DATA_DIR . 'rate_limits.json';
    $rate_limits = [];
    
    if (file_exists($rate_limit_file)) {
        $rate_limits = json_decode(file_get_contents($rate_limit_file), true) ?: [];
    }
    
    $current_time = time();
    $key = md5($identifier);
    
    // Clean old entries
    foreach ($rate_limits as $k => $v) {
        if ($current_time - $v['first_attempt'] > $time_window) {
            unset($rate_limits[$k]);
        }
    }
    
    if (!isset($rate_limits[$key])) {
        $rate_limits[$key] = ['count' => 1, 'first_attempt' => $current_time];
    } else {
        $rate_limits[$key]['count']++;
    }
    
    file_put_contents($rate_limit_file, json_encode($rate_limits));
    
    return $rate_limits[$key]['count'] <= $max_attempts;
}

// Initialize data files
initialize_data_files();

?>