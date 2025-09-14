<?php
require __DIR__.'/php/funciones_sesion.php';
exigir_login();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú - Draftosaurus</title>

  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .menu-columna{
      display:flex; flex-direction:column; align-items:center; gap:14px; margin:0 auto;
    }
    .menu-columna .btn-menu{
      width:260px; padding:14px 18px; font-size:1.2rem; border-radius:14px; text-align:center; margin-top:12px;
      background-color:#f5f5dc; color:#333; border:1px solid #ddd; box-shadow:6px 6px 12px rgba(0,0,0,.15);
      transition:transform .2s ease, box-shadow .2s ease, background-color .3s ease;
    }
    .menu-columna .btn-menu:hover{
      transform:translateY(-3px); box-shadow:0 8px 16px rgba(0,0,0,.25); background-color:#e0e0b8;
    }
    .menu-columna .btn-salir{
      background-color:#f8fafc; color:#111; border:1px solid #ddd;
    }
    .menu-columna .btn-salir:hover{
      background-color:#dc2626 !important; color:#fff !important; border-color:#b91c1c !important;
    }
    .menu-contenedor{
      background:#fff; border-radius:20px; padding:40px 60px; text-align:center; display:inline-block;
      box-shadow:0 8px 20px rgba(0,0,0,.3); margin:0 auto;
    }
    .titulo-bienvenida{
      font-weight:900; font-size:2.5rem; color:#0d300e;
    }
  </style>
</head>
<body class="con-fondo d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg jungle-header">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <span class="navbar-brand text-white fs-3 m-0">Draftosaurus</span>
  </div>
</nav>

<main class="container py-5 flex-grow-1 d-flex justify-content-center align-items-center">
  <div class="menu-contenedor">
    <div class="text-center mb-4">
      <h1 class="titulo-bienvenida">¡Bienvenido a Draftosaurus!</h1>
    </div>

    <div class="menu-columna">
      <a href="nueva.php" class="btn btn-menu">Nueva Partida</a>
      <a href="#"class="btn btn-menu">Ranking Global</a>
      <a href="#" class="btn btn-menu">Historial de Partidas</a>
      <a href="ajustes.php" class="btn btn-menu">Ajustes</a>
      <?php if (function_exists('es_admin') && es_admin()): ?>
        <a href="admin/usuarios.php" class="btn-menu">Panel de administración</a>
      <?php endif; ?>
      <a href="index.php"class="btn btn-menu btn-salir">Salir</a>
    </div>
  </div>
</main>

<footer class="text-center text-white p-3 jungle-header mt-auto">
  <p class="mb-0">Proyecto Draftosaurus © 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
