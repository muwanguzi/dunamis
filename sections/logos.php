<section class="logos" aria-label="Selected clients">
  <div class="wrap">
    <p class="logos-label reveal">Trusted by teams at</p>
  </div>
  <div class="logos-mask reveal">
    <div class="logos-track">
      <?php for ($r = 0; $r < 2; $r++): foreach ($CLIENTS as $c): ?>
        <img class="logo-img" src="<?= e($c['logo']) ?>" alt="<?= e($c['name']) ?>" loading="lazy" width="150" height="60">
      <?php endforeach; endfor; ?>
    </div>
  </div>
</section>
