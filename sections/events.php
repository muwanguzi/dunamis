<section class="section events" id="events">
  <div class="wrap">
    <header class="section-head reveal">
      <p class="section-kicker">What's next</p>
      <h2 class="section-title">Upcoming<span class="accent-line">events.</span></h2>
      <p class="section-intro">
        Workshops, activations and industry meet-ups — announced here as soon as the dates are locked in.
      </p>
    </header>

    <?php if (!empty($EVENTS)): ?>
      <ul class="event-list">
        <?php foreach ($EVENTS as $ev): ?>
          <li class="event-row reveal">
            <div class="event-date">
              <span class="event-day"><?= e($ev['day']) ?></span>
              <span class="event-month"><?= e($ev['month']) ?></span>
            </div>
            <div class="event-body">
              <?php if (!empty($ev['tag'])): ?><span class="event-tag"><?= e($ev['tag']) ?></span><?php endif; ?>
              <h3 class="event-title"><?= e($ev['title']) ?></h3>
              <p class="event-meta">
                <?= e($ev['location'] ?? '') ?><?= !empty($ev['year']) ? ' · ' . e($ev['year']) : '' ?>
              </p>
              <?php if (!empty($ev['desc'])): ?><p class="event-desc"><?= e($ev['desc']) ?></p><?php endif; ?>
            </div>
            <?php if (!empty($ev['link'])): ?>
              <a class="event-link" href="<?= e($ev['link']) ?>">
                <?= e($ev['link_label'] ?? 'Learn more') ?> <span aria-hidden="true">→</span>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <div class="events-empty reveal">
        <p class="events-empty-title">Nothing on the calendar right now.</p>
        <p class="events-empty-text">
          We're heads-down on client work, but the next workshop, activation or industry
          meet-up will be announced here first.
        </p>
        <a class="btn btn-sm" href="#contact">Get notified <span aria-hidden="true">→</span></a>
      </div>
    <?php endif; ?>
  </div>
</section>
