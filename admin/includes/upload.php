<?php
/**
 * Image upload handling. Every uploaded image is decoded and re-encoded with
 * GD before it touches disk — that strips any non-image payload smuggled
 * inside a file that merely *looks* like a jpg/png/webp, and normalises the
 * format/size at the same time. Uploads are capped and re-named, never kept
 * under the name the visitor's browser sent.
 */

declare(strict_types=1);

const UPLOAD_MAX_BYTES = 25 * 1024 * 1024; // 25MB in — real camera/phone JPEGs run 10-20MB; re-encoded output is far smaller
const UPLOAD_MAX_DIMENSION = 2000;         // longest side, px — plenty for this site
const UPLOAD_MAX_SOURCE_PIXELS = 60_000_000; // guards GD's memory use against a tiny file claiming huge pixel dimensions

/**
 * Handle one uploaded image field. Returns the new web-relative path
 * (e.g. "assets/img/work/xyz.jpg") on success, or null if the field was left
 * empty. Throws RuntimeException with a human-readable message on failure.
 */
function handle_image_upload(string $field, string $webDir): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed (error code ' . $file['error'] . ').');
    }
    if ($file['size'] > UPLOAD_MAX_BYTES) {
        $gotMb = round($file['size'] / 1024 / 1024, 1);
        $maxMb = (int) (UPLOAD_MAX_BYTES / 1024 / 1024);
        throw new RuntimeException("That image is {$gotMb}MB — please keep it under {$maxMb}MB.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Please upload a JPG, PNG or WEBP image.');
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('That file isn\'t a valid image.');
    }
    [$width, $height] = $info;
    if ($width * $height > UPLOAD_MAX_SOURCE_PIXELS) {
        throw new RuntimeException('That image\'s dimensions are too large — please use a smaller photo.');
    }

    $src = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($file['tmp_name']),
        'image/png'  => @imagecreatefrompng($file['tmp_name']),
        'image/webp' => @imagecreatefromwebp($file['tmp_name']),
    };
    if (!$src) {
        throw new RuntimeException('Could not read that image — try a different file.');
    }

    // Downscale if it's larger than we'd ever display.
    $longest = max($width, $height);
    if ($longest > UPLOAD_MAX_DIMENSION) {
        $scale = UPLOAD_MAX_DIMENSION / $longest;
        $newW = (int) round($width * $scale);
        $newH = (int) round($height * $scale);
        $resized = imagecreatetruecolor($newW, $newH);
        if ($mime !== 'image/jpeg') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($src);
        $src = $resized;
    }

    $projectRoot = __DIR__ . '/../..';
    $absDir = $projectRoot . '/' . trim($webDir, '/');
    if (!is_dir($absDir)) {
        mkdir($absDir, 0775, true);
    }
    $ext = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $absPath = $absDir . '/' . $name;

    $ok = match ($ext) {
        'jpg'  => imagejpeg($src, $absPath, 86),
        'png'  => imagepng($src, $absPath, 6),
        'webp' => imagewebp($src, $absPath, 86),
    };
    imagedestroy($src);
    if (!$ok) {
        throw new RuntimeException('Could not save that image — please try again.');
    }

    return trim($webDir, '/') . '/' . $name;
}

/** Delete a previously-stored upload, if it lives under one of our own upload dirs. */
function delete_upload(?string $webPath): void {
    if (!$webPath) return;
    $allowedPrefixes = ['assets/img/services/', 'assets/img/work/', 'assets/img/team/',
                         'assets/img/clients/', 'assets/img/events/'];
    $ok = false;
    foreach ($allowedPrefixes as $p) {
        if (str_starts_with($webPath, $p)) { $ok = true; break; }
    }
    if (!$ok) return; // never touch anything outside our own upload directories
    $abs = realpath(__DIR__ . '/../../' . $webPath);
    $root = realpath(__DIR__ . '/../..');
    if ($abs && $root && str_starts_with($abs, $root) && is_file($abs)) {
        @unlink($abs);
    }
}

/**
 * Hero video upload: MP4 only, larger size cap, no re-encoding (GD can't
 * touch video — we just validate type/size and store it under a safe name).
 */
function handle_video_upload(string $field): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed (error code ' . $file['error'] . ').');
    }
    $maxBytes = 40 * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('That video is too large — please keep it under 40MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ['video/mp4', 'video/quicktime'], true)) {
        throw new RuntimeException('Please upload an MP4 video.');
    }
    $projectRoot = __DIR__ . '/../..';
    $absDir = $projectRoot . '/assets/video';
    if (!is_dir($absDir)) mkdir($absDir, 0775, true);
    $name = 'hero-' . bin2hex(random_bytes(6)) . '.mp4';
    $absPath = $absDir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $absPath)) {
        throw new RuntimeException('Could not save that video — please try again.');
    }
    return 'assets/video/' . $name;
}
