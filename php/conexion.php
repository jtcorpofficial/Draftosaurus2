<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'draftosaurus';

$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
  if ($user) {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
  } else {
    try {
      $conn = new mysqli($host, 'draft_app', 'CambiaEstaPass_2025!', $db);
      $conn->set_charset('utf8mb4');
    } catch (mysqli_sql_exception $e) {
      $conn = new mysqli($host, 'root', '', $db);
      $conn->set_charset('utf8mb4');
    }
  }
} catch (mysqli_sql_exception $e) {
  http_response_code(500);
  die('Error de conexión a MySQL');
}

$cn = $conn;
