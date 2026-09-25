<section class="section process" aria-label="How we work">
  <div class="wrap">
    <header class="section-head reveal">
      <p class="section-kicker">How we work</p>
      <h2 class="section-title">Four steps.<br>No hand-offs.</h2>
      <p class="section-intro">The same team carries a project from the first conversation to the report after launch.</p>
    </header>

    <ol class="process-list">
      <?php foreach ($PROCESS as $i => $s): ?>
        <li class="process-step reveal">
          <span class="process-no"><?= e($s['no']) ?></span>
          <div class="process-body">
            <h3 class="process-title"><?= e($s['title']) ?></h3>
            <p class="process-desc"><?= e($s['desc']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
