<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/messages_store.php';
require_admin();

$schemas = content_schemas();
$counts = [];
foreach ($schemas as $key => $schema) {
    $counts[$key] = count(content_load($key));
}
$messages = read_messages();
$recentMessages = array_slice($messages, 0, 5);

admin_head('Dashboard');
admin_nav('');
?>
<div class="admin-wrap">
  <?php admin_flash(); ?>
  <h1>Dashboard</h1>
  <p class="admin-muted">Edit anything below — changes go live on the site immediately.</p>

  <div class="admin-grid" style="margin-top:1.5rem;">
    <?php foreach ($schemas as $key => $schema): ?>
      <a class="admin-tile" href="edit.php?type=<?= e($key) ?>">
        <span class="admin-count"><?= $counts[$key] ?></span>
        <h3><?= e($schema['label']) ?></h3>
        <p>Manage entries</p>
      </a>
    <?php endforeach; ?>
    <a class="admin-tile" href="site.php">
      <span class="admin-count">⚙</span>
      <h3>Site &amp; hero</h3>
      <p>Name, contact details, hero videos</p>
    </a>
    <a class="admin-tile" href="messages.php">
      <span class="admin-count"><?= count($messages) ?></span>
      <h3>Messages</h3>
      <p>Contact-form enquiries</p>
    </a>
  </div>

  <div class="admin-card" style="margin-top:2rem;">
    <div class="admin-actions-row">
      <h2 style="margin:0;">Latest enquiries</h2>
      <a class="btn btn-ghost btn-sm" href="messages.php">View all ↗</a>
    </div>
    <?php if (!$recentMessages): ?>
      <p class="admin-empty">No enquiries yet.</p>
    <?php else: foreach ($recentMessages as $m): ?>
      <div class="admin-msg">
        <div class="admin-msg-meta">
          <strong><?= e($m['name']) ?></strong> · <?= e($m['email']) ?>
          <?= $m['company'] ? ' · ' . e($m['company']) : '' ?>
          · <?= e(date('j M Y, H:i', strtotime($m['date']) ?: time())) ?>
          <?php if ($m['mail_failed']): ?><span style="color:var(--error)"> · email delivery failed</span><?php endif; ?>
        </div>
        <div class="admin-msg-body"><?= e($m['body']) ?></div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
<?php admin_foot(); ?>
