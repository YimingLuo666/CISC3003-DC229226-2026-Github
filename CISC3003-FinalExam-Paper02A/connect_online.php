<?php
/*
 * connect_online.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 *
 * ONLINE deployment version (InfinityFree).
 * When uploading to the hosting account, upload THIS file as "connect.php".
 * Keep the original local connect.php for XAMPP testing.
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// === InfinityFree MySQL credentials ===
$db_host = 'sql210.infinityfree.com';
$db_user = 'if0_41894929';
$db_pass = 'your-infinityfree-mysql-password'; // Replace before deploying to InfinityFree
$db_name = 'if0_41894929_paper02a';

try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
