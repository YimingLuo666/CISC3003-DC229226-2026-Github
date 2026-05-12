-- ============================================================
-- CISC3003 Final Exam Paper 02 - Scenario C
-- Database schema for the SignUp / SignIn service.
-- Import this file from phpMyAdmin before running the project.
-- ============================================================

CREATE DATABASE IF NOT EXISTS cisc3003_paper02c
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02c;

-- ------------------------------------------------------------
-- users: registered accounts. An account starts as inactive
-- (is_active = 0) and must confirm its email before login.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    activation_token CHAR(64) NULL,
    activation_expires DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- password_resets: short-lived reset tokens. Only the SHA-256
-- hash of the token is stored; the plain token only exists in
-- the email link.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_password_resets_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
