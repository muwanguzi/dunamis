<section class="section services" id="services">
  <div class="wrap">
    <header class="section-head reveal">
      <p class="section-kicker">What we do</p>
      <h2 class="section-title">Seven capabilities.<span class="accent-line">One accountable team.</span></h2>
      <p class="section-intro">
        No hand-offs between agencies. From the first insight to the final placement,
        the same people own the outcome. Browse the list — the preview follows.
      </p>
    </header>

    <div class="service-gallery reveal">
      <ul class="service-list" role="listbox" aria-label="Our services" data-gallery-list>
        <?php foreach ($SERVICES as $i => $s): ?>
          <li class="service-item<?= $i === 0 ? ' is-active' : '' ?>"
              role="option"
              aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
              tabindex="<?= $i === 0 ? '0' : '-1' ?>"
              data-index="<?= (int) $i ?>">
            <span class="service-item-no"><?= e($s['no']) ?></span>
            <span class="service-item-name"><?= e($s['title']) ?></span>
            <span class="service-item-arrow" aria-hidden="true">↗</span>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="service-preview" data-gallery-preview>
        <?php foreach ($SERVICES as $i => $s): ?>
          <figure class="service-slide<?= $i === 0 ? ' is-active' : '' ?>" data-index="<?= (int) $i ?>" data-tint="<?= e($s['tint']) ?>">
            <?php if (!empty($s['img'])): ?>
              <img src="<?= e($s['img']) ?>" alt="" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" width="700" height="560">
            <?php endif; ?>
            <figcaption>
              <span class="service-slide-no"><?= e($s['no']) ?> / <?= sprintf('%02d', count($SERVICES)) ?></span>
              <h3><?= e($s['title']) ?></h3>
              <p><?= e($s['desc']) ?></p>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
