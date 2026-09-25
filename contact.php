<?php
/**
 * Contact form handler.
 * - Accepts POST from the homepage form (AJAX or classic).
 * - Validates, blocks spam via honeypot + basic rate sense.
 * - Emails the enquiry via mail() and always appends to storage/messages.log as a backup.
 * Responds with JSON when requested with fetch(), otherwise redirects back.
 */

declare(strict_types=1);

require __DIR__ . '/includes/data.php';

const RECIPIENT = 'dunamismediacompanylimited@gmail.com';
// Sender must be on the site's own domain so SPF/DKIM line up and inboxes trust it.
const SENDER    = 'no-reply@dunamismedia.ug';

$wantsJson = (
    stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch'
);

function respond(bool $ok, string $message, int $code = 200): void {
    global $wantsJson;
    http_response_code($ok ? $code : ($code === 200 ? 422 : $code));
    if ($wantsJson) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'message' => $message]);
    } else {
        $flash = urlencode($message);
        header('Location: index.php?sent=' . ($ok ? '1' : '0') . '&msg=' . $flash . '#contact');
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

/* Honeypot – real users never fill this */
if (!empty(trim($_POST['website'] ?? ''))) {
    respond(true, 'Thanks — we\'ll be in touch.'); // silently accept, drop
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$company = trim($_POST['company'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '' || mb_strlen($name) > 120)          $errors[] = 'a valid name';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))       $errors[] = 'a valid email';
if ($message === '' || mb_strlen($message) > 5000)    $errors[] = 'a short message';

if ($errors) {
    respond(false, 'Please add ' . implode(', ', $errors) . '.');
}

$clean = fn(string $s) => preg_replace('/[\r\n]+/', ' ', $s);
$entry = sprintf(
    "[%s] %s <%s> | %s\n%s\n%s\n",
    date('c'),
    $clean($name),
    $clean($email),
    $clean($company !== '' ? $company : '—'),
    str_repeat('-', 40),
    $message
);

/* Fallback store */
$dir = __DIR__ . '/storage';
if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
@file_put_contents($dir . '/messages.log', $entry . "\n", FILE_APPEND | LOCK_EX);

/* Attempt email */
$subject = '=?UTF-8?B?' . base64_encode('New enquiry — ' . $clean($name)) . '?=';
$body    = "Name: {$clean($name)}\nEmail: {$clean($email)}\nCompany: "
         . ($company !== '' ? $clean($company) : '—')
         . "\n\nMessage:\n{$message}\n";
$headers = "From: Dunamis Website <" . SENDER . ">\r\n"
         . "Reply-To: {$clean($name)} <{$clean($email)}>\r\n"
         . "MIME-Version: 1.0\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n";

$mailed = @mail(RECIPIENT, $subject, $body, $headers, '-f' . SENDER);
if (!$mailed) {
    @file_put_contents($dir . '/messages.log', '[' . date('c') . "] ^ mail() failed for the entry above\n\n", FILE_APPEND | LOCK_EX);
}

respond(true, 'Thanks, ' . $clean($name) . ' — your message is in. We\'ll reply within one working day.');
