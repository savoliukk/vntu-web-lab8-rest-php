<?php
declare(strict_types=1);

function buildUrl(string $baseUrl, array $query): string {
  // Важливо: @ у паролі буде закодовано як %40 автоматично
  $qs = http_build_query($query);
  return $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . $qs;
}

function httpGet(string $url, int $timeoutSec = 10): string {
  // 1) через curl_exec()
  if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_CONNECTTIMEOUT => $timeoutSec,
      CURLOPT_TIMEOUT => $timeoutSec,
    ]);
    $body = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($body === false) throw new RuntimeException("cURL error: $err");
    if ($code < 200 || $code >= 300) throw new RuntimeException("HTTP status $code");
    return (string)$body;
  }

  // 2) fallback через file_get_contents()
  $ctx = stream_context_create([
    'http' => [
      'method' => 'GET',
      'timeout' => $timeoutSec,
      'header' => "User-Agent: PHP\r\n",
    ]
  ]);
  $body = @file_get_contents($url, false, $ctx);
  if ($body === false) throw new RuntimeException("file_get_contents failed: $url");
  return (string)$body;
}
