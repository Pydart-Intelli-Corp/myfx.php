-- Migration: 003_add_user_profile_fields
-- Description: Adds additional profile fields to users table
-- Version: 1.1.0
-- Date: 2025-10-17

USE myfx;

-- Add new columns to users table
ALTER TABLE users 
ADD COLUMN email VARCHAR(255) NULL AFTER username,
ADD COLUMN full_name VARCHAR(100) NULL AFTER email,
ADD COLUMN phone VARCHAR(20) NULL AFTER full_name,
ADD COLUMN timezone VARCHAR(50) DEFAULT 'UTC' AFTER phone,
ADD COLUMN preferences JSON NULL AFTER timezone;

-- Add index for email
ALTER TABLE users ADD INDEX idx_email (email);

-- Update existing users with default values
UPDATE users SET 
    email = CONCAT(username, '@example.com'),
    full_name = CONCAT(UPPER(LEFT(username, 1)), SUBSTRING(username, 2)),
    timezone = 'UTC'
WHERE email IS NULL;