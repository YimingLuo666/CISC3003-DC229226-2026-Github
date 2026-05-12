<?php
session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$feedback = $_SESSION['contact_feedback'] ?? null;
unset($_SESSION['contact_feedback']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CISC3003 Final Exam Paper 02B</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div>
            <p class="eyebrow">Scenario B</p>
            <h1>SignUp / SignIn Service and Contact Form</h1>
            <p class="header-copy">A PHP contact form using client-side validation, PHPMailer, debugging, and POST / Redirect / GET.</p>
        </div>
        <nav class="quick-actions" aria-label="Account links">
            <button type="button" class="outline-button" data-go="register.php">Sign Up</button>
            <button type="button" data-go="login.php">Sign In</button>
        </nav>
    </header>

    <main class="page-grid">
        <section class="panel">
            <h2>Scenario B Contact Form</h2>

            <?php if ($feedback !== null) : ?>
                <div class="notice notice-<?php echo h($feedback['type']); ?>" role="status">
                    <strong><?php echo h($feedback['title']); ?></strong>
                    <?php if (!empty($feedback['messages'])) : ?>
                        <ul>
                            <?php foreach ($feedback['messages'] as $message) : ?>
                                <li><?php echo h($message); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form id="contactForm" action="php/send-contact.php" method="post" novalidate>
                <div class="form-row">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" autocomplete="name" required minlength="2" maxlength="80">
                </div>

                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" autocomplete="email" required maxlength="120">
                </div>

                <div class="form-row">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required minlength="3" maxlength="120">
                </div>

                <div class="form-row">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="7" required minlength="10" maxlength="1500"></textarea>
                </div>

                <p id="contactClientMessage" class="client-message" aria-live="polite"></p>
                <button type="submit">Send Message</button>
            </form>
        </section>

        <aside class="panel checklist">
            <h2>Paper 02B Evidence</h2>
            <ul>
                <li>B.01 HTML contact form with client-side validation</li>
                <li>B.02 PHPMailer files and SMTP configuration</li>
                <li>B.03 Email sending through PHPMailer</li>
                <li>B.04 Safe debug messages for sending problems</li>
                <li>B.05 POST / Redirect / GET after submit</li>
            </ul>
            <p class="small-note">Replace the placeholder values in <code>php/config.php</code> before testing real Gmail SMTP sending.</p>
        </aside>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
