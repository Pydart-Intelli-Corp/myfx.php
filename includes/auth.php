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
    
    private static function getUsersData() {
        $users_file = __DIR__ . '/../data/users.json';
        if (file_exists($users_file)) {
            return json_decode(file_get_contents($users_file), true) ?: [];
        }
        return [];
    }
    
    private static function saveUsersData($users) {
        $users_file = __DIR__ . '/../data/users.json';
        return file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
    }
    
    public static function login($username, $password) {
        $users = self::getUsersData();
        $user = null;
        
        foreach ($users as $u) {
            if ($u['username'] === $username) {
                $user = $u;
                break;
            }
        }
        
        if (!$user || !$user['is_active']) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
        
        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $unlock_time = date('H:i:s', strtotime($user['locked_until']));
            return ['success' => false, 'message' => "Account locked until {$unlock_time}"];
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Update user data - reset login attempts and update last login
            foreach ($users as &$u) {
                if ($u['username'] === $username) {
                    $u['last_login'] = date('Y-m-d H:i:s');
                    $u['login_attempts'] = 0;
                    $u['locked_until'] = null;
                    break;
                }
            }
            self::saveUsersData($users);
            
            // Set session variables
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            
            return ['success' => true, 'message' => 'Login successful'];
        } else {
            // Increment login attempts
            foreach ($users as &$u) {
                if ($u['username'] === $username) {
                    $u['login_attempts'] = ($u['login_attempts'] ?? 0) + 1;
                    
                    // Lock account if too many attempts
                    if ($u['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
                        $u['locked_until'] = date('Y-m-d H:i:s', time() + LOGIN_LOCKOUT_TIME);
                    }
                    break;
                }
            }
            self::saveUsersData($users);
            
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
                'role' => $_SESSION['role'] ?? 'administrator',
                'login_time' => $_SESSION['login_time']
            ];
        }
        return null;
    }
}

// Data Manager Class (File-based system)
class DataManager {
    
    public static function get_metrics() {
        $metrics_file = __DIR__ . '/../data/trading-metrics.json';
        if (file_exists($metrics_file)) {
            return json_decode(file_get_contents($metrics_file), true) ?: [];
        }
        return [];
    }
    
    public static function save_metric($key, $value, $change = null, $trend = null, $updatedBy = null) {
        $metrics = self::get_metrics();
        
        if ($change === null || $trend === null) {
            // Calculate change and trend if not provided
            if (isset($metrics[$key])) {
                $previousValue = floatval(str_replace(['$', ','], '', $metrics[$key]['value']));
                $newValue = floatval(str_replace(['$', ','], '', $value));
                
                if ($previousValue === 0) {
                    $change = 0;
                } else {
                    $change = round((($newValue - $previousValue) / $previousValue) * 100, 2);
                }
                
                if ($change > 0) $trend = 'up';
                elseif ($change < 0) $trend = 'down';
                else $trend = 'neutral';
            } else {
                $change = 0;
                $trend = 'up';
            }
        }
        
        $metrics[$key] = [
            'value' => $value,
            'change' => $change,
            'trend' => $trend
        ];
        
        $metrics_file = __DIR__ . '/../data/trading-metrics.json';
        return file_put_contents($metrics_file, json_encode($metrics, JSON_PRETTY_PRINT));
    }
    
    public static function get_accounts() {
        $accounts_file = __DIR__ . '/../data/accounts.json';
        if (file_exists($accounts_file)) {
            return json_decode(file_get_contents($accounts_file), true) ?: [];
        }
        return [];
    }
    
    public static function save_accounts($accounts) {
        $accounts_file = __DIR__ . '/../data/accounts.json';
        return file_put_contents($accounts_file, json_encode($accounts, JSON_PRETTY_PRINT));
    }
    
    public static function add_account($account_data) {
        $accounts = self::get_accounts();
        $accounts[] = $account_data;
        return self::save_accounts($accounts);
    }
    
    public static function update_account($id, $account_data) {
        $accounts = self::get_accounts();
        foreach ($accounts as &$account) {
            if ($account['id'] == $id) {
                foreach ($account_data as $key => $value) {
                    $account[$key] = $value;
                }
                break;
            }
        }
        return self::save_accounts($accounts);
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