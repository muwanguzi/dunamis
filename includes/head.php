<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
/* Set the theme before first paint so there's no light-then-dark flash. */
(function () {
  try {
    var saved = localStorage.getItem('dunamis-theme');
    var wantsDark = saved ? saved === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
    if (wantsDark) document.documentElement.setAttribute('data-theme', 'dark');
  } catch (e) {}
})();
</script>
<title><?= e($SITE['name']) ?> — <?= e($SITE['tagline']) ?></title>
<meta name="description" content="<?= e($SITE['legal']) ?> is a full-service marketing, branding and media agency: strategy, creative and media buying under one roof.">
<meta name="theme-color" content="#0e0e10">

<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($SITE['name']) ?> — <?= e($SITE['tagline']) ?>">
<meta property="og:description" content="Full-service marketing, branding and media. Strategy, creative and media in sync.">
<meta property="og:image" content="<?= e($SITE['logo']) ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css?v=<?= (int) @filemtime(__DIR__ . '/../assets/css/style.css') ?>">
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/brand/favicon-32.png">
<link rel="icon" type="image/png" sizes="192x192" href="assets/img/brand/favicon-192.png">
<link rel="apple-touch-icon" href="assets/img/brand/favicon-180.png">
</head>
<body>
