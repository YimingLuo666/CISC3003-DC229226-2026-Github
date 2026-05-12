<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// SMTP diagnostic page. Visit this URL while testing locally
// to verify that Gmail SMTP credentials work. NOT linked from
// the public UI; it is only here for debugging.
// Example:
//   /CISC3003-FinalExam-Paper02C/php/test-mail.php?to=you@gmail.com
// ============================================================

require __DIR__ . '/mailer.php';

header('Content-Type: text/html; charset=utf-8');

$to = trim((string) ($_GET['to'] ?? ''));
$result = null;

if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
    $subject = 'CISC3003 Paper 02C SMTP test';
    $body = "Hello,\n\nThis is a SMTP diagnostic email from CISC3003 Paper 02C.\n\nIf you can read this, PHPMailer is working.";
    $result = send_mail($to, 'SMTP Tester', $subject, $body, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMTP Test - CISC3003 Paper 02C</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="site-header compact-header">
        <div>
            <p class="eyebrow">Diagnostics</p>
            <h1>SMTP Test</h1>
        </div>
    </header>

    <main class="single-panel">
        <section class="panel">
            <h2>Send a test email</h2>
            <form method="get" action="test-mail.php">
                <div class="form-row">
                    <label for="to">Send test email to</label>
                    <input type="email" id="to" name="to" required
                           value="<?php echo htmlspecialchars($to, ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="your-address@gmail.com">
                </div>
                <button type="submit">Send Test Email</button>
            </form>

            <?php if ($result !== null) : ?>
                <hr>
                <h3>Result</h3>
                <p>
                    <strong>ok</strong>:
                    <?php echo $result['ok'] ? '<span style="color:#146c2e">true</span>' : '<span style="color:#9f1d28">false</span>'; ?>
                </p>
                <?php if (!empty($result['error'])) : ?>
                    <p><strong>error</strong>:</p>
                    <pre style="background:#fdebec;padding:10px;border-radius:6px;white-space:pre-wrap;"><?php
                        echo htmlspecialchars($result['error'], ENT_QUOTES, 'UTF-8');
                    ?></pre>
                <?php endif; ?>
                <?php if (!empty($result['debug'])) : ?>
                    <p><strong>SMTP conversation</strong>:</p>
                    <pre style="background:#eef2f7;padding:10px;border-radius:6px;white-space:pre-wrap;font-size:0.85rem;"><?php
                        echo htmlspecialchars($result['debug'], ENT_QUOTES, 'UTF-8');
                    ?></pre>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        CISC3003 Web Programming: Yiming Luo + DC229226 + 2026
    </footer>
</body>
</html>
