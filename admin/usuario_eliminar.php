<?php
require __DIR__ . '/../php/funciones_sesion.php';
require __DIR__ . '/../php/conexion.php';
exigir_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: usuarios.php');
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
  header('Location: usuarios.php');
  exit;
}

if ($id === (int)($_SESSION['id_usuario'] ?? 0)) {
  header('Location: usuarios.php');
  exit;
}

$sel = $cn->prepare("SELECT rol FROM usuario WHERE id_usuario=?");
$sel->bind_param("i", $id);
$sel->execute();
$sel->bind_result($rol);
$sel->fetch();
$sel->close();

if ($rol === 'admin') {
  header('Location: usuarios.php');
  exit;
}

$del = $cn->prepare("DELETE FROM usuario WHERE id_usuario=?");
$del->bind_param("i", $id);
$del->execute();
$del->close();

header('Location: usuarios.php');
exit;
