<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.09 User dashboard. Shown after a successful login and
// displays the registration date plus the available services.
// ============================================================

session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$fullName  = $_SESSION['full_name'] ?? 'User';
$email     = $_SESSION['email'] ?? '';
$createdAt = $_SESSION['created_at'] ?? '';
$memberDate = $createdAt !== ''
    ? date('F j, Y', strtotime($createdAt))
    : 'the registration date stored in MySQL';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboad.css">
</head>
<body>
    <header class="dashboard-header">
        <div>
            <p class="eyebrow">User Dashboard</p>
            <h1>Welcome, <?php echo h($fullName); ?></h1>
            <p class="header-copy">You became a user of this site on <?php echo h($memberDate); ?>.</p>
        </div>
        <nav class="quick-actions" aria-label="Dashboard links">
            <a class="text-link" href="index.php">Home</a>
            <a class="button-link danger-link" href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-grid">
        <section class="dashboard-card">
            <h2>Account Information</h2>
            <dl>
                <dt>Name</dt>
                <dd><?php echo h($fullName); ?></dd>
                <dt>Email</dt>
                <dd><?php echo h($email); ?></dd>
                <dt>Member Since</dt>
                <dd><?php echo h($memberDate); ?></dd>
            </dl>
        </section>

        <section class="dashboard-card">
            <h2>Password &amp; Security</h2>
            <p>Need to change your password? Use the secure email-based reset to issue a one-time link.</p>
            <a class="button-link" href="forgot-password.php">Reset My Password</a>
        </section>

        <section class="dashboard-card">
            <h2>Session</h2>
            <p>You are currently signed in. Use logout to close your session on this device.</p>
            <a class="button-link danger-link" href="logout.php">Logout</a>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
