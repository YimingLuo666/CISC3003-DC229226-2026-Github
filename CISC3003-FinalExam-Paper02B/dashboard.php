<?php
session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$fullName = $_SESSION['full_name'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$createdAt = $_SESSION['created_at'] ?? '';
$memberDate = $createdAt !== '' ? date('F j, Y', strtotime($createdAt)) : 'the registration date stored in MySQL';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CISC3003 Paper 02B</title>
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
            <a class="text-link" href="index.php">Contact Form</a>
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
            <h2>Available Service</h2>
            <p>Use the Scenario B contact form to send a message through PHPMailer after the SMTP placeholders are replaced.</p>
            <a class="button-link" href="index.php#contactForm">Open Contact Form</a>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
