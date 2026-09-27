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

/**
 * Sidebar nav, grouped by kind, plus the main-content wrapper it opens.
 * admin_foot() closes what this opens — keep the two in sync.
 */
function admin_nav(string $active = ''): void {
    $collectionHref = fn(string $key) => "edit.php?type=$key";
    $groups = [
        'Overview' => [
            '' => ['Dashboard', 'index.php'],
        ],
        'Content' => [
            'services'     => ['Services', $collectionHref('services')],
            'process'      => ['Process', $collectionHref('process')],
            'work'         => ['Work', $collectionHref('work')],
            'team'         => ['Team', $collectionHref('team')],
            'stats'        => ['Stats', $collectionHref('stats')],
            'values'       => ['Values', $collectionHref('values')],
            'clients'      => ['Client logos', $collectionHref('clients')],
            'testimonials' => ['Testimonials', $collectionHref('testimonials')],
            'events'       => ['Events', $collectionHref('events')],
        ],
        'Site' => [
            'site' => ['Site & hero', 'site.php'],
        ],
        'Inbox' => [
            'messages' => ['Messages', 'messages.php'],
        ],
    ];
    ?>
    <div class="admin-mobile-bar">
      <button type="button" class="admin-burger" id="adminBurger" aria-label="Menu" aria-expanded="false" aria-controls="adminSidebar">
        <span></span><span></span><span></span>
      </button>
      <a href="index.php" class="admin-brand">Dunamis Admin</a>
    </div>
    <div class="admin-shell">
      <div class="admin-sidebar-veil" id="adminVeil"></div>
      <aside class="admin-sidebar" id="adminSidebar">
        <a href="index.php" class="admin-sidebar-brand">Dunamis Admin</a>
        <nav aria-label="Admin sections">
          <?php foreach ($groups as $groupLabel => $items): ?>
            <div class="admin-nav-group">
              <h4><?= e($groupLabel) ?></h4>
              <?php foreach ($items as $key => [$label, $href]): ?>
                <a href="<?= e($href) ?>" class="<?= $key === $active ? 'is-active' : '' ?>"><?= e($label) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar-foot">
          <a href="../index.php" target="_blank" rel="noopener">View site ↗</a>
          <a href="change-password.php">Account</a>
          <a href="logout.php">Log out</a>
        </div>
      </aside>
      <main class="admin-main">
    <?php
    $GLOBALS['__admin_shell_open'] = true;
}

function admin_flash(): void {
    $f = take_flash();
    if (!$f) return;
    printf('<div class="admin-flash admin-flash-%s">%s</div>', e($f['type']), e($f['message']));
}

function admin_foot(): void {
    if (!empty($GLOBALS['__admin_shell_open'])) {
        echo '</main></div>';
        ?>
        <script>
        (function () {
          var burger = document.getElementById('adminBurger');
          var sidebar = document.getElementById('adminSidebar');
          var veil = document.getElementById('adminVeil');
          if (!burger || !sidebar || !veil) return;
          var setOpen = function (open) {
            sidebar.classList.toggle('is-open', open);
            veil.classList.toggle('is-open', open);
            burger.setAttribute('aria-expanded', String(open));
          };
          burger.addEventListener('click', function () { setOpen(!sidebar.classList.contains('is-open')); });
          veil.addEventListener('click', function () { setOpen(false); });
        })();
        </script>
        <?php
    }
    echo "</body>\n</html>\n";
}
