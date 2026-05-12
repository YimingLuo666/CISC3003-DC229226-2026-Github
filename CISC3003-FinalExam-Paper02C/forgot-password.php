<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.07 Step 1: accept an email, issue a single-use token, and
// email a reset link. The plain token is only sent in email;
// the database only stores its SHA-256 hash.
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$email = '';
$infoMessage = '';
$fallbackLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        require __DIR__ . '/connect.php';
        require __DIR__ . '/php/mailer.php';

        if ($conn === null) {
            $errors[] = $dbConnectionError;
        } else {
            try {
                $stmt = $conn->prepare(
                    'SELECT id, full_name, is_active FROM users WHERE email = ? LIMIT 1'
                );
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();

                if ($user && (int) $user['is_active'] === 1) {
                    $plainToken = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $plainToken);
                    $expiresAt = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

                    $insert = $conn->prepare(
                        'INSERT INTO password_resets (user_id, token_hash, expires_at)
                         VALUES (?, ?, ?)'
                    );
                    $insert->bind_param('iss', $user['id'], $tokenHash, $expiresAt);
                    $insert->execute();

                    $config = load_mail_config();
                    $resetLink = rtrim($config['app_base_url'], '/')
                        . '/reset-password.php?token=' . urlencode($plainToken)
                        . '&id=' . (int) $user['id'];

                    $subject = 'Reset your CISC3003 Paper 02C password';
                    $body = "Hello {$user['full_name']},\n\n"
                        . "We received a request to reset the password for your account. "
                        . "If you made this request, open the link below within 1 hour:\n\n"
                        . $resetLink . "\n\n"
                        . "If you did not request a password reset, you can safely ignore this message.\n\n"
                        . "-- CISC3003 Paper 02C";

                    $result = send_mail($email, $user['full_name'], $subject, $body);

                    if ($result['fallback']) {
                        $fallbackLink = $resetLink;
                    }
                }

                // Always show the same response regardless of whether the email exists.
                $infoMessage = 'If an active account exists for that address, a password reset email has been sent. '
                    . 'The link will be valid for one hour.';
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Unable to start the password reset right now. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/just-validate@4/dist/just-validate.production.min.js" defer></script>
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Password Help</p>
            <h1>Forgot Your Password?</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Home</a>
            <a class="text-link" href="login.php">Sign In</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Request Password Reset</h2>

            <?php if ($infoMessage !== '') : ?>
                <div class="notice notice-success" role="status">
                    <strong><?php echo h($infoMessage); ?></strong>
                    <?php if ($fallbackLink !== '') : ?>
                        <p class="small-note">SMTP is not configured, so the test link is:
                            <br><code><?php echo h($fallbackLink); ?></code></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)) : ?>
                <div class="notice notice-error" role="alert">
                    <strong>Please correct the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="forgotForm" action="forgot-password.php" method="post" novalidate>
                <div class="form-row">
                    <label for="email">Account Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo h($email); ?>"
                           required maxlength="120" autocomplete="email">
                </div>

                <p id="forgotClientMessage" class="client-message" aria-live="polite"></p>
                <button type="submit">Send Reset Link</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
