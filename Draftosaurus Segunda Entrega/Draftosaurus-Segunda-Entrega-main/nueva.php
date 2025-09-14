<?php
require __DIR__.'/php/funciones_sesion.php';
if (session_status() === PHP_SESSION_NONE) session_start();
exigir_login();

$mensaje = tomar_mensaje_flasheo();
$nombre  = $_SESSION['nombre_usuario'] ?? 'Jugador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva partida - Draftosaurus</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="con-fondo d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg jungle-header">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="menu.php">Draftosaurus</a>
  </div>
</nav>

<main class="container d-flex justify-content-center align-items-center flex-grow-1">
  <div class="card p-4" style="max-width:520px;width:100%;">
    <h2 class="mb-3">Nueva partida</h2>

    <?php if ($mensaje): ?>
      <div class="alert <?= $mensaje['tipo']==='ok' ? 'alert-success' : 'alert-danger' ?>"><?= htmlspecialchars($mensaje['texto']) ?></div>
    <?php endif; ?>

    <form id="form-nueva" method="post" action="php/nueva_preparar.php" novalidate>
      <div class="mb-3">
        <label class="form-label">Jugador</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($nombre) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label" for="bots">Cantidad de bots</label>
        <select class="form-select" id="bots" name="bots" required>
          <option value="1" selected>1 bot</option>
          <option value="2">2 bots</option>
          <option value="2">3 bots</option>
          <option value="2">4 bots</option>
        </select>
        <div class="invalid-feedback">Elegí cuántos bots querés.</div>
      </div>

      <button class="btn btn-safari fw-bold" type="submit">Comenzar</button>
      <a class="btn btn-outline-secondary ms-2" href="menu.php">Cancelar</a>
    </form>
  </div>
</main>

<footer class="text-center text-white p-3 jungle-header mt-auto">
  <p class="mb-0">Proyecto Draftosaurus © 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  const f = document.getElementById('form-nueva');
  if (!f) return;
  f.addEventListener('submit', e => {
    const sel = f.elements['bots'];
    const ok  = sel.value === '1' || sel.value === '2';
    sel.classList.toggle('is-invalid', !ok);
    if (!ok) e.preventDefault();
  });
})();
</script>
</body>
</html>
