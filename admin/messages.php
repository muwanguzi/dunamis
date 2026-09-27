<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/messages_store.php';
require_admin();

$messages = read_messages();

admin_head('Messages');
admin_nav('messages');
?>
<div class="admin-wrap">
  <?php admin_flash(); ?>
  <h1>Messages</h1>
  <p class="admin-muted">Every submission from the contact form, newest first. Sent to <?= e($SITE['email'] ?? '') ?> by email too — this list is the backup copy.</p>

  <?php if (!$messages): ?>
    <div class="admin-card"><p class="admin-empty">No enquiries yet.</p></div>
  <?php else: foreach ($messages as $m): ?>
    <div class="admin-msg">
      <div class="admin-msg-meta">
        <strong><?= e($m['name']) ?></strong> ·
        <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>
        <?= $m['company'] ? ' · ' . e($m['company']) : '' ?>
        · <?= e(date('j M Y, H:i', strtotime($m['date']) ?: time())) ?>
        <?php if ($m['mail_failed']): ?><span style="color:var(--error)"> · email delivery failed, this log is the only copy</span><?php endif; ?>
      </div>
      <div class="admin-msg-body"><?= e($m['body']) ?></div>
    </div>
  <?php endforeach; endif; ?>
</div>
<?php admin_foot(); ?>
