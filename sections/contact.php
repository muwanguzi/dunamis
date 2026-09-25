<section class="section contact" id="contact">
  <div class="wrap">
    <div class="contact-intro reveal">
      <span class="contact-bar" aria-hidden="true"></span>
      <h2 class="contact-title">Make it seen.<span class="accent-line">Make it matter.</span></h2>
      <p class="section-intro">Tell us what you're launching — we'll come back within one working day.</p>
    </div>

    <div class="contact-grid">
      <form class="contact-form reveal" id="contactForm" action="contact.php" method="post" novalidate>
        <div class="field">
          <label for="cf-name">Name</label>
          <input type="text" id="cf-name" name="name" autocomplete="name" required>
        </div>
        <div class="field">
          <label for="cf-email">Email address</label>
          <input type="email" id="cf-email" name="email" autocomplete="email" required>
        </div>
        <div class="field">
          <label for="cf-company">Company name</label>
          <input type="text" id="cf-company" name="company" autocomplete="organization">
        </div>
        <div class="field">
          <label for="cf-message">Message</label>
          <textarea id="cf-message" name="message" rows="4" required></textarea>
        </div>
        <!-- honeypot -->
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <button type="submit" class="btn btn-lg" id="cf-submit">Send</button>
        <p class="form-status" id="cf-status" role="status" aria-live="polite"></p>
      </form>

      <div class="contact-details reveal">
        <div class="contact-row">
          <span>Visit</span>
          <p><?= e($SITE['address']) ?></p>
        </div>
        <div class="contact-row">
          <span>Email</span>
          <a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a>
        </div>
        <div class="contact-row">
          <span>Phone</span>
          <p>
            <?php foreach ($SITE['phones'] as $i => $p): ?>
              <a href="tel:<?= e(preg_replace('/\s+/', '', $p)) ?>"><?= e($p) ?></a><?= $i === 0 ? '<br>' : '' ?>
            <?php endforeach; ?>
          </p>
        </div>
        <div class="contact-row">
          <span>Follow</span>
          <div class="contact-socials">
            <?php foreach ($SITE['socials'] as $s): ?>
              <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener"><?= e($s['label']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
