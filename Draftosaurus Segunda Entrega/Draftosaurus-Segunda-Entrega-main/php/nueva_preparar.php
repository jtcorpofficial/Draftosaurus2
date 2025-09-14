<?php
require __DIR__.'/funciones_sesion.php';
if (session_status() === PHP_SESSION_NONE) session_start();
exigir_login();

$bots = isset($_POST['bots']) ? (int)$_POST['bots'] : 0;
if (!in_array($bots, [1,2], true)) {
  poner_mensaje_flasheo('error', 'Cantidad de bots inválida.');
  header('Location: ../nueva.php'); exit;
}

$nombreHumano = $_SESSION['nombre_usuario'] ?? 'Jugador';

$jugadores = [];
$jugadores[] = ['nombre'=>$nombreHumano, 'puntos'=>0, 'es_bot'=>0];
for ($i=1; $i<=$bots; $i++) {
  $jugadores[] = ['nombre'=>"Bot $i", 'puntos'=>0, 'es_bot'=>1];
}

$_SESSION['partida'] = [
  'jugadores'  => $jugadores,
  'creada_en'  => date('c'),
  'rondas'     => 2,    
];

header('Location: ../tablero.php'); exit;
