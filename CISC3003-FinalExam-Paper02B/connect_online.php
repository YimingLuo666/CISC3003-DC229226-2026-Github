<?php
// CISC3003 Final Exam Paper 02B - ONLINE connect.php
// When uploading to InfinityFree, upload THIS file as "connect.php".
// Keep the local connect.php for XAMPP testing.

$databaseHost = 'sql210.infinityfree.com';
$databaseUser = 'if0_41894929';
$databasePassword = 'your-infinityfree-mysql-password'; // Replace before deploying to InfinityFree
$databaseName = 'if0_41894929_paper02b';

$conn = null;
$dbConnectionError = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($databaseHost, $databaseUser, $databasePassword, $databaseName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $dbConnectionError = 'Database connection failed. Please import db/database.sql and check your MySQL settings.';
}
?>
