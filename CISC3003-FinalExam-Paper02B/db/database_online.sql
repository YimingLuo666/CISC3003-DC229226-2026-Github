-- CISC3003 Final Exam Paper 02 - Scenario B (ONLINE / InfinityFree version)
-- Use this file when importing on a shared host where the database has
-- already been created for you (e.g. InfinityFree, 000webhost).
-- The original db/database.sql (with CREATE DATABASE) is for local XAMPP only.
--
-- Steps on InfinityFree:
--   1. Create a MySQL database in the control panel (e.g. if0_XXXXXXXX_paper02b)
--   2. Open phpMyAdmin from the control panel and select that database
--   3. Tab: Import -> choose this file -> Go

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
