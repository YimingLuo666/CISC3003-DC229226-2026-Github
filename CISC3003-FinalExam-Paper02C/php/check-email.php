<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.06 Ajax endpoint: validate that an email address is well
// formed and not yet registered. Returns JSON to the browser.
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$email = trim((string) ($_GET['email'] ?? ''));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'available' => false,
        'reason'    => 'invalid',
        'message'   => 'Please enter a valid email address.',
    ]);
    exit;
}

require __DIR__ . '/../connect.php';

if ($conn === null) {
    http_response_code(500);
    echo json_encode([
        'available' => false,
        'reason'    => 'database',
        'message'   => $dbConnectionError,
    ]);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc() !== null;

    if ($exists) {
        echo json_encode([
            'available' => false,
            'reason'    => 'taken',
            'message'   => 'This email address is already registered.',
        ]);
        exit;
    }

    echo json_encode([
        'available' => true,
        'reason'    => 'ok',
        'message'   => 'Email is available.',
    ]);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode([
        'available' => false,
        'reason'    => 'database',
        'message'   => 'Unable to check email at this time.',
    ]);
}
