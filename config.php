<?php
// Myforexcart Trading Portal - Configuration File
// Version: 1.0.0
// Author: Myforexcart Team

// Prevent duplicate includes
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_NAME', 'MyForexCart Trading Portal');
define('SITE_VERSION', '1.0.0');
define('BASE_URL', isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) : 'https://blueviolet-caterpillar-353032.hostingersite.com');

// Development mode (set to false in production)
define('DEVELOPMENT', false);

// Production Security Settings
if (!DEVELOPMENT) {
    // Disable error display in production
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/data/php_errors.log');
    
    // Session Security
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when HTTPS is enabled
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    
    // Hide PHP version
    header_remove('X-Powered-By');
}

// Database Configuration - External MySQL Server
// Production database server configuration
define('DB_HOST', '45.130.228.117');          // External MySQL host (IP address)
define('DB_PORT', '3306');                    // MySQL port (usually 3306)
define('DB_NAME', 'u509963569_lp_myfx');                    // Database name
define('DB_USER', 'u509963569_lp_myfx_user');               // MySQL username
define('DB_PASS', 'Asd@8750123');          // MySQL password
define('DB_CHARSET', 'utf8mb4');

// Alternative: For local development with XAMPP, use these settings:
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'myfx_trading');
// define('DB_USER', 'root');                    // XAMPP default MySQL username
// define('DB_PASS', '');                        // XAMPP MySQL password (empty by default)
// define('DB_CHARSET', 'utf8mb4');

// Note: Your current IP (111.92.83.235) needs to be whitelisted by your hosting provider
// Contact them to add this IP to the MySQL user's allowed connections

// Authentication Configuration
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Access@myfx2025!'); // Updated for production
define('SESSION_TIMEOUT', 3600); // 1 hour for production security

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
define('MAX_LOGIN_ATTEMPTS', 3); // Reduced for production
define('LOGIN_LOCKOUT_TIME', 1800); // 30 minutes lockout

// Production Rate Limiting
define('RATE_LIMIT_REQUESTS', 60); // Max requests per hour
define('RATE_LIMIT_WINDOW', 3600); // 1 hour window

// Default Trading Metrics
$default_metrics = [
    'trading_volume' => [
        'value' => 2500000,
        'percentage' => 12.5,
        'trend' => 'up'
    ],
    'total_capital' => [
        'value' => 50000000,
        'percentage' => 8.2,
        'trend' => 'up'
    ],
    'commission' => [
        'value' => 125000,
        'percentage' => 15.3,
        'trend' => 'up'
    ]
];

// Default Live Accounts
$default_accounts = [
    [
        'id' => 1001,
        'name' => 'John Smith',
        'balance' => 45230.50,
        'equity' => 47120.30,
        'status' => 'active',
        'last_trade' => '2025-10-12 09:45:00'
    ],
    [
        'id' => 1002,
        'name' => 'Sarah Johnson',
        'balance' => 78940.25,
        'equity' => 82150.75,
        'status' => 'active',
        'last_trade' => '2025-10-12 10:12:00'
    ],
    [
        'id' => 1003,
        'name' => 'Michael Brown',
        'balance' => 23780.00,
        'equity' => 24890.50,
        'status' => 'active',
        'last_trade' => '2025-10-12 08:30:00'
    ],
    [
        'id' => 1004,
        'name' => 'Emma Davis',
        'balance' => 156420.75,
        'equity' => 159870.25,
        'status' => 'active',
        'last_trade' => '2025-10-12 11:05:00'
    ],
    [
        'id' => 1005,
        'name' => 'Robert Wilson',
        'balance' => 89560.00,
        'equity' => 87230.50,
        'status' => 'margin_call',
        'last_trade' => '2025-10-12 07:15:00'
    ]
];

// Initialize data files if they don't exist
function initialize_data_files() {
    global $default_metrics, $default_accounts;
    
    // Create data directory if it doesn't exist
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    // Initialize metrics file
    if (!file_exists(METRICS_FILE)) {
        file_put_contents(METRICS_FILE, json_encode($default_metrics, JSON_PRETTY_PRINT));
    }
    
    // Initialize accounts file
    if (!file_exists(ACCOUNTS_FILE)) {
        file_put_contents(ACCOUNTS_FILE, json_encode($default_accounts, JSON_PRETTY_PRINT));
    }
    
    // Initialize users file
    if (!file_exists(USERS_FILE)) {
        $users = [
            'admin' => [
                'username' => ADMIN_USERNAME,
                'password_hash' => password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT),
                'role' => 'administrator',
                'created' => date('Y-m-d H:i:s'),
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
    // If it's already formatted with $, return as is
    if (is_string($amount) && strpos($amount, '$') === 0) {
        return $amount;
    }
    // Convert to float if it's a string
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

// Initialize data files
initialize_data_files();

?>