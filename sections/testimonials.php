<section class="section testimonials" aria-label="Client feedback">
  <div class="wrap">
    <header class="section-head reveal">
      <p class="section-kicker">In their words</p>
      <h2 class="section-title">Clients don't just<br>like it — they use it.</h2>
    </header>

    <ul class="quote-grid">
      <?php foreach ($TESTIMONIALS as $t): ?>
        <li class="quote-card reveal">
          <span class="quote-mark" aria-hidden="true">&ldquo;</span>
          <?php if (!empty($t['rating'])): ?>
            <span class="quote-stars" aria-label="<?= (int)$t['rating'] ?> out of 5">
              <?= str_repeat('★', (int)$t['rating']) . str_repeat('☆', 5 - (int)$t['rating']) ?>
            </span>
          <?php endif; ?>
          <blockquote><?= e($t['quote']) ?></blockquote>
          <p class="quote-by">
            <span class="quote-avatar" aria-hidden="true"><?= e(mb_substr($t['name'], 0, 1)) ?></span>
            <span><strong><?= e($t['name']) ?></strong> · <?= e($t['meta']) ?></span>
          </p>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
