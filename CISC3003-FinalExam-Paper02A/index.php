<?php
/*
 * index.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 * Front page of the SignUp / SignIn service.
 */
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DC229226 Yiming Luo - Paper02A</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main>
        <section class="card">
            <h1>Welcome to the CISC3003 Paper 02A Demo</h1>
            <p>
                This site demonstrates the ten Scenario A tasks (A.01 - A.10):
                building an HTML form, validating it with PHP filter functions,
                and inserting the record into a MySQL database via a prepared
                statement.
            </p>

            <?php if ($loggedIn): ?>
                <div class="alert alert-info">
                    You are already signed in as
                    <strong><?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?></strong>.
                </div>
                <div class="btn-row">
                    <a class="btn" href="dashboard.php">Go to Dashboard</a>
                    <a class="btn btn-secondary" href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <p>Choose an option to get started:</p>
                <div class="btn-row">
                    <a id="btn-signup" class="btn" href="register.php">Sign Up</a>
                    <a id="btn-signin" class="btn btn-secondary" href="login.php">Sign In</a>
                </div>
                <p id="button-hint" class="alert alert-info" style="margin-top:18px;">
                    Hover over a button to see what it does.
                </p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="page-footer">
        CISC3003 Web Programming: DC229226 Yiming Luo 2026
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
