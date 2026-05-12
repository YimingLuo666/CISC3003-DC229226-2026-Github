<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.08 Activate the user account by validating the token in
// the email link. Sets is_active = 1 on success.
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$status = 'error';
$title = 'Activation failed';
$message = 'The activation link is invalid or has expired.';

$token = trim((string) ($_GET['token'] ?? ''));

if ($token === '' || !ctype_xdigit($token) || strlen($token) !== 64) {
    $message = 'The activation link is malformed.';
} else {
    require __DIR__ . '/connect.php';

    if ($conn === null) {
        $message = $dbConnectionError;
    } else {
        try {
            $stmt = $conn->prepare(
                'SELECT id, full_name, is_active, activation_expires
                 FROM users WHERE activation_token = ? LIMIT 1'
            );
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if (!$user) {
                $message = 'We could not find an account for this activation link.';
            } elseif ((int) $user['is_active'] === 1) {
                $status = 'success';
                $title = 'Already activated';
                $message = 'This account has already been activated. You can sign in now.';
            } elseif (strtotime((string) $user['activation_expires']) < time()) {
                $message = 'This activation link has expired. Please register again or contact support.';
            } else {
                $update = $conn->prepare(
                    'UPDATE users
                       SET is_active = 1,
                           activation_token = NULL,
                           activation_expires = NULL
                     WHERE id = ?'
                );
                $update->bind_param('i', $user['id']);
                $update->execute();

                $status = 'success';
                $title = 'Account activated';
                $message = 'Thanks, ' . $user['full_name'] . '. Your email has been confirmed. You can now sign in.';
            }
        } catch (mysqli_sql_exception $e) {
            $message = 'Activation failed because of a database error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Account - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Email Confirmation</p>
            <h1>Activate Your Account</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Home</a>
            <a class="text-link" href="login.php">Sign In</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <div class="notice notice-<?php echo h($status === 'success' ? 'success' : 'error'); ?>" role="status">
                <strong><?php echo h($title); ?></strong>
                <p><?php echo h($message); ?></p>
            </div>

            <?php if ($status === 'success') : ?>
                <a class="button-link" href="login.php">Sign In Now</a>
            <?php else : ?>
                <a class="button-link" href="register.php">Register Again</a>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
