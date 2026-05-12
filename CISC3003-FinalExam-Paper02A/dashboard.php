<?php
/*
 * dashboard.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 * Welcome page shown after a successful login.
 * Displays the date the user became a member of the site.
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/connect.php';

$stmt = $mysqli->prepare(
    'SELECT full_name, username, email, country, gender, interests, bio, created_at
       FROM users
      WHERE id = ?
      LIMIT 1'
);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($full_name, $username, $email, $country, $gender, $interests, $bio, $created_at);
$stmt->fetch();
$stmt->close();

$member_since = $created_at !== null
    ? date('F j, Y \a\t g:i A', strtotime($created_at))
    : 'unknown';

function h($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DC229226 Yiming Luo</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <section class="welcome-card">
            <h1>Welcome, <?php echo h($full_name); ?>!</h1>
            <p class="subtitle">You are signed in to the CISC3003 Paper 02A demo site.</p>

            <div class="member-since">
                Member since: <?php echo h($member_since); ?>
            </div>

            <div class="meta-grid">
                <div class="meta-label">Username</div>
                <div class="meta-value"><?php echo h($username); ?></div>

                <div class="meta-label">Email</div>
                <div class="meta-value"><?php echo h($email); ?></div>

                <div class="meta-label">Country</div>
                <div class="meta-value"><?php echo h($country); ?></div>

                <div class="meta-label">Gender</div>
                <div class="meta-value"><?php echo h($gender); ?></div>

                <div class="meta-label">Interests</div>
                <div class="meta-value"><?php echo h($interests !== '' ? $interests : '(none)'); ?></div>

                <?php if ($bio !== null && $bio !== ''): ?>
                    <div class="meta-label">About</div>
                    <div class="meta-value"><?php echo nl2br(h($bio)); ?></div>
                <?php endif; ?>
            </div>

            <div class="action-row">
                <a class="btn" href="index.php">Home</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </section>
    </div>

    <footer class="page-footer">
        CISC3003 Web Programming: DC229226 Yiming Luo 2026
    </footer>
</body>
</html>
