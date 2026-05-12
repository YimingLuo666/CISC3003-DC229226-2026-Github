<?php
session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$email = '';
$successMessage = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        require __DIR__ . '/connect.php';

        if ($conn === null) {
            $errors[] = $dbConnectionError;
        } else {
            try {
                $stmt = $conn->prepare('SELECT id, full_name, email, password_hash, created_at FROM users WHERE email = ? LIMIT 1');
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();

                if ($user && password_verify($password, $user['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['created_at'] = $user['created_at'];

                    header('Location: dashboard.php');
                    exit;
                }

                $errors[] = 'The email address or password is incorrect.';
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Login failed. Please check the database table and try again.';
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
    <title>Login - CISC3003 Paper 02B</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">User Service</p>
            <h1>Sign In</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Contact Form</a>
            <a class="text-link" href="register.php">Sign Up</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Login</h2>

            <?php if ($successMessage !== '') : ?>
                <div class="notice notice-success" role="status">
                    <strong><?php echo h($successMessage); ?></strong>
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

            <form id="loginForm" action="login.php" method="post" novalidate>
                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo h($email); ?>" required autocomplete="email">
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <p id="loginClientMessage" class="client-message" aria-live="polite"></p>
                <button type="submit">Sign In</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
