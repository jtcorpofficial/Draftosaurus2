<?php
require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones_sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$usuario = trim($_POST['nombre_usuario'] ?? '');
$clave   = $_POST['contrasena'] ?? '';
$clave2  = $_POST['contrasena2'] ?? '';

if ($usuario === '' || $clave === '' || $clave2 === '') {
    poner_mensaje_flasheo('error', 'Usuario y contraseñas requeridos.');
    header('Location: ../registro.php'); exit;
}

if ($clave !== $clave2) {
    poner_mensaje_flasheo('error', 'Las contraseñas no coinciden.');
    header('Location: ../registro.php'); exit;
}

$hash = password_hash($clave, PASSWORD_DEFAULT);

$sql = $cn->prepare("SELECT COUNT(*) FROM usuario WHERE nombre_usuario=?");
$sql->bind_param("s", $usuario);
$sql->execute();
$sql->bind_result($existe);
$sql->fetch();
$sql->close();

if ($existe > 0) {
    poner_mensaje_flasheo('error', 'Ese nombre de usuario ya existe.');
    header('Location: ../registro.php'); exit;
}

$sql = $cn->prepare("INSERT INTO usuario (nombre_usuario, contrasena, rol) VALUES (?, ?, 'usuario')");
$sql->bind_param("ss", $usuario, $hash);
$sql->execute();
$id_nuevo = $sql->insert_id;
$sql->close();

iniciar_sesion_usuario($id_nuevo, $usuario, 'usuario');
poner_mensaje_flasheo('ok', 'Registro exitoso.');
header('Location: ../menu.php'); exit;
