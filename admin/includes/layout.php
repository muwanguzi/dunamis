<?php
declare(strict_types=1);

function admin_head(string $title): void {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> — Dunamis Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<?php
}

function admin_nav(string $active = ''): void {
    $links = [
        ''              => 'Dashboard',
        'site'          => 'Site & hero',
        'services'      => 'Services',
        'process'       => 'Process',
        'work'          => 'Work',
        'team'          => 'Team',
        'stats'         => 'Stats',
        'values'        => 'Values',
        'clients'       => 'Client logos',
        'testimonials'  => 'Testimonials',
        'events'        => 'Events',
        'messages'      => 'Messages',
    ];
    ?>
    <header class="admin-topbar">
      <div class="admin-topbar-inner">
        <a href="index.php" class="admin-brand">Dunamis Admin</a>
        <nav class="admin-nav" aria-label="Admin sections">
          <?php foreach ($links as $key => $label):
            $href = $key === '' ? 'index.php' : (in_array($key, ['services','process','work','team','stats','values','clients','testimonials','events'], true) ? "edit.php?type=$key" : "$key.php");
            $isActive = $key === $active;
          ?>
            <a href="<?= e($href) ?>" class="<?= $isActive ? 'is-active' : '' ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="admin-topbar-right">
          <a href="../index.php" target="_blank" rel="noopener">View site ↗</a>
          <a href="change-password.php">Account</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
    </header>
    <?php
}

function admin_flash(): void {
    $f = take_flash();
    if (!$f) return;
    printf('<div class="admin-flash admin-flash-%s">%s</div>', e($f['type']), e($f['message']));
}

function admin_foot(): void {
    echo "</body>\n</html>\n";
}
