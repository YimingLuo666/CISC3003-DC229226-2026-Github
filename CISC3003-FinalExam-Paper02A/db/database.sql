-- CISC3003 Final Exam Paper 02 - Scenario A
-- A.09: create a database and table using phpMyAdmin
-- A.10: use an SQL INSERT INTO statement to insert a record
-- Run this script in phpMyAdmin: Import / SQL tab

CREATE DATABASE IF NOT EXISTS cisc3003_paper02a
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02a;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100)  NOT NULL,
    username      VARCHAR(50)   NOT NULL UNIQUE,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    bio           TEXT,
    country       VARCHAR(50),
    gender        VARCHAR(30),
    interests     VARCHAR(255),
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Example INSERT INTO statement for A.10 reference.
-- The application performs the actual insert through a prepared statement
-- in register.php (see A.07 / A.08).
-- INSERT INTO users (full_name, username, email, password_hash, bio, country, gender, interests)
-- VALUES ('Demo User', 'demo', 'demo@example.com', '<hash>', 'Hello!', 'Macau', 'Male', 'Reading,Coding');
