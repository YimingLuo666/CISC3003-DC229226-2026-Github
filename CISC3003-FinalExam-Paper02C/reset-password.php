<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.07 Step 2: validate the reset token and update the
// password. The token is matched against the stored
// SHA-256 hash; it must not be expired and must be unused.
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$tokenValid = false;
$tokenRow = null;

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$userId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

function validate_reset_token(mysqli $conn, int $userId, string $token)
{
    if ($userId <= 0 || $token === '' || !ctype_xdigit($token) || strlen($token) !== 64) {
        return null;
    }

    $tokenHash = hash('sha256', $token);
    $stmt = $conn->prepare(
        'SELECT pr.id, pr.user_id, pr.expires_at, pr.used_at, u.full_name, u.email
           FROM password_resets pr
           JOIN users u ON u.id = pr.user_id
          WHERE pr.user_id = ? AND pr.token_hash = ?
          ORDER BY pr.id DESC
          LIMIT 1'
    );
    $stmt->bind_param('is', $userId, $tokenHash);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        return null;
    }

    if ($row['used_at'] !== null) {
        return null;
    }

    if (strtotime((string) $row['expires_at']) < time()) {
        return null;
    }

    return $row;
}

require __DIR__ . '/connect.php';

if ($conn === null) {
    $errors[] = $dbConnectionError;
} else {
    try {
        $tokenRow = validate_reset_token($conn, $userId, $token);

        if (!$tokenRow) {
            $errors[] = 'This password reset link is invalid, used, or expired. Please request a new one.';
        } else {
            $tokenValid = true;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 8) {
                    $errors[] = 'Password must contain at least 8 characters.';
                } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
                    $errors[] = 'Password must contain both letters and digits.';
                }

                if ($password !== $confirmPassword) {
                    $errors[] = 'The two passwords do not match.';
                }

                if (empty($errors)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);

                    $updateUser = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                    $updateUser->bind_param('si', $newHash, $tokenRow['user_id']);
                    $updateUser->execute();

                    $markUsed = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?');
                    $markUsed->bind_param('i', $tokenRow['id']);
                    $markUsed->execute();

                    // Invalidate any other outstanding reset rows for this user.
                    $invalidateOthers = $conn->prepare(
                        'UPDATE password_resets SET used_at = NOW()
                          WHERE user_id = ? AND used_at IS NULL'
                    );
                    $invalidateOthers->bind_param('i', $tokenRow['user_id']);
                    $invalidateOthers->execute();

                    header('Location: login.php?reset=1');
                    exit;
                }
            }
        }
    } catch (mysqli_sql_exception $e) {
        $errors[] = 'Password reset failed because of a database error. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/just-validate@4/dist/just-validate.production.min.js" defer></script>
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Password Reset</p>
            <h1>Choose a New Password</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Home</a>
            <a class="text-link" href="login.php">Sign In</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Reset Password</h2>

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

            <?php if ($tokenValid) : ?>
                <p class="small-note">Resetting password for <strong><?php echo h($tokenRow['email']); ?></strong>.</p>
                <form id="resetForm" action="reset-password.php" method="post" novalidate>
                    <input type="hidden" name="token" value="<?php echo h($token); ?>">
                    <input type="hidden" name="id" value="<?php echo h((string) $userId); ?>">

                    <div class="form-row">
                        <label for="password">New Password (≥ 8 chars, letters + digits)</label>
                        <input type="password" id="password" name="password"
                               required minlength="8" autocomplete="new-password">
                    </div>

                    <div class="form-row">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               required minlength="8" autocomplete="new-password">
                    </div>

                    <p id="resetClientMessage" class="client-message" aria-live="polite"></p>
                    <button type="submit">Update Password</button>
                </form>
            <?php else : ?>
                <a class="button-link" href="forgot-password.php">Request a New Link</a>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
