<?php
require __DIR__ . '/../php/funciones_sesion.php';
require __DIR__ . '/../php/conexion.php';
exigir_admin();

$consulta = $cn->query("SELECT id_usuario, nombre_usuario, rol FROM usuario ORDER BY id_usuario ASC");
$usuarios = $consulta ? $consulta->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de administración</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
  <style>
     body {
      margin: 0;
      font-family: 'Fredoka', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
     body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url('../img/fondo.png') no-repeat center center / cover;
      filter: blur(8px);  /* nivel de desenfoque */
      transform: scale(1.1); /* para evitar bordes vacíos al desenfocar */
      z-index: -1;
    }
    .panel-admin {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 20px;
      padding: 30px;
      max-width: 800px;
      width: 90%;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    h1 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 20px;
    }
    .btn-volver {
      margin-top: 20px;
      display: block;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="panel-admin">
    <h1>Usuarios registrados</h1>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!$usuarios): ?>
          <tr><td colspan="4" class="text-center text-muted">No hay usuarios</td></tr>
        <?php else: foreach ($usuarios as $u): ?>
          <tr>
            <td><?=(int)$u['id_usuario']?></td>
            <td><?=htmlspecialchars($u['nombre_usuario'])?></td>
            <td><?=htmlspecialchars($u['rol'])?></td>
            <td>
              <?php if ($u['rol'] !== 'admin'): ?>
                <form action="usuario_eliminar.php" method="post" onsubmit="return confirm('¿Eliminar este usuario?');" class="d-inline">
                  <input type="hidden" name="id" value="<?=(int)$u['id_usuario']?>">
                  <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    <a href="../menu.php" class="btn btn-outline-secondary btn-volver">Volver al menú</a>
  </div>
</body>
</html>
