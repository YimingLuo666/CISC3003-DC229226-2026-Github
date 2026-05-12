<?php
/*
 * login.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 * Sign-in service. Uses a prepared SELECT (A.07) and password_verify().
 */
session_start();
require __DIR__ . '/connect.php';

$error    = '';
$username = '';
$justRegistered = isset($_GET['registered']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'] ?? '';

    if ($username === null || $username === false || trim($username) === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        // Prepared statement -> no SQL injection (A.07).
        $stmt = $mysqli->prepare(
            'SELECT id, full_name, password_hash, created_at
               FROM users
              WHERE username = ?
              LIMIT 1'
        );
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id, $full_name, $password_hash, $created_at);

        if ($stmt->fetch() && password_verify($password, $password_hash)) {
            $stmt->close();

            session_regenerate_id(true);
            $_SESSION['user_id']    = (int) $id;
            $_SESSION['full_name']  = $full_name;
            $_SESSION['created_at'] = $created_at;

            header('Location: dashboard.php');
            exit;
        }

        $stmt->close();
        $error = 'Invalid username or password.';
    }
}

function h($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - DC229226 Yiming Luo</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main>
        <section class="card">
            <h1>Sign in to your account</h1>

            <?php if ($justRegistered): ?>
                <div class="alert alert-success">
                    Registration successful. Please sign in with your new account.
                </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo h($error); ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" novalidate>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           value="<?php echo h($username); ?>"
                           maxlength="50" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="btn-row">
                    <button type="submit">Sign In</button>
                    <a class="btn btn-secondary" href="index.php">Cancel</a>
                </div>
            </form>

            <p style="margin-top:16px;">
                Don't have an account? <a href="register.php">Register here</a>.
            </p>
        </section>
    </main>

    <footer class="page-footer">
        CISC3003 Web Programming: DC229226 Yiming Luo 2026
    </footer>
</body>
</html>
