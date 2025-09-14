<?php
require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones_sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$usuario = trim($_POST['nombre_usuario'] ?? '');
$clave   = $_POST['contrasena'] ?? '';

if ($usuario === '' || $clave === '') {
    poner_mensaje_flasheo('error', 'Usuario y contraseña requeridos.');
    header('Location: ../login.php'); exit;
}

$sql = $cn->prepare("SELECT id_usuario, nombre_usuario, contrasena, rol
                     FROM usuario
                     WHERE nombre_usuario = ?");
$sql->bind_param("s", $usuario);
$sql->execute();
$res = $sql->get_result();
$fila = $res->fetch_assoc();
$sql->close();

if (!$fila) {
    poner_mensaje_flasheo('error', 'Usuario no encontrado.');
    header('Location: ../login.php'); exit;
}

if (!password_verify($clave, $fila['contrasena'])) {
    poner_mensaje_flasheo('error', 'Contraseña incorrecta.');
    header('Location: ../login.php'); exit;
}

iniciar_sesion_usuario((int)$fila['id_usuario'], $fila['nombre_usuario'], $fila['rol']);
poner_mensaje_flasheo('ok', 'Bienvenido ' . $fila['nombre_usuario']);
header('Location: ../menu.php'); exit;
