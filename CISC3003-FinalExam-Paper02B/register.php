<?php
session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'The two passwords do not match.';
    }

    if (empty($errors)) {
        require __DIR__ . '/connect.php';

        if ($conn === null) {
            $errors[] = $dbConnectionError;
        } else {
            try {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $fullName, $email, $passwordHash);
                $stmt->execute();

                $_SESSION['register_success'] = 'Account created. Please sign in.';
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
    <title>Register - CISC3003 Paper 02B</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Create Account</p>
            <h1>Register</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Contact Form</a>
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
                    <input type="text" id="full_name" name="full_name" value="<?php echo h($fullName); ?>" required minlength="2" maxlength="80" autocomplete="name">
                </div>

                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo h($email); ?>" required maxlength="120" autocomplete="email">
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
                </div>

                <div class="form-row">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
                </div>

                <p id="registerClientMessage" class="client-message" aria-live="polite"></p>
                <button type="submit">Create Account</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
