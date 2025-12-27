<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/http.php';
require_once __DIR__ . '/inc/helpers.php';

$jsonUrl = 'http://127.0.0.1/json.php';

$error = null;
$items = [];
$answerCols = [];

try {
  $raw = httpGet($jsonUrl);
  $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

  $set = [];
  foreach ($items as $it) {
    if (isset($it['answers']) && is_array($it['answers'])) {
      foreach ($it['answers'] as $k => $_) $set[$k] = true;
    }
  }
  $answerCols = array_keys($set);
} catch (Throwable $e) {
  $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ЛР8 — preview.php</title>
  <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<div class="wrap">
  <h1>Preview анкет (з json.php)</h1>
  <p class="muted">Джерело: <?= h($jsonUrl) ?></p>

  <?php if ($error): ?>
    <div class="alert err">Помилка: <?= h($error) ?></div>
  <?php else: ?>
    <div class="alert ok">Анкет: <?= count($items) ?></div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>name</th><th>email</th><th>submitted_at</th>
            <?php foreach ($answerCols as $c): ?><th><?= h($c) ?></th><?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= h((string)($it['name'] ?? '')) ?></td>
              <td><?= h((string)($it['email'] ?? '')) ?></td>
              <td><?= h((string)($it['submitted_at'] ?? '')) ?></td>
              <?php foreach ($answerCols as $c): ?>
                <td><?= h((string)($it['answers'][$c] ?? '')) ?></td>
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
