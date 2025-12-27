<?php
declare(strict_types=1);

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Перетворює об'єкт/масив у масив рекурсивно */
function toArrayDeep(mixed $v): mixed {
  if (is_object($v)) $v = (array)$v;
  if (is_array($v)) {
    foreach ($v as $k => $vv) $v[$k] = toArrayDeep($vv);
  }
  return $v;
}

function isAssoc(array $a): bool {
  return array_keys($a) !== range(0, count($a) - 1);
}

/** Евристика: “запис людини” = асоціативний масив з переважно скалярними полями */
function looksLikePerson(array $row): bool {
  if (!isAssoc($row)) return false;
  $scalars = 0; $total = 0;
  foreach ($row as $v) {
    $total++;
    if (is_scalar($v) || $v === null) $scalars++;
  }
  return $total > 0 && ($scalars / $total) >= 0.7;
}

/** Збирає всі записи про людей у один масив */
function collectPeople(mixed $data): array {
  $out = [];
  if (is_array($data)) {
    if (looksLikePerson($data)) {
      $out[] = $data;
      return $out;
    }
    foreach ($data as $v) {
      $out = array_merge($out, collectPeople($v));
    }
  }
  return $out;
}
