<footer class="site-footer">
  <div class="wrap">
    <div class="footer-top">
      <a href="#top" class="footer-wordmark"><?= e($SITE['name']) ?></a>
      <p class="footer-tag"><?= e($SITE['tagline']) ?></p>
    </div>

    <div class="footer-cols">
      <div class="footer-col">
        <h4>Navigate</h4>
        <ul>
          <?php foreach ($NAV as $item): ?>
            <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Follow</h4>
        <ul>
          <?php foreach ($SITE['socials'] as $s): ?>
            <li><a href="<?= e($s['url']) ?>" target="_blank" rel="noopener"><?= e($s['label']) ?> <?= e($s['handle']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Reach us</h4>
        <ul>
          <li><a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a></li>
          <?php foreach ($SITE['phones'] as $p): ?>
            <li><a href="tel:<?= e(preg_replace('/\s+/', '', $p)) ?>"><?= e($p) ?></a></li>
          <?php endforeach; ?>
          <li><?= e($SITE['address']) ?></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <?= e($SITE['legal']) ?>. All rights reserved.</p>
      <p><a href="#top">Back to top ↑</a></p>
    </div>
  </div>
</footer>

<script src="assets/js/main.js?v=<?= (int) @filemtime(__DIR__ . '/../assets/js/main.js') ?>" defer></script>
</body>
</html>
