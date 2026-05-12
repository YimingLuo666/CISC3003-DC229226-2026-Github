<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// Database connection service. Update the credentials below to
// match your XAMPP MySQL setup before deployment.
// ============================================================

$databaseHost = 'localhost';
$databaseUser = 'root';
$databasePassword = '';
$databaseName = 'cisc3003_paper02c';

$conn = null;
$dbConnectionError = '';

// Throw mysqli_sql_exception on errors so callers can use try / catch.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($databaseHost, $databaseUser, $databasePassword, $databaseName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $dbConnectionError = 'Database connection failed. Please import db/database.sql and check your MySQL settings.';
}
?>
