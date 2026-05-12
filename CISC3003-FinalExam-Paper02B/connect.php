<?php
// Database connection service for CISC3003 Final Exam Paper 02B.
$databaseHost = 'localhost';
$databaseUser = 'root';
$databasePassword = '';
$databaseName = 'cisc3003_paper02b';

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
