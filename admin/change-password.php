<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require_admin();

$user = admin_user();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    csrf_check();
    $current = (string) ($_POST['current'] ?? '');
    $new = (string) ($_POST['new'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    if (!$user || !password_verify($current, $user['password_hash'])) {
        flash('error', 'Current password is wrong.');
    } elseif (strlen($new) < 10) {
        flash('error', 'New password must be at least 10 characters.');
    } elseif ($new !== $confirm) {
        flash('error', "New password and confirmation don't match.");
    } else {
        $user['password_hash'] = password_hash($new, PASSWORD_DEFAULT);
        admin_save_user($user);
        flash('ok', 'Password changed.');
    }
    header('Location: change-password.php');
    exit;
}

admin_head('Account');
admin_nav('');
?>
<div class="admin-wrap">
  <?php admin_flash(); ?>
  <h1>Account</h1>
  <div class="admin-card" style="max-width:480px;">
    <p class="admin-muted">Signed in as <strong><?= e($user['username'] ?? '') ?></strong>.</p>
    <form method="post">
      <?= csrf_field() ?>
      <div class="field"><label>Current password</label><input type="password" name="current" autocomplete="current-password" required></div>
      <div class="field"><label>New password</label><input type="password" name="new" autocomplete="new-password" required minlength="10"></div>
      <div class="field"><label>Confirm new password</label><input type="password" name="confirm" autocomplete="new-password" required minlength="10"></div>
      <button class="btn btn-primary" type="submit">Change password</button>
    </form>
  </div>
</div>
<?php admin_foot(); ?>
