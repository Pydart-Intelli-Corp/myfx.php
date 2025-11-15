-- Database Setup Script for New MySQL User
-- Execute this script on your MySQL server: u509963569_lp_myfx
-- Host: blueviolet-caterpillar-353032.hostingersite.com
-- User: u509963569_lp_myfx_user
-- Date: 2025-10-26

-- Make sure you're connected to the correct database
USE u509963569_lp_myfx;

-- Drop existing tables if they exist (clean slate approach)
DROP TABLE IF EXISTS migration_log;
DROP TABLE IF EXISTS live_accounts;
DROP TABLE IF EXISTS trading_metrics;
DROP TABLE IF EXISTS users;

-- Create users table with enhanced security and profile features
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) NULL,
    full_name VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('administrator', 'super_admin') NOT NULL DEFAULT 'administrator',
    timezone VARCHAR(50) DEFAULT 'UTC',
    preferences JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create trading metrics table for dashboard statistics
CREATE TABLE trading_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_key VARCHAR(50) UNIQUE NOT NULL,
    metric_value VARCHAR(100) NOT NULL,
    change_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    trend ENUM('up', 'down', 'stable') NOT NULL DEFAULT 'stable',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by VARCHAR(50) NULL,
    
    INDEX idx_metric_key (metric_key),
    INDEX idx_updated_at (updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create live accounts table for trading account management
CREATE TABLE live_accounts (
    id VARCHAR(20) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    equity DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    margin DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('active', 'inactive', 'suspended', 'margin_call') NOT NULL DEFAULT 'active',
    last_activity VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_username (username),
    INDEX idx_updated_at (updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create migration log table to track applied migrations
CREATE TABLE migration_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration_file VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('success', 'failed') DEFAULT 'success',
    notes TEXT NULL,
    
    INDEX idx_migration_file (migration_file),
    INDEX idx_applied_at (applied_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin users
-- Password for both users: Access@myfx
-- Hash generated with: password_hash('Access@myfx', PASSWORD_DEFAULT)
INSERT INTO users (username, email, full_name, password_hash, role, timezone) VALUES 
('admin', 'admin@myforexcart.com', 'System Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrator', 'UTC'),
('superadmin', 'superadmin@myforexcart.com', 'Super Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'UTC');

-- Insert default trading metrics
INSERT INTO trading_metrics (metric_key, metric_value, change_percentage, trend, updated_by) VALUES 
('trading_volume', '$2,500,000', 12.5, 'up', 'system'),
('total_capital', '$50,000,000', 8.2, 'up', 'system'),
('commission', '$125,000', 15.3, 'up', 'system');

-- Insert default live accounts
INSERT INTO live_accounts (id, username, balance, equity, margin, status, last_activity) VALUES 
('1001', 'John Smith', 45230.50, 47120.30, 1200.00, 'active', '2 min ago'),
('1002', 'Sarah Johnson', 78940.25, 82150.75, 800.00, 'active', '5 min ago'),
('1003', 'Michael Brown', 23780.00, 24890.50, 950.00, 'active', '1 hour ago'),
('1004', 'Emma Davis', 156420.75, 159870.25, 2500.00, 'active', '3 hours ago'),
('1005', 'Robert Wilson', 89560.00, 87230.50, 1500.00, 'margin_call', '30 min ago');

-- Log this migration
INSERT INTO migration_log (migration_file, notes) VALUES 
('manual_setup_for_new_mysql_user.sql', 'Database setup for new MySQL user configuration - executed manually');

-- Verify the setup
SELECT 'Users created:' as info, COUNT(*) as count FROM users
UNION ALL
SELECT 'Metrics created:', COUNT(*) FROM trading_metrics
UNION ALL
SELECT 'Accounts created:', COUNT(*) FROM live_accounts;

-- Show table structure for verification
SHOW TABLES;