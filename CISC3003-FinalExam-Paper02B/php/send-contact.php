<?php
session_start();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function redirect_with_feedback($type, $title, array $messages)
{
    $_SESSION['contact_feedback'] = [
        'type' => $type,
        'title' => $title,
        'messages' => $messages,
    ];

    $status = $type === 'success' ? 'sent' : 'error';
    header('Location: ../index.php?status=' . $status . '#contactForm');
    exit;
}

function clean_text($value)
{
    return trim(str_replace(["\r", "\n"], ' ', (string) $value));
}

function smtp_config_has_placeholders(array $config)
{
    $placeholderValues = [
        'your-gmail-address@gmail.com',
        'your-16-character-gmail-app-password',
    ];

    return in_array($config['smtp_username'], $placeholderValues, true)
        || in_array($config['smtp_password'], $placeholderValues, true)
        || in_array($config['recipient_email'], $placeholderValues, true);
}

function sanitize_debug_lines(array $lines, array $config)
{
    $sensitiveValues = [
        $config['smtp_username'] ?? '',
        $config['smtp_password'] ?? '',
        base64_encode($config['smtp_username'] ?? ''),
        base64_encode($config['smtp_password'] ?? ''),
    ];

    $safeLines = [];

    foreach ($lines as $line) {
        foreach ($sensitiveValues as $value) {
            if ($value !== '') {
                $line = str_replace($value, '[hidden]', $line);
            }
        }

        $safeLines[] = $line;
    }

    return $safeLines;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$name = clean_text($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = clean_text($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$errors = [];

if ($name === '' || strlen($name) < 2) {
    $errors[] = 'Please enter your full name.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($subject === '' || strlen($subject) < 3) {
    $errors[] = 'Please enter a subject with at least 3 characters.';
}

if ($message === '' || strlen($message) < 10) {
    $errors[] = 'Please enter a message with at least 10 characters.';
}

if (!empty($errors)) {
    redirect_with_feedback('error', 'The contact form was not sent.', $errors);
}

$config = require __DIR__ . '/config.php';

if (smtp_config_has_placeholders($config)) {
    redirect_with_feedback('error', 'SMTP is not configured yet.', [
        'Replace the placeholder Gmail SMTP values in php/config.php.',
        'After updating the Gmail address, app password, and recipient address, submit the form again.',
    ]);
}

$debugLines = [];
$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function ($debugMessage, $debugLevel) use (&$debugLines) {
        $debugLines[] = 'SMTP level ' . $debugLevel . ': ' . trim($debugMessage);
    };

    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_secure'] === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) $config['smtp_port'];

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['recipient_email'], $config['recipient_name']);
    $mail->addReplyTo($email, $name);

    $mail->Subject = '[CISC3003 Paper 02B] ' . $subject;
    $mail->Body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";

    $mail->send();

    redirect_with_feedback('success', 'Your message was sent successfully.', [
        'The contact form used PHPMailer and the POST / Redirect / GET pattern.',
    ]);
} catch (Exception $e) {
    $safeDebugLines = sanitize_debug_lines($debugLines, $config);
    $messages = ['PHPMailer error: ' . $mail->ErrorInfo];

    if (!empty($safeDebugLines)) {
        $messages[] = 'Debug output:';
        foreach (array_slice($safeDebugLines, 0, 8) as $line) {
            $messages[] = $line;
        }
    }

    redirect_with_feedback('error', 'The email could not be sent.', $messages);
}
?>
