<?php
/**
 * Session + login handling for the admin panel.
 * Single admin account; credentials live in content/admin-user.json
 * (username + a password_hash, never the plaintext password).
 */

declare(strict_types=1);

function admin_user_path(): string {
    return __DIR__ . '/../../content/admin-user.json';
}

function admin_user(): ?array {
    $path = admin_user_path();
    if (!is_file($path)) return null;
    $data = json_decode((string) file_get_contents($path), true);
    if (!is_array($data) || empty($data['username']) || empty($data['password_hash'])) return null;
    return $data;
}

function admin_save_user(array $user): void {
    file_put_contents(admin_user_path(), json_encode($user, JSON_PRETTY_PRINT) . "\n", LOCK_EX);
}

function admin_start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $secure,
    ]);
    session_name('dunamis_admin');
    session_start();
}

function admin_logged_in(): bool {
    admin_start_session();
    return !empty($_SESSION['admin_user']);
}

function require_admin(): void {
    if (!admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/* ---- brute-force throttling: a short file-based counter per IP ---- */
function admin_login_throttle_path(): string {
    return sys_get_temp_dir() . '/dunamis-admin-login-' . md5(__DIR__) . '.json';
}

function admin_login_attempts(string $ip): array {
    $path = admin_login_throttle_path();
    if (!is_file($path)) return [];
    $all = json_decode((string) file_get_contents($path), true);
    return is_array($all) && isset($all[$ip]) && is_array($all[$ip]) ? $all[$ip] : [];
}

function admin_register_failed_login(string $ip): void {
    $path = admin_login_throttle_path();
    $all = is_file($path) ? json_decode((string) file_get_contents($path), true) : [];
    if (!is_array($all)) $all = [];
    $entry = $all[$ip] ?? ['count' => 0, 'until' => 0];
    $entry['count'] = (int) ($entry['count'] ?? 0) + 1;
    // Back off: 5 tries free, then a growing lockout window.
    if ($entry['count'] > 5) {
        $entry['until'] = time() + min(900, 15 * ($entry['count'] - 5));
    }
    $all[$ip] = $entry;
    @file_put_contents($path, json_encode($all), LOCK_EX);
}

function admin_clear_failed_logins(string $ip): void {
    $path = admin_login_throttle_path();
    if (!is_file($path)) return;
    $all = json_decode((string) file_get_contents($path), true);
    if (is_array($all) && isset($all[$ip])) {
        unset($all[$ip]);
        @file_put_contents($path, json_encode($all), LOCK_EX);
    }
}

function admin_is_locked_out(string $ip): int {
    $entry = admin_login_attempts($ip);
    $until = (int) ($entry['until'] ?? 0);
    return $until > time() ? $until - time() : 0;
}
