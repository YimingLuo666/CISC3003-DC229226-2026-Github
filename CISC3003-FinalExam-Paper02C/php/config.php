<?php
// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// Mailer + application configuration.
// Replace the placeholder values with real Gmail SMTP settings
// and the public APP_BASE_URL before going live.
// ============================================================

return [
    // Public URL where this project is hosted. Used to build
    // activation and password-reset links inside email bodies.
    'app_base_url' => 'http://localhost/CISC3003-FinalExam-Paper02C',

    // SMTP credentials (Gmail example).
    'smtp_host'      => 'smtp.gmail.com',
    'smtp_port'      => 587,
    'smtp_secure'    => 'tls',
    'smtp_username'  => 'your-gmail-address@gmail.com',
    'smtp_password'  => 'your-16-character-gmail-app-password',
    'from_email'     => 'your-gmail-address@gmail.com',
    'from_name'      => 'CISC3003 Paper 02C Account Service',
];
