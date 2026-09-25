<section class="section studio" id="studio">
  <div class="wrap">
    <div class="studio-grid">
      <div class="studio-lead reveal">
        <p class="section-kicker">The studio</p>
        <h2 class="section-title">A dynamic media agency<span class="accent-line">with a bias for action.</span></h2>
        <p class="section-intro">
          We exist to amplify your message, grow engagement and move real numbers —
          combining cutting-edge media solutions with craft you can feel.
        </p>
        <a class="btn btn-ghost" href="#contact">Work with us</a>
      </div>

      <ul class="stat-list reveal">
        <?php foreach ($STATS as $st): ?>
          <li class="stat">
            <span class="stat-value" data-count="<?= e($st['value']) ?>"><?= e($st['value']) ?></span><span class="stat-suffix"><?= e($st['suffix']) ?></span>
            <span class="stat-label"><?= e($st['label']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="team-block">
      <h3 class="team-heading reveal">Who you'll work with</h3>
      <ul class="team-grid">
        <?php foreach ($TEAM as $m): ?>
          <li class="team-card reveal">
            <?php if (!empty($m['photo'])): ?>
              <img class="team-photo" src="<?= e($m['photo']) ?>" alt="<?= e($m['name']) ?>" loading="lazy" width="232" height="232">
            <?php else: ?>
              <span class="team-avatar" aria-hidden="true"><?= e(mb_substr($m['name'], 0, 1)) ?></span>
            <?php endif; ?>
            <h4 class="team-name"><?= e($m['name']) ?></h4>
            <p class="team-role"><?= e($m['role']) ?></p>
            <p class="team-bio"><?= e($m['bio']) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="values-block">
      <h3 class="team-heading reveal">What we hold ourselves to</h3>
      <ul class="values-list">
        <?php foreach ($VALUES as $v): ?>
          <li class="value-row reveal">
            <span class="value-label"><?= e($v['label']) ?></span>
            <span class="value-bar"><span class="value-fill" style="--to: <?= (int)$v['score'] ?>%"></span></span>
            <span class="value-score"><?= (int)$v['score'] ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
