<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/http.php';
require_once __DIR__ . '/inc/helpers.php';

$baseUrl = 'http://lab.vntu.org/api-server/lab8.php';
$url = buildUrl($baseUrl, ['user' => 'student', 'pass' => 'p@ssw0rd']);

$error = null;
$people = [];
$cols = [];

try {
  $json = httpGet($url);

  // Вимога: json_decode у об’єкти PHP
  $obj = json_decode($json, false, 512, JSON_THROW_ON_ERROR);

  // для обробки перетворюємо в масиви
  $data = toArrayDeep($obj);

  // Об’єднати всі записи про людей в один масив
  $people = collectPeople($data);

  // Динамічно зберемо колонки
  $set = [];
  foreach ($people as $p) foreach ($p as $k => $_) $set[$k] = true;
  $cols = array_keys($set);
} catch (Throwable $e) {
  $error = $e->getMessage();
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
  <div class="wrap">
    <h1>ЛР №8 — REST API → JSON → Таблиця</h1>
    <p class="muted">URL: <?= h($url) ?></p>

    <?php if ($error): ?>
      <div class="alert err">Помилка: <?= h($error) ?></div>
    <?php else: ?>
      <div class="alert ok">Знайдено записів: <?= count($people) ?></div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <?php foreach ($cols as $c): ?><th><?= h((string)$c) ?></th><?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($people as $p): ?>
              <tr>
                <?php foreach ($cols as $c): ?>
                  <td>
                    <?php
                      $v = $p[$c] ?? '';
                      if (is_array($v)) $v = json_encode($v, JSON_UNESCAPED_UNICODE);
                      echo h((string)$v);
                    ?>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
