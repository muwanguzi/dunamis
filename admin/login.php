<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

if (admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$error = null;
$lockedFor = admin_is_locked_out($ip);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !$lockedFor) {
    csrf_check();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $user = admin_user();

    // Constant-shape check: always run password_verify, even with no user on
    // file, against a dummy hash — avoids timing/enumeration differences.
    $hash = $user['password_hash'] ?? '$2y$10$abcdefghijklmnopqrstuuQpZ0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0';
    $ok = $user && hash_equals($user['username'], $username) && password_verify($password, $hash);

    if ($ok) {
        admin_clear_failed_logins($ip);
        session_regenerate_id(true);
        $_SESSION['admin_user'] = $user['username'];
        header('Location: index.php');
        exit;
    }
    admin_register_failed_login($ip);
    $lockedFor = admin_is_locked_out($ip);
    $error = $lockedFor
        ? 'Too many attempts — try again in ' . ceil($lockedFor / 60) . ' minute(s).'
        : 'Wrong username or password.';
}

admin_head('Log in');
?>
<div class="admin-narrow">
  <div class="admin-login-card">
    <h1>Dunamis Admin</h1>
    <p class="admin-muted">Sign in to edit the website's content.</p>
    <?php if ($error): ?><div class="admin-flash admin-flash-error"><?= e($error) ?></div><?php endif; ?>
    <?php if ($lockedFor): ?>
      <p class="admin-muted">Locked for <?= (int) ceil($lockedFor / 60) ?> more minute(s).</p>
    <?php else: ?>
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <div class="field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" autocomplete="username" required autofocus>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn-primary" type="submit">Log in</button>
      </form>
    <?php endif; ?>
  </div>
</div>
<?php admin_foot(); ?>
