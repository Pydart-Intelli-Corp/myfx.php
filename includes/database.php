<?php
// Database Manager Class for MySQL operations
class DatabaseManager {
    private static $pdo = null;
    
    // Get PDO connection
    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                $port = defined('DB_PORT') ? DB_PORT : '3306';
                $dsn = "mysql:host=" . DB_HOST . ";port=" . $port . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 30
                ]);
                
                // Test the connection
                self::$pdo->query("SELECT 1");
                
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                // For development, you might want to show the error
                if (defined('DEVELOPMENT') && DEVELOPMENT) {
                    throw new Exception("Database connection failed: " . $e->getMessage());
                } else {
                    throw new Exception("Database connection failed. Please check your database configuration.");
                }
            }
        }
        return self::$pdo;
    }
    
    // User Management Methods
    public static function getUserByUsername($username) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }
    
    public static function updateUserLoginAttempts($username, $attempts, $lockedUntil = null) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ?, locked_until = ? WHERE username = ?");
            return $stmt->execute([$attempts, $lockedUntil, $username]);
        } catch (PDOException $e) {
            error_log("Error updating login attempts: " . $e->getMessage());
            return false;
        }
    }
    
    public static function updateUserLastLogin($username) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW(), login_attempts = 0, locked_until = NULL WHERE username = ?");
            return $stmt->execute([$username]);
        } catch (PDOException $e) {
            error_log("Error updating last login: " . $e->getMessage());
            return false;
        }
    }
    
    // Trading Metrics Methods
    public static function getMetrics() {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT * FROM trading_metrics ORDER BY metric_key");
            $metrics = [];
            while ($row = $stmt->fetch()) {
                $metrics[$row['metric_key']] = [
                    'value' => $row['metric_value'],
                    'change' => $row['change_percentage'],
                    'trend' => $row['trend']
                ];
            }
            return $metrics;
        } catch (PDOException $e) {
            error_log("Error fetching metrics: " . $e->getMessage());
            return [];
        }
    }
    
    public static function updateMetric($key, $value, $change, $trend, $updatedBy = null) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                UPDATE trading_metrics 
                SET metric_value = ?, change_percentage = ?, trend = ?, updated_by = ? 
                WHERE metric_key = ?
            ");
            return $stmt->execute([$value, $change, $trend, $updatedBy, $key]);
        } catch (PDOException $e) {
            error_log("Error updating metric: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getMetricByKey($key) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM trading_metrics WHERE metric_key = ?");
            $stmt->execute([$key]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching metric: " . $e->getMessage());
            return false;
        }
    }
    
    // Live Accounts Methods
    public static function getAllAccounts() {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT * FROM live_accounts ORDER BY id");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching accounts: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getAccountById($id) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM live_accounts WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching account: " . $e->getMessage());
            return false;
        }
    }
    
    public static function updateAccount($id, $data) {
        try {
            $pdo = self::getConnection();
            $fields = [];
            $values = [];
            
            foreach ($data as $field => $value) {
                if (in_array($field, ['username', 'balance', 'equity', 'margin', 'status', 'last_activity'])) {
                    $fields[] = "$field = ?";
                    $values[] = $value;
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $id;
            $sql = "UPDATE live_accounts SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating account: " . $e->getMessage());
            return false;
        }
    }
    
    public static function addAccount($data) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO live_accounts (id, username, balance, equity, margin, status, last_activity) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $data['id'],
                $data['username'],
                $data['balance'],
                $data['equity'],
                $data['margin'],
                $data['status'] ?? 'active',
                $data['last_activity'] ?? 'Just now'
            ]);
        } catch (PDOException $e) {
            error_log("Error adding account: " . $e->getMessage());
            return false;
        }
    }
    
    public static function deleteAccount($id) {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("DELETE FROM live_accounts WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting account: " . $e->getMessage());
            return false;
        }
    }
    
    // Utility Methods
    public static function testConnection() {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT 1");
            return $stmt !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>