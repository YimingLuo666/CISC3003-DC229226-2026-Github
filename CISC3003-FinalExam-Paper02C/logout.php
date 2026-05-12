<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.04 Sign-out: clear session data and the session cookie,
// then render a confirmation page.
// ============================================================

session_start();
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Session Closed</p>
            <h1>You have signed out</h1>
        </div>
        <nav class="quick-actions" aria-label="Page links">
            <a class="text-link" href="index.php">Home</a>
            <a class="text-link" href="login.php">Sign In Again</a>
        </nav>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Logout Complete</h2>
            <p>Your account session has been closed.</p>
            <a class="button-link" href="login.php">Return to Login</a>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
