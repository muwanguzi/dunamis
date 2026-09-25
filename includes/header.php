<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="siteHeader">
  <div class="wrap header-inner">
    <a href="#top" class="brand" aria-label="<?= e($SITE['name']) ?> home">
      <img class="brand-logo" src="<?= e($SITE['logo']) ?>" alt="" width="40" height="40">
      <span class="brand-name"><?= e($SITE['name']) ?></span>
    </a>

    <nav class="nav" aria-label="Primary">
      <button class="theme-toggle" id="themeToggle" type="button" aria-pressed="false">
        <span class="theme-toggle-icon" aria-hidden="true">☾</span>
        <span class="sr-only">Toggle dark theme</span>
      </button>
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navMenu">
        <span></span><span></span><span></span>
        <span class="sr-only">Menu</span>
      </button>
      <ul class="nav-menu" id="navMenu">
        <?php foreach ($NAV as $item): ?>
          <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
        <li><a class="btn btn-sm" href="#contact">Start a project</a></li>
      </ul>
    </nav>
  </div>
</header>
