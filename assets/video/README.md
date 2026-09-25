# Hero background video

The hero plays through a **playlist** of clips, in order, looping back to the
first once the last one ends. The list lives in `includes/data.php`:

```php
'hero_videos' => [
    'assets/video/hero-1.mp4',
    'assets/video/hero-2.mp4',
],
```

Add or remove clips by editing that array — drop the file in this folder and
add its path to the list (any length works, one clip is fine too).

- Format: H.264 / AAC MP4, ~1080p, keep each clip a few MB so it loads fast
- The `<video>` starts `autoplay muted playsinline` (browsers block sound until a click); the "Sound on" pill bottom-right
  unmutes it (natural speed, audio on) and the state carries over between clips. Muted, the picture is slowed to 0.4x
- Until at least one file is present, the hero falls back to the poster image
  set in `includes/data.php` (`hero_poster`)

Do **not** drop in footage you don't have the rights to (including clips
lifted from other agencies' sites) — use Dunamis's own production work or
properly licensed stock.
