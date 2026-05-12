<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// Landing page. Provides the SignUp / SignIn entry points and
// lists the Scenario C evidence so the marker can navigate
// through the project quickly.
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$isLoggedIn = !empty($_SESSION['user_id']);
$fullName = $_SESSION['full_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CISC3003 Final Exam Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div>
            <p class="eyebrow">Scenario C</p>
            <h1>SignUp / SignIn Service</h1>
            <p class="header-copy">A PHP + MySQL account service with JavaScript validation, Ajax email checks,
                email activation, and secure password reset.</p>
        </div>
        <nav class="quick-actions" aria-label="Account links">
            <?php if ($isLoggedIn) : ?>
                <a class="button-link" href="dashboard.php">Open Dashboard</a>
                <a class="text-link" href="logout.php">Logout</a>
            <?php else : ?>
                <button type="button" class="outline-button" data-go="register.php">Sign Up</button>
                <button type="button" data-go="login.php">Sign In</button>
            <?php endif; ?>
        </nav>
    </header>

    <main class="page-grid">
        <section class="panel">
            <h2>Welcome<?php echo $isLoggedIn ? ', ' . h($fullName) : ''; ?></h2>
            <p>This site demonstrates a complete signup and sign-in workflow for CISC3003 Paper 02 Scenario C:</p>
            <ul>
                <li>Register with full name, email, and a password (letters + digits, ≥ 8 chars).</li>
                <li>Confirm your email by opening the activation link.</li>
                <li>Sign in and access the user dashboard.</li>
                <li>Reset a forgotten password via a short-lived email link.</li>
            </ul>

            <div class="cta-row">
                <?php if (!$isLoggedIn) : ?>
                    <a class="button-link" href="register.php">Create an Account</a>
                    <a class="button-link outline-link" href="login.php">Sign In</a>
                <?php else : ?>
                    <a class="button-link" href="dashboard.php">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </section>

        <aside class="panel checklist">
            <h2>Paper 02C Evidence</h2>
            <ul>
                <li>C.01 Signup page (<code>register.php</code>)</li>
                <li>C.02 Server-side validation in <code>register.php</code></li>
                <li>C.03 Prepared INSERT into MySQL</li>
                <li>C.04 Login (<code>login.php</code>) and Logout (<code>logout.php</code>)</li>
                <li>C.05 Browser validation via JustValidate (<code>js/script.js</code>)</li>
                <li>C.06 Ajax email check (<code>php/check-email.php</code>)</li>
                <li>C.07 Secure password reset (<code>forgot-password.php</code> + <code>reset-password.php</code>)</li>
                <li>C.08 Email activation required (<code>activate.php</code>)</li>
                <li>C.09 Dashboard services (<code>dashboard.php</code>)</li>
            </ul>
            <p class="small-note">Replace the placeholder values in <code>php/config.php</code> before testing
                with real Gmail SMTP.</p>
        </aside>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
