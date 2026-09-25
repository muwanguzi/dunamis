# Dunamis Media — website redesign

A modern, single-page marketing site for **Dunamis Media Company Limited**, built with plain PHP
(no framework, no build step, no Composer dependencies).

## Run it locally

```bash
cd "Dunamis Website"
php -S localhost:8000
```

Then open <http://localhost:8000>.

## Structure

```
index.php            Page composition (includes head + header + sections + footer)
contact.php          Form handler — validates, emails, logs to storage/messages.log
includes/
  data.php           ALL site copy (services, team, values, testimonials, contact…)
  head.php           <head>, fonts, meta
  header.php          Sticky nav
  footer.php          Footer + script tag
sections/            One file per page section (hero, logos, services, work, studio,
                     testimonials, contact)
assets/
  css/style.css      Design system + layout (ink / paper / lime palette)
  js/main.js         Sticky header, mobile nav, scroll reveal, counters, AJAX form
storage/messages.log Contact submissions (created on first message)
```

## Editing content

Everything readable lives in `includes/data.php` — change the arrays, reload the page.
No templates need to be touched for copy updates.

## Contact form

- Submits via `fetch()` to `contact.php`; falls back to a normal POST + redirect if JS is off.
- Every valid message is appended to `storage/messages.log`.
- `mail()` is attempted best-effort. On a real host, point `RECIPIENT` in `contact.php`
  at the inbox you want and configure SMTP / a mail transport.
- Spam is filtered with a hidden honeypot field.

## Deploy

Upload the folder to any PHP 8+ host (shared hosting, a VPS with `php-fpm` + nginx, etc.).
Make sure `storage/` is writable by the web server. No database required.

## Design notes

- Layout benchmarked against alsayegh.com: full-bleed video hero with a sound
  toggle and an "explore services" scroll cue, transparent nav over the hero that
  turns solid once you scroll past it, then Services → Clients → Work → Studio →
  Testimonials → Contact with generous whitespace.
- Typography: Space Grotesk (display) + Inter (body), loaded from Google Fonts.
- **Brand palette** (kept from the Dunamis logo): orange `#f5911e` accent, dark slate
  `#1b2429` ink, warm paper `#faf7f2`. Teal `#0f7d92` is used only for focus rings so it
  never competes with the orange.
- Motion respects `prefers-reduced-motion`.

## Images

Real assets pulled from the current site live in `assets/img/`:

- `brand/` — logo + favicons
- `clients/` — the 10 client logos (shown greyscale, colour on hover)
- `team/` — the three director photos (small round avatars)
- `photos/` — back the 3 image-led service tiles, portfolio thumbnails and the hero poster

Service tiles and portfolio cards without a photo fall back to a branded gradient
(`tint` a–e). Add real imagery by setting an `img` path on the entry in `$SERVICES`
or `$WORK` (`includes/data.php`) — the dark scrim and centred label handle the rest.

## Hero video

`assets/video/` holds the hero background clip — see `assets/video/README.md`. Drop in
`hero.mp4` (and optionally `hero.webm`) and it plays automatically, muted, looping;
the button bottom-right unmutes it. With no file present the hero shows `hero_poster`
from `includes/data.php`.

> Assets from other agencies' sites (alsayegh.com included) were **not** copied in —
> that site was used only as a layout reference. Use Dunamis's own footage/photography
> or licensed stock.
