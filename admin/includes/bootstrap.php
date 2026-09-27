<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '0'); // never leak paths/stack traces to visitors

require __DIR__ . '/../../includes/data.php'; // gives us e()
require __DIR__ . '/auth.php';
require __DIR__ . '/csrf.php';
require __DIR__ . '/store.php';
require __DIR__ . '/upload.php';

admin_start_session();

/** One-line success/error banner that survives a redirect. */
function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function take_flash(): ?array {
    if (empty($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}
