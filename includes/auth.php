<?php
// Authentication Functions
if (!defined('CONFIG_LOADED')) {
    $config_path = __DIR__ . '/../config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    } elseif (file_exists(__DIR__ . '/config.php')) {
        require_once __DIR__ . '/config.php';
    } elseif (file_exists('config.php')) {
        require_once 'config.php';
    }
}

class AuthManager {
    
    public static function login($username, $password) {
        $users = self::load_users();
        
        if (!isset($users[$username])) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
        
        $user = $users[$username];
        
        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $unlock_time = date('H:i:s', strtotime($user['locked_until']));
            return ['success' => false, 'message' => "Account locked until {$unlock_time}"];
        }
        
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Reset login attempts on successful login
            $users[$username]['login_attempts'] = 0;
            $users[$username]['locked_until'] = null;
            $users[$username]['last_login'] = date('Y-m-d H:i:s');
            
            self::save_users($users);
            
            // Set session variables
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            
            return ['success' => true, 'message' => 'Login successful'];
        } else {
            // Increment login attempts
            $users[$username]['login_attempts']++;
            
            // Lock account if too many attempts
            if ($users[$username]['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
                $users[$username]['locked_until'] = date('Y-m-d H:i:s', time() + LOGIN_LOCKOUT_TIME);
            }
            
            self::save_users($users);
            
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
    
    public static function logout() {
        session_destroy();
    }
    
    public static function is_authenticated() {
        return isset($_SESSION['authenticated']) && 
               $_SESSION['authenticated'] === true &&
               isset($_SESSION['username']) &&
               isset($_SESSION['login_time']) &&
               (time() - $_SESSION['login_time']) < SESSION_TIMEOUT;
    }
    
    public static function require_auth() {
        if (!self::is_authenticated()) {
            header('Location: login.php');
            exit();
        }
    }
    
    public static function get_user() {
        if (self::is_authenticated()) {
            return [
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role'] ?? 'user',
                'login_time' => $_SESSION['login_time']
            ];
        }
        return null;
    }
    
    private static function load_users() {
        if (file_exists(USERS_FILE)) {
            $json = file_get_contents(USERS_FILE);
            return json_decode($json, true) ?: [];
        }
        return [];
    }
    
    private static function save_users($users) {
        file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
    }
}

// Data Manager Class
class DataManager {
    
    public static function get_metrics() {
        if (file_exists(METRICS_FILE)) {
            $json = file_get_contents(METRICS_FILE);
            return json_decode($json, true) ?: [];
        }
        return [];
    }
    
    public static function save_metric($key, $value, $percentage = null, $trend = null) {
        $metrics = self::get_metrics();
        
        if (!isset($metrics[$key])) {
            $metrics[$key] = [];
        }
        
        $metrics[$key]['value'] = floatval($value);
        if ($percentage !== null) {
            $metrics[$key]['percentage'] = floatval($percentage);
        }
        if ($trend !== null) {
            $metrics[$key]['trend'] = $trend;
        }
        
        return file_put_contents(METRICS_FILE, json_encode($metrics, JSON_PRETTY_PRINT)) !== false;
    }
    
    public static function get_accounts() {
        if (file_exists(ACCOUNTS_FILE)) {
            $json = file_get_contents(ACCOUNTS_FILE);
            return json_decode($json, true) ?: [];
        }
        return [];
    }
    
    public static function save_accounts($accounts) {
        return file_put_contents(ACCOUNTS_FILE, json_encode($accounts, JSON_PRETTY_PRINT)) !== false;
    }
    
    public static function add_account($account_data) {
        $accounts = self::get_accounts();
        
        // Generate new ID
        $max_id = 0;
        foreach ($accounts as $account) {
            if ($account['id'] > $max_id) {
                $max_id = $account['id'];
            }
        }
        
        $account_data['id'] = $max_id + 1;
        $account_data['last_trade'] = date('Y-m-d H:i:s');
        
        $accounts[] = $account_data;
        
        return self::save_accounts($accounts);
    }
    
    public static function update_account($id, $account_data) {
        $accounts = self::get_accounts();
        
        foreach ($accounts as &$account) {
            if ($account['id'] == $id) {
                $account = array_merge($account, $account_data);
                return self::save_accounts($accounts);
            }
        }
        
        return false;
    }
    
    public static function delete_account($id) {
        $accounts = self::get_accounts();
        
        $accounts = array_filter($accounts, function($account) use ($id) {
            return $account['id'] != $id;
        });
        
        return self::save_accounts(array_values($accounts));
    }
}

?>