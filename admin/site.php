<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require_admin();

$site = content_load('site');
$nav = content_load('nav');
$SOCIAL_ROWS = 4; // fixed number of editable rows — plenty for a small agency

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    csrf_check();
    $postAction = (string) ($_POST['action'] ?? '');

    if ($postAction === 'save_site') {
        try {
            $site['name']    = trim((string) ($_POST['name'] ?? ''));
            $site['legal']   = trim((string) ($_POST['legal'] ?? ''));
            $site['tagline'] = trim((string) ($_POST['tagline'] ?? ''));
            $site['email']   = trim((string) ($_POST['email'] ?? ''));
            $site['address'] = trim((string) ($_POST['address'] ?? ''));

            $phones = array_filter(array_map('trim', explode("\n", (string) ($_POST['phones'] ?? ''))));
            $site['phones'] = array_values($phones);

            $socials = [];
            for ($i = 0; $i < $SOCIAL_ROWS; $i++) {
                $label = trim((string) ($_POST["social_label_$i"] ?? ''));
                $handle = trim((string) ($_POST["social_handle_$i"] ?? ''));
                $url = trim((string) ($_POST["social_url_$i"] ?? ''));
                if ($label === '' && $url === '') continue;
                $socials[] = ['label' => $label, 'handle' => $handle, 'url' => $url];
            }
            $site['socials'] = $socials;

            $logo = handle_image_upload('logo', 'assets/img/brand');
            if ($logo) { $site['logo'] = $logo; }
            $wordmark = handle_image_upload('wordmark', 'assets/img/brand');
            if ($wordmark) { $site['wordmark'] = $wordmark; }

            content_save('site', $site);
            flash('ok', 'Site details saved.');
        } catch (RuntimeException $ex) {
            flash('error', $ex->getMessage());
        }
        header('Location: site.php');
        exit;
    }

    if ($postAction === 'save_nav') {
        foreach ($nav as $i => &$item) {
            $label = trim((string) ($_POST["nav_label_$i"] ?? ''));
            if ($label !== '') $item['label'] = $label;
        }
        unset($item);
        content_save('nav', $nav);
        flash('ok', 'Menu labels saved.');
        header('Location: site.php');
        exit;
    }

    if ($postAction === 'add_video') {
        try {
            $video = handle_video_upload('video');
            if ($video) {
                $site['hero_videos'][] = $video;
                content_save('site', $site);
                flash('ok', 'Video added to the hero playlist.');
            } else {
                flash('error', 'Choose an MP4 file first.');
            }
        } catch (RuntimeException $ex) {
            flash('error', $ex->getMessage());
        }
        header('Location: site.php');
        exit;
    }

    if ($postAction === 'remove_video') {
        $i = (int) ($_POST['i'] ?? -1);
        if (isset($site['hero_videos'][$i])) {
            if (count($site['hero_videos']) <= 1) {
                flash('error', 'The hero needs at least one video.');
            } else {
                array_splice($site['hero_videos'], $i, 1);
                content_save('site', $site);
                flash('ok', 'Video removed.');
            }
        }
        header('Location: site.php');
        exit;
    }

    if ($postAction === 'move_video') {
        $i = (int) ($_POST['i'] ?? -1);
        $j = ($_POST['dir'] ?? '') === 'up' ? $i - 1 : $i + 1;
        if (isset($site['hero_videos'][$i]) && isset($site['hero_videos'][$j])) {
            [$site['hero_videos'][$i], $site['hero_videos'][$j]] = [$site['hero_videos'][$j], $site['hero_videos'][$i]];
            content_save('site', $site);
        }
        header('Location: site.php');
        exit;
    }
}

admin_head('Site & hero');
admin_nav('site');
?>
<div class="admin-wrap">
  <?php admin_flash(); ?>
  <h1>Site details</h1>

  <div class="admin-card">
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_site">

      <div class="field-row">
        <div class="field"><label>Display name</label><input type="text" name="name" value="<?= e($site['name']) ?>" required></div>
        <div class="field"><label>Legal / full name</label><input type="text" name="legal" value="<?= e($site['legal']) ?>"></div>
      </div>
      <div class="field"><label>Tagline</label><input type="text" name="tagline" value="<?= e($site['tagline']) ?>"></div>
      <div class="field-row">
        <div class="field"><label>Contact email</label><input type="email" name="email" value="<?= e($site['email']) ?>" required></div>
        <div class="field">
          <label>Phone numbers</label>
          <textarea name="phones" placeholder="One per line"><?= e(implode("\n", $site['phones'] ?? [])) ?></textarea>
        </div>
      </div>
      <div class="field"><label>Address</label><input type="text" name="address" value="<?= e($site['address']) ?>"></div>

      <div class="field-row">
        <div class="field">
          <label>Logo</label>
          <div class="field-current-img"><img src="../<?= e($site['logo']) ?>" alt=""></div>
          <input type="file" name="logo" accept="image/jpeg,image/png,image/webp">
          <span class="hint">Square works best. Leave blank to keep the current logo.</span>
        </div>
        <div class="field">
          <label>Wordmark</label>
          <?php if (!empty($site['wordmark'])): ?><div class="field-current-img"><img src="../<?= e($site['wordmark']) ?>" alt=""></div><?php endif; ?>
          <input type="file" name="wordmark" accept="image/jpeg,image/png,image/webp">
        </div>
      </div>

      <h3 style="margin-top:1.5rem;">Social links</h3>
      <?php for ($i = 0; $i < $SOCIAL_ROWS; $i++): $s = $site['socials'][$i] ?? ['label' => '', 'handle' => '', 'url' => '']; ?>
        <div class="field-row" style="grid-template-columns: 1fr 1fr 1.4fr;">
          <div class="field"><label>Platform</label><input type="text" name="social_label_<?= $i ?>" value="<?= e($s['label']) ?>" placeholder="Instagram"></div>
          <div class="field"><label>Handle</label><input type="text" name="social_handle_<?= $i ?>" value="<?= e($s['handle'] ?? '') ?>" placeholder="@yourhandle"></div>
          <div class="field"><label>URL</label><input type="text" name="social_url_<?= $i ?>" value="<?= e($s['url']) ?>" placeholder="https://…"></div>
        </div>
      <?php endfor; ?>

      <button class="btn btn-primary" type="submit">Save site details</button>
    </form>
  </div>

  <h2>Menu labels</h2>
  <div class="admin-card">
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_nav">
      <div class="field-row">
        <?php foreach ($nav as $i => $item): ?>
          <div class="field"><label>Links to <?= e($item['href']) ?></label><input type="text" name="nav_label_<?= $i ?>" value="<?= e($item['label']) ?>"></div>
        <?php endforeach; ?>
      </div>
      <button class="btn btn-primary" type="submit">Save menu labels</button>
    </form>
  </div>

  <h2>Hero videos</h2>
  <div class="admin-card">
    <p class="admin-muted">Plays through in order, then loops. The site needs at least one.</p>
    <table class="admin-table">
      <tbody>
        <?php foreach ($site['hero_videos'] as $i => $v): ?>
          <tr>
            <td><?= e($v) ?></td>
            <td>
              <div class="admin-row-actions">
                <form method="post" style="display:inline;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="move_video">
                  <input type="hidden" name="i" value="<?= $i ?>">
                  <button class="btn btn-ghost btn-sm btn-icon" name="dir" value="up" <?= $i === 0 ? 'disabled' : '' ?>>↑</button>
                  <button class="btn btn-ghost btn-sm btn-icon" name="dir" value="down" <?= $i === count($site['hero_videos']) - 1 ? 'disabled' : '' ?>>↓</button>
                </form>
                <form method="post" style="display:inline;" onsubmit="return confirm('Remove this video from the playlist?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="remove_video">
                  <input type="hidden" name="i" value="<?= $i ?>">
                  <button class="btn btn-danger btn-sm">Remove</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <form method="post" enctype="multipart/form-data" style="margin-top:1.25rem;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add_video">
      <div class="field">
        <label>Add another clip</label>
        <input type="file" name="video" accept="video/mp4">
        <span class="hint">MP4, up to 40MB. A few seconds of footage is plenty — it loops.</span>
      </div>
      <button class="btn btn-primary" type="submit">Upload &amp; add</button>
    </form>
  </div>
</div>
<?php admin_foot(); ?>
