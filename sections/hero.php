<section class="hero" id="top">
  <div class="hero-media" aria-hidden="true">
    <video class="hero-video" id="heroVideo" autoplay muted playsinline preload="metadata"
           src="<?= e($SITE['hero_videos'][0]) ?>"
           data-playlist='<?= e(json_encode($SITE['hero_videos'], JSON_UNESCAPED_SLASHES)) ?>'>
    </video>
    <div class="hero-scrim"></div>
  </div>

  <div class="wrap hero-content">
    <p class="hero-eyebrow reveal">Full-service media agency · Kampala</p>

    <h1 class="hero-title reveal">
      We build <span class="accent-word">brands</span> people remember.
    </h1>

    <p class="hero-lede reveal">
      <?= e($SITE['legal']) ?> — strategy, creative and media buying working as one
      team, for corporates, government, NGOs and the ambitious.
    </p>

    <div class="hero-actions reveal">
      <a class="btn btn-lg" href="#contact">Start a project <span aria-hidden="true">↗</span></a>
      <a class="btn btn-ghost btn-lg" href="#work">See the work <span aria-hidden="true">↗</span></a>
    </div>
  </div>

  <p class="hero-pill"><span aria-hidden="true">●</span> Kira Road · Kampala, Uganda</p>

  <button class="hero-mute" id="heroMute" type="button" aria-pressed="false" aria-label="Turn video sound on">
    <span class="hero-mute-icon" aria-hidden="true">🔇</span>
    <span class="hero-mute-label">Sound on</span>
  </button>

  <a class="hero-explore" href="#services">
    <span>Explore services</span>
    <span class="hero-explore-arrow" aria-hidden="true">↓</span>
  </a>
</section>
