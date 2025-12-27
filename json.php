<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/db.php';

header('Content-Type: application/json; charset=utf-8');

$pdo = db();

// Під твій формат анкети ЛР6 (можеш змінити список q* якщо інший)
$sql = "SELECT submitted_at, name, email, q1_lang, q2_hours, q3_ai, q4_hard, q5_wish
        FROM survey_responses
        ORDER BY submitted_at DESC";

$rows = $pdo->query($sql)->fetchAll();

$out = [];
foreach ($rows as $r) {
  $out[] = [
    'name' => (string)$r['name'],
    'email' => (string)$r['email'],
    'submitted_at' => (string)$r['submitted_at'],
    'answers' => [
      'q1_lang' => (string)$r['q1_lang'],
      'q2_hours' => (string)$r['q2_hours'],
      'q3_ai' => (string)$r['q3_ai'],
      'q4_hard' => (string)$r['q4_hard'],
      'q5_wish' => (string)$r['q5_wish'],
    ],
  ];
}

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);