<?php
require __DIR__ . '/php/funciones_sesion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$mensaje  = tomar_mensaje_flasheo();
$usuario_previo = $_SESSION['registro_usuario_temp'] ?? '';
unset($_SESSION['registro_usuario_temp']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="con-fondo">

<nav class="navbar jungle-header">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="login.php">Draftosaurus</a>
  </div>
</nav>

<main class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="card p-4" style="max-width:400px;width:100%;">
    <h2 class="mb-3">Crear cuenta</h2>

    <?php if ($mensaje): ?>
      <div class="alert <?= $mensaje['tipo']==='ok'?'alert-success':'alert-danger' ?> mb-3">
        <?= htmlspecialchars($mensaje['texto']) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="php/registro_procesar.php">
      <div class="mb-3 text-start">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" id="usuario" name="nombre_usuario" class="form-control"
               value="<?= htmlspecialchars($usuario_previo) ?>"
               required pattern="^[A-Za-z0-9_]{4,20}$" autocomplete="username">
      </div>

      <div class="mb-3 text-start">
        <label for="clave" class="form-label">Contraseña</label>
        <input type="password" id="clave" name="contrasena" class="form-control"
               required minlength="8" maxlength="64" autocomplete="new-password">
      </div>

      <div class="mb-3 text-start">
        <label for="clave2" class="form-label">Repetir contraseña</label>
        <input type="password" id="clave2" name="contrasena2" class="form-control"
               required minlength="8" maxlength="64" autocomplete="new-password">
      </div>

      <button type="submit" class="btn btn-safari fw-bold">Crear cuenta</button>
    </form>

    <p class="mt-3">¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a></p>
  </div>
</main>

<footer class="text-center text-white p-3 jungle-header">
  <small>Proyecto Draftosaurus © 2025</small>
</footer>

</body>
</html>
