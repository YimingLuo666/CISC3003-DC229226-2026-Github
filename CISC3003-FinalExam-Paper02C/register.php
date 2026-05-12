<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.01 SignUp page + C.02 server-side validation
// C.03 Save to MySQL with a prepared INSERT
// C.08 Issue an activation token and send an activation email
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$fullName = '';
$email = '';
$activationFallbackLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // ---- C.02 Server-side validation using filter functions and helpers ----
    if ($fullName === '' || strlen($fullName) < 2) {
        $errors[] = 'Please enter your full name (at least 2 characters).';
    }

    $validatedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($validatedEmail === false) {
        $errors[] = 'A valid email address is required.';
    } else {
        $email = $validatedEmail;
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = 'Password must contain both letters and digits.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'The two passwords do not match.';
    }

    if (empty($errors)) {
        require __DIR__ . '/connect.php';
        require __DIR__ . '/php/mailer.php';

        if ($conn === null) {
            $errors[] = $dbConnectionError;
        } else {
            try {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $activationToken = bin2hex(random_bytes(32));
                $activationExpires = (new DateTime('+1 day'))->format('Y-m-d H:i:s');

                // ---- C.03 Prepared INSERT to avoid SQL injection ----
                $stmt = $conn->prepare(
                    'INSERT INTO users
                        (full_name, email, password_hash, is_active, activation_token, activation_expires)
                     VALUES (?, ?, ?, 0, ?, ?)'
                );
                $stmt->bind_param('sssss', $fullName, $email, $passwordHash, $activationToken, $activationExpires);
                $stmt->execute();

                // ---- C.08 Send activation email ----
                $config = load_mail_config();
                $activationLink = rtrim($config['app_base_url'], '/')
                    . '/activate.php?token=' . urlencode($activationToken);

                $subject = 'Confirm your CISC3003 Paper 02C account';
                $body = "Hello {$fullName},\n\n"
                    . "Thank you for registering. Please confirm your email address by opening the link below "
                    . "within 24 hours:\n\n"
                    . $activationLink . "\n\n"
                    . "If you did not request this account, you can ignore this message.\n\n"
                    . "-- CISC3003 Paper 02C";

                $result = send_mail($email, $fullName, $subject, $body);

                if (!$result['ok']) {
                    // Email failed; still let the user activate via the
                    // fallback link so they are not locked out, but make
                    // the SMTP error visible for debugging.
                    $_SESSION['register_success'] = 'Account created, but the activation email could not be sent. Use the link below to activate manually.';
                    $_SESSION['register_activation_link'] = $activationLink;
                    $_SESSION['register_mail_error'] = $result['error'];
                } elseif ($result['fallback']) {
                    $_SESSION['register_success'] = 'Account created. SMTP is not configured, so the activation link was written to the PHP error log.';
                    $_SESSION['register_activation_link'] = $activationLink;
                } else {
                    $_SESSION['register_success'] = 'Account created. Please open the activation link sent to your email before signing in.';
                }

                // ---- PRG: redirect after successful POST ----
                header('Location: login.php?registered=1');
                exit;
            } catch (mysqli_sql_exception $e) {
                if ((int) $e->getCode() === 1062) {
                    $errors[] = 'This email address has already been registered.';
                } else {
                    $errors[] = 'Registration failed. Please check the database table and try again.';
                }
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
    <title>Register - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/just-validate@4/dist/just-validate.production.min.js" defer></script>
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Scenario C</p>
            <h1>Create Your Account</h1>
            <p class="header-copy">Sign up, confirm your email, then sign in to access the user dashboard.</p>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Home</a>
            <a class="text-link" href="login.php">Sign In</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Sign Up</h2>

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

            <form id="registerForm" action="register.php" method="post" novalidate>
                <div class="form-row">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name"
                           value="<?php echo h($fullName); ?>"
                           required minlength="2" maxlength="80" autocomplete="name">
                </div>

                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo h($email); ?>"
                           required maxlength="120" autocomplete="email">
                    <p id="emailAjaxMessage" class="ajax-message" aria-live="polite"></p>
                </div>

                <div class="form-row">
                    <label for="password">Password (≥ 8 chars, letters + digits)</label>
                    <input type="password" id="password" name="password"
                           required minlength="8" autocomplete="new-password">
                </div>

                <div class="form-row">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                           required minlength="8" autocomplete="new-password">
                </div>

                <p id="registerClientMessage" class="client-message" aria-live="polite"></p>
                <button type="submit">Create Account</button>
            </form>

            <p class="small-note">After registering, open the activation link emailed to you. The link is valid for 24 hours.</p>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
