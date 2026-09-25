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
- The `<video>` is `autoplay muted playsinline`; the round button bottom-right
  unmutes it and the mute state carries over between clips
- Until at least one file is present, the hero falls back to the poster image
  set in `includes/data.php` (`hero_poster`)

Do **not** drop in footage you don't have the rights to (including clips
lifted from other agencies' sites) — use Dunamis's own production work or
properly licensed stock.
