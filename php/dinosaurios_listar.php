<?php
header('Content-Type: application/json');
require __DIR__ . '/conexion.php';

if (!$cn || !($cn instanceof mysqli)) {
  echo json_encode([]); exit;
}

$res = $cn->query("SELECT id_dinosaurio AS id, especie FROM dinosaurio ORDER BY especie");
$out = [];

if ($res) {
  while ($row = $res->fetch_assoc()) { $out[] = $row; }
  $res->free();
}

echo json_encode($out);
