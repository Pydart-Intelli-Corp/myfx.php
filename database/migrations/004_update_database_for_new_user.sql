-- Migration: 004_update_database_for_new_user
-- Description: Updates database structure for new MySQL user configuration
-- Version: 1.2.0
-- Date: 2025-10-26
-- Purpose: Migrate from local/previous database to new external MySQL server

-- Use the new database name
USE u509963569_lp_myfx;

-- Recreate all tables with proper structure for the new database
-- This ensures compatibility with the new MySQL user and database

-- Drop existing tables if they exist (in case of migration from old setup)
DROP TABLE IF EXISTS live_accounts;
DROP TABLE IF EXISTS trading_metrics;
DROP TABLE IF EXISTS users;

-- Users table with enhanced security and profile features
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

-- Trading metrics table for dashboard statistics
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

-- Live accounts table for trading account management
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

-- Insert default admin users with updated passwords
-- Password for both users: Access@myfx
INSERT INTO users (username, email, full_name, password_hash, role, timezone) VALUES 
('admin', 'admin@myforexcart.com', 'System Administrator', '$2y$12$IqjudC5rMofhmmmdEMvvN.WHveP2zWc0Qsarw7mjbORsorUid7nmC', 'administrator', 'UTC'),
('superadmin', 'superadmin@myforexcart.com', 'Super Administrator', '$2y$12$IqjudC5rMofhmmmdEMvvN.WHveP2zWc0Qsarw7mjbORsorUid7nmC', 'super_admin', 'UTC');

-- Insert default trading metrics
INSERT INTO trading_metrics (metric_key, metric_value, change_percentage, trend, updated_by) VALUES 
('trading_volume', '$2,500,000', 12.5, 'up', 'system'),
('total_capital', '$50,000,000', 8.2, 'up', 'system'),
('commission', '$125,000', 15.3, 'up', 'system');

-- Insert default live accounts with current data
INSERT INTO live_accounts (id, username, balance, equity, margin, status, last_activity) VALUES 
('1001', 'John Smith', 45230.50, 47120.30, 1200.00, 'active', '2 min ago'),
('1002', 'Sarah Johnson', 78940.25, 82150.75, 800.00, 'active', '5 min ago'),
('1003', 'Michael Brown', 23780.00, 24890.50, 950.00, 'active', '1 hour ago'),
('1004', 'Emma Davis', 156420.75, 159870.25, 2500.00, 'active', '3 hours ago'),
('1005', 'Robert Wilson', 89560.00, 87230.50, 1500.00, 'margin_call', '30 min ago');

-- Create a migration log table to track applied migrations
CREATE TABLE IF NOT EXISTS migration_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration_file VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('success', 'failed') DEFAULT 'success',
    notes TEXT NULL,
    
    INDEX idx_migration_file (migration_file),
    INDEX idx_applied_at (applied_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Log this migration
INSERT INTO migration_log (migration_file, notes) VALUES 
('004_update_database_for_new_user.sql', 'Database structure updated for new MySQL user configuration');

-- Grant necessary permissions to the new MySQL user (if needed)
-- Note: These commands would typically be run by a database administrator
-- GRANT SELECT, INSERT, UPDATE, DELETE ON lp_myfx_db.* TO 'lp_myfx_user'@'%';
-- FLUSH PRIVILEGES;