<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
  // на майбутнє: сесії тут не обов'язкові, але не завадять
  // session_start();
}

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

function navActive(string $href, string $path): string {
  // активний пункт: якщо шлях співпадає точно
  return ($href === $path) ? 'active' : '';
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ЛР8 — REST + JSON</title>
  <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <div class="brand">ЛР8</div>

      <nav class="nav">
        <a class="<?= navActive('/index.php', $path) ?>" href="/index.php">Головна</a>
        <a class="<?= navActive('/json.php', $path) ?>" href="/json.php">JSON</a>
        <a class="<?= navActive('/preview.php', $path) ?>" href="/preview.php">Preview</a>
      </nav>
    </div>
  </header>

  <main class="wrap">
