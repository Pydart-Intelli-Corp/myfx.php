-- Migration: 002_insert_default_data
-- Description: Inserts default users, metrics, and accounts
-- Version: 1.0.1
-- Date: 2025-10-17

USE myfx;

-- Insert default users (password: Access@myfx for both)
INSERT INTO users (username, password_hash, role) VALUES 
('admin', '$2y$12$IqjudC5rMofhmmmdEMvvN.WHveP2zWc0Qsarw7mjbORsorUid7nmC', 'administrator'),
('superadmin', '$2y$12$IqjudC5rMofhmmmdEMvvN.WHveP2zWc0Qsarw7mjbORsorUid7nmC', 'super_admin')
ON DUPLICATE KEY UPDATE 
password_hash = VALUES(password_hash),
role = VALUES(role);

-- Insert default trading metrics
INSERT INTO trading_metrics (metric_key, metric_value, change_percentage, trend) VALUES 
('tradingVolume', '$2,847,392', 12.4, 'up'),
('totalCapital', '$18,924,581', 8.7, 'up'),
('commission', '$84,573', -2.3, 'down')
ON DUPLICATE KEY UPDATE 
metric_value = VALUES(metric_value),
change_percentage = VALUES(change_percentage),
trend = VALUES(trend);

-- Insert default live accounts
INSERT INTO live_accounts (id, username, balance, equity, margin, status, last_activity) VALUES 
('1001', 'trader_001', 50000.00, 52340.00, 1200.00, 'active', '2 min ago'),
('1002', 'trader_002', 25000.00, 23890.00, 800.00, 'active', '5 min ago'),
('1003', 'trader_003', 75000.00, 76200.00, 2500.00, 'inactive', '1 hour ago'),
('1004', 'trader_004', 30000.00, 28500.00, 950.00, 'suspended', '3 hours ago')
ON DUPLICATE KEY UPDATE 
username = VALUES(username),
balance = VALUES(balance),
equity = VALUES(equity),
margin = VALUES(margin),
status = VALUES(status),
last_activity = VALUES(last_activity);