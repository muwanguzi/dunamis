<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="#top" class="footer-logo" aria-label="<?= e($SITE['name']) ?> — back to top">
          <img src="<?= e($SITE['logo']) ?>" alt="" width="56" height="56">
          <span><?= e($SITE['name']) ?></span>
        </a>
        <p class="footer-tag"><?= e($SITE['tagline']) ?></p>
        <a class="btn btn-sm footer-cta" href="#contact">Start a project <span aria-hidden="true">↗</span></a>
      </div>

      <nav class="footer-col" aria-label="Footer">
        <h4>Explore</h4>
        <ul>
          <?php foreach ($NAV as $item): ?>
            <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="footer-col">
        <h4>Follow</h4>
        <ul>
          <?php foreach ($SITE['socials'] as $s): ?>
            <li><a href="<?= e($s['url']) ?>" target="_blank" rel="noopener"><?= e($s['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col footer-contact">
        <h4>Contact</h4>
        <ul>
          <li><a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a></li>
          <?php foreach ($SITE['phones'] as $p): ?>
            <li><a href="tel:<?= e(preg_replace('/\s+/', '', $p)) ?>"><?= e($p) ?></a></li>
          <?php endforeach; ?>
          <li class="footer-address"><?= e($SITE['address']) ?></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <?= e($SITE['legal']) ?></p>
      <a href="#top">Back to top ↑</a>
    </div>
  </div>
</footer>

<script src="assets/js/main.js?v=<?= (int) @filemtime(__DIR__ . '/../assets/js/main.js') ?>" defer></script>
</body>
</html>
