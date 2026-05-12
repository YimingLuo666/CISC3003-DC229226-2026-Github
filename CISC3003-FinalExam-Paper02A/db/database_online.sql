-- CISC3003 Final Exam Paper 02 - Scenario A (ONLINE / InfinityFree version)
-- Use this file when importing on a shared host where the database has
-- already been created for you (e.g. InfinityFree, 000webhost).
-- The original db/database.sql (with CREATE DATABASE) is for local XAMPP only.
--
-- Steps on InfinityFree:
--   1. Create a MySQL database in the control panel (e.g. if0_XXXXXXXX_paper02a)
--   2. Open phpMyAdmin from the control panel and select that database
--   3. Tab: Import -> choose this file -> Go

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
