<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// Shared helpers around PHPMailer. send_mail() sends an HTML
// email when SMTP credentials are configured, and falls back
// to error_log so the activation / reset link is still
// discoverable while testing without a real SMTP account.
// ============================================================

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

function load_mail_config()
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    return $config;
}

function smtp_is_placeholder(array $config)
{
    $placeholderValues = [
        'your-gmail-address@gmail.com',
        'your-16-character-gmail-app-password',
    ];

    return in_array($config['smtp_username'], $placeholderValues, true)
        || in_array($config['smtp_password'], $placeholderValues, true);
}

/**
 * Send a plain-text email. Returns an array shaped like
 * ['ok' => bool, 'error' => string, 'debug' => string,
 *  'fallback' => bool].
 * When SMTP is not configured the message body is written to
 * the PHP error log so links remain testable.
 */
function send_mail($toEmail, $toName, $subject, $body, $debug = false)
{
    $config = load_mail_config();

    if (smtp_is_placeholder($config)) {
        error_log('[Paper02C][MAIL-FALLBACK] To: ' . $toEmail
            . ' | Subject: ' . $subject
            . ' | Body: ' . str_replace(["\r", "\n"], ' / ', $body));
        return ['ok' => true, 'error' => '', 'debug' => '', 'fallback' => true];
    }

    $mail = new PHPMailer(true);
    $debugLines = [];

    try {
        if ($debug) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function ($msg, $level) use (&$debugLines) {
                $debugLines[] = 'lvl ' . $level . ': ' . trim($msg);
            };
        }

        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = str_replace(' ', '', (string) $config['smtp_password']);
        $mail->SMTPSecure = $config['smtp_secure'] === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $config['smtp_port'];

        // XAMPP on macOS / Windows often lacks an up-to-date CA bundle, so
        // TLS verification against smtp.gmail.com fails. The relaxed
        // options below let local testing succeed; remove them in
        // production once a proper cacert.pem is configured in php.ini.
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($toEmail, $toName);

        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);

        $mail->send();
        return [
            'ok'       => true,
            'error'    => '',
            'debug'    => implode("\n", $debugLines),
            'fallback' => false,
        ];
    } catch (Exception $e) {
        $error = $mail->ErrorInfo !== '' ? $mail->ErrorInfo : $e->getMessage();
        error_log('[Paper02C][MAIL-ERROR] ' . $error);
        return [
            'ok'       => false,
            'error'    => $error,
            'debug'    => implode("\n", $debugLines),
            'fallback' => false,
        ];
    }
}
