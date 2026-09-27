<?php
/**
 * Generic list + add/edit/delete/reorder screen for every simple collection
 * (services, work, team, stats, values, clients, testimonials, events,
 * process) — driven entirely by the field schema in includes/store.php.
 */
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require_admin();

$schemas = content_schemas();
$type = (string) ($_GET['type'] ?? '');
if (!isset($schemas[$type])) {
    http_response_code(404);
    exit('Unknown section.');
}
$schema = $schemas[$type];
$items = content_load($type);
$action = (string) ($_GET['action'] ?? 'list');
$index = isset($_GET['i']) ? (int) $_GET['i'] : null;

function renumber(array $items, array $schema): array {
    if (empty($schema['auto_number'])) return $items;
    foreach ($items as $i => &$row) {
        $row['no'] = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
    }
    return $items;
}

/* ---- handle writes ---- */
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    csrf_check();
    $postAction = (string) ($_POST['action'] ?? '');

    if ($postAction === 'save') {
        $i = isset($_POST['i']) && $_POST['i'] !== '' ? (int) $_POST['i'] : null;
        $row = $i !== null && isset($items[$i]) ? $items[$i] : [];
        try {
            foreach ($schema['fields'] as $name => $def) {
                if ($def['type'] === 'image') {
                    $uploaded = handle_image_upload($name, $def['dir']);
                    if ($uploaded !== null) {
                        delete_upload($row[$name] ?? null);
                        $row[$name] = $uploaded;
                    } elseif (!empty($_POST['remove_' . $name])) {
                        delete_upload($row[$name] ?? null);
                        $row[$name] = null;
                    }
                    continue;
                }
                $val = trim((string) ($_POST[$name] ?? ''));
                if ($def['type'] === 'number') {
                    $row[$name] = $val === '' ? null : (int) $val;
                } else {
                    $row[$name] = $val;
                }
            }
            if (!empty($schema['auto_number'])) $row['no'] = '00'; // placeholder, fixed by renumber()
            if ($i !== null) { $items[$i] = $row; } else { $items[] = $row; }
            $items = renumber($items, $schema);
            content_save($type, $items);
            flash('ok', $i !== null ? 'Saved.' : 'Added.');
        } catch (RuntimeException $ex) {
            flash('error', $ex->getMessage());
        }
        header('Location: edit.php?type=' . urlencode($type));
        exit;
    }

    if ($postAction === 'delete') {
        $i = (int) ($_POST['i'] ?? -1);
        if (isset($items[$i])) {
            foreach ($schema['fields'] as $name => $def) {
                if ($def['type'] === 'image' && !empty($items[$i][$name])) {
                    delete_upload($items[$i][$name]);
                }
            }
            array_splice($items, $i, 1);
            $items = renumber($items, $schema);
            content_save($type, $items);
            flash('ok', 'Deleted.');
        }
        header('Location: edit.php?type=' . urlencode($type));
        exit;
    }

    if ($postAction === 'move') {
        $i = (int) ($_POST['i'] ?? -1);
        $dir = (string) ($_POST['dir'] ?? '');
        $j = $dir === 'up' ? $i - 1 : $i + 1;
        if (isset($items[$i]) && isset($items[$j])) {
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
            $items = renumber($items, $schema);
            content_save($type, $items);
        }
        header('Location: edit.php?type=' . urlencode($type));
        exit;
    }
}

admin_head($schema['label']);
admin_nav($type);
?>
<div class="admin-wrap">
  <?php admin_flash(); ?>

  <?php if ($action === 'form'): ?>
    <?php
      $editing = $index !== null && isset($items[$index]);
      $row = $editing ? $items[$index] : [];
    ?>
    <div class="admin-actions-row">
      <h1><?= $editing ? 'Edit' : 'Add' ?> — <?= e($schema['label']) ?></h1>
      <a class="btn btn-ghost btn-sm" href="edit.php?type=<?= e($type) ?>">← Back to list</a>
    </div>
    <div class="admin-card">
      <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <?php if ($editing): ?><input type="hidden" name="i" value="<?= (int) $index ?>"><?php endif; ?>

        <?php foreach ($schema['fields'] as $name => $def): ?>
          <div class="field">
            <label for="f_<?= e($name) ?>"><?= e($def['label']) ?><?= !empty($def['required']) ? ' *' : '' ?></label>
            <?php if ($def['type'] === 'textarea'): ?>
              <textarea id="f_<?= e($name) ?>" name="<?= e($name) ?>"><?= e((string) ($row[$name] ?? '')) ?></textarea>
            <?php elseif ($def['type'] === 'select'): ?>
              <select id="f_<?= e($name) ?>" name="<?= e($name) ?>">
                <?php foreach ($def['options'] as $val => $optLabel): ?>
                  <option value="<?= e($val) ?>" <?= ($row[$name] ?? '') === $val ? 'selected' : '' ?>><?= e($optLabel) ?></option>
                <?php endforeach; ?>
              </select>
            <?php elseif ($def['type'] === 'number'): ?>
              <input type="number" id="f_<?= e($name) ?>" name="<?= e($name) ?>"
                     value="<?= e((string) ($row[$name] ?? '')) ?>"
                     <?= isset($def['min']) ? 'min="' . (int) $def['min'] . '"' : '' ?>
                     <?= isset($def['max']) ? 'max="' . (int) $def['max'] . '"' : '' ?>>
            <?php elseif ($def['type'] === 'image'): ?>
              <?php if (!empty($row[$name])): ?>
                <div class="field-current-img">
                  <img src="../<?= e($row[$name]) ?>" alt="">
                  <label style="font-weight:400;"><input type="checkbox" name="remove_<?= e($name) ?>" value="1"> Remove current image</label>
                </div>
              <?php endif; ?>
              <input type="file" id="f_<?= e($name) ?>" name="<?= e($name) ?>" accept="image/jpeg,image/png,image/webp">
              <span class="hint">JPG, PNG or WEBP, up to 8MB. Leave blank to keep the current image.</span>
            <?php else: ?>
              <input type="text" id="f_<?= e($name) ?>" name="<?= e($name) ?>"
                     value="<?= e((string) ($row[$name] ?? '')) ?>"
                     placeholder="<?= e($def['placeholder'] ?? '') ?>"
                     <?= !empty($def['required']) ? 'required' : '' ?>>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-ghost" href="edit.php?type=<?= e($type) ?>">Cancel</a>
      </form>
    </div>

  <?php else: ?>
    <div class="admin-actions-row">
      <h1><?= e($schema['label']) ?></h1>
      <a class="btn btn-primary" href="edit.php?type=<?= e($type) ?>&action=form">+ Add new</a>
    </div>
    <div class="admin-card">
      <?php if (!$items): ?>
        <p class="admin-empty">Nothing here yet.</p>
      <?php else: ?>
        <table class="admin-table">
          <thead><tr>
            <th></th><th><?= e($schema['title_field']) ?></th><th></th>
          </tr></thead>
          <tbody>
            <?php foreach ($items as $i => $row):
              $imgField = null;
              foreach ($schema['fields'] as $name => $def) { if ($def['type'] === 'image') { $imgField = $name; break; } }
            ?>
              <tr>
                <td><?php if ($imgField && !empty($row[$imgField])): ?><img class="admin-thumb" src="../<?= e($row[$imgField]) ?>" alt=""><?php endif; ?></td>
                <td><?= e((string) ($row[$schema['title_field']] ?? '')) ?></td>
                <td>
                  <div class="admin-row-actions">
                    <form method="post" style="display:inline;">
                      <?= csrf_field() ?>
                      <input type="hidden" name="action" value="move">
                      <input type="hidden" name="i" value="<?= $i ?>">
                      <button class="btn btn-ghost btn-sm btn-icon" name="dir" value="up" <?= $i === 0 ? 'disabled' : '' ?> title="Move up">↑</button>
                      <button class="btn btn-ghost btn-sm btn-icon" name="dir" value="down" <?= $i === count($items) - 1 ? 'disabled' : '' ?> title="Move down">↓</button>
                    </form>
                    <a class="btn btn-ghost btn-sm" href="edit.php?type=<?= e($type) ?>&action=form&i=<?= $i ?>">Edit</a>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this entry? This can\'t be undone.');">
                      <?= csrf_field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="i" value="<?= $i ?>">
                      <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
<?php admin_foot(); ?>
