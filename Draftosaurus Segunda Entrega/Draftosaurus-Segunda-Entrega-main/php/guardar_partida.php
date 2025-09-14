<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require __DIR__ . '/conexion.php';
if (!$cn || !($cn instanceof mysqli)) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'msg'=>'Sin conexión']); exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw,true);

$usuario_id = (int)($data['usuario_id'] ?? ($_SESSION['id_usuario'] ?? 0));
$puntos     = (int)($data['puntos'] ?? 0);

if ($usuario_id <= 0) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'msg'=>'usuario_id inválido']); exit;
}

$cn->begin_transaction();

$sql1 = $cn->prepare("INSERT INTO partida (usuario_id, estado, fecha_hora) VALUES (?, 'finalizada', NOW())");
$sql1->bind_param("i",$usuario_id);
if(!$sql1->execute()){ $cn->rollback(); http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error partida']); exit; }
$partida_id = $cn->insert_id;
$sql1->close();

$sql2 = $cn->prepare("INSERT INTO tablero (partida_id, usuario_id, puntaje) VALUES (?,?,?)");
$sql2->bind_param("iii",$partida_id,$usuario_id,$puntos);
if(!$sql2->execute()){ $cn->rollback(); http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error tablero']); exit; }
$tablero_id = $cn->insert_id;
$sql2->close();

$cn->commit();
echo json_encode(['ok'=>true,'partida_id'=>$partida_id,'tablero_id'=>$tablero_id]);
