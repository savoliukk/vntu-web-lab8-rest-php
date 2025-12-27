<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/http.php';
require_once __DIR__ . '/inc/helpers.php';

// ВАЖЛИВО: preview.php виконується всередині контейнера,
// тому json.php треба читати по внутрішньому порту Apache (80).
$fetchJsonUrl = 'http://127.0.0.1/json.php';

// А для відображення користувачу в UI — нормальний шлях
$displayJsonUrl = '/json.php';

$error = null;
$items = [];
$answerCols = [];

try {
  $raw = httpGet($fetchJsonUrl);
  $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

  // Збираємо всі можливі ключі answers, щоб побудувати колонки таблиці
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

require __DIR__ . '/templates/header.php';
?>

<h1>Preview анкет (з json.php)</h1>
<p class="muted">Джерело JSON: <?= h($displayJsonUrl) ?></p>

<?php if ($error): ?>
  <div class="alert err">Помилка: <?= h($error) ?></div>
<?php else: ?>
  <div class="alert ok">Анкет: <?= count($items) ?></div>

  <?php if (empty($items)): ?>
    <div class="alert err">Немає даних для відображення.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>name</th>
            <th>email</th>
            <th>submitted_at</th>
            <?php foreach ($answerCols as $c): ?>
              <th><?= h($c) ?></th>
            <?php endforeach; ?>
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
<?php endif; ?>

<?php
require __DIR__ . '/templates/footer.php';
