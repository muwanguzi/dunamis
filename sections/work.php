<section class="section work" id="work">
  <div class="wrap">
    <header class="section-head reveal">
      <p class="section-kicker">Selected work</p>
      <h2 class="section-title">Brands we helped<br>get seen.</h2>
    </header>

    <div class="work-grid">
      <?php foreach ($WORK as $p): ?>
        <article class="work-card reveal" data-tint="<?= e($p['tint']) ?>">
          <div class="work-visual"<?= empty($p['img']) ? ' aria-hidden="true"' : '' ?>>
            <?php if (!empty($p['img'])): ?>
              <img class="work-img" src="<?= e($p['img']) ?>" alt="<?= e($p['title']) ?> — <?= e($p['kind']) ?>" loading="lazy" width="736" height="552">
            <?php else: ?>
              <span class="work-initial"><?= e(mb_substr($p['title'], 0, 1)) ?></span>
            <?php endif; ?>
          </div>
          <div class="work-meta">
            <div>
              <h3 class="work-title"><?= e($p['title']) ?></h3>
              <p class="work-kind"><?= e($p['kind']) ?></p>
            </div>
            <span class="work-arrow" aria-hidden="true">↗</span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
