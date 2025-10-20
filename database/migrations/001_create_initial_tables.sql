-- Migration: 001_create_initial_tables
-- Description: Creates the initial database structure for the trading portal
-- Version: 1.0.0
-- Date: 2025-10-17

USE myfx;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('administrator', 'super_admin') NOT NULL DEFAULT 'administrator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trading metrics table
CREATE TABLE IF NOT EXISTS trading_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_key VARCHAR(50) UNIQUE NOT NULL,
    metric_value VARCHAR(100) NOT NULL,
    change_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    trend ENUM('up', 'down') NOT NULL DEFAULT 'up',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by VARCHAR(50) NULL,
    INDEX idx_metric_key (metric_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Live accounts table
CREATE TABLE IF NOT EXISTS live_accounts (
    id VARCHAR(20) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    equity DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    margin DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    last_activity VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;