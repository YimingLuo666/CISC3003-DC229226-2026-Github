<?php
/*
 * connect.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 * Provides the mysqli connection used by register.php, login.php and dashboard.php.
 * XAMPP defaults: host=localhost, user=root, password=empty.
 */

// Surface MySQL errors as exceptions during development (helps task A.07 testing).
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'cisc3003_paper02a';

try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
