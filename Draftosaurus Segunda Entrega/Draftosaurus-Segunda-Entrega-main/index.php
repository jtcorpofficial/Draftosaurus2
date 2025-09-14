<!DOCTYPE html>
<html lang="es"> <!-- Define el idioma principal del documento como español -->
<head>
  <meta charset="UTF-8" /> <!-- Codificación de caracteres (soporta ñ, tildes, etc.) -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Adaptación responsive para dispositivos móviles -->
  <title>Draftosaurus - Inicio</title> <!-- Título que aparece en la pestaña del navegador -->

  <!-- Bootstrap 5 para estilos y componentes responsivos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Fuente Fredoka desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Hoja de estilos personalizada -->
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body class="d-flex flex-column min-vh-100"> <!-- Estructura de página con Bootstrap: columna flexible de altura mínima 100vh -->

  <!-- Header transparente sobre el fondo -->
  <header class="navbar navbar-expand-lg jungle-header fixed-top">
    <div class="container px-4 d-flex justify-content-between align-items-center">
      <!-- Título/Logo del sitio -->
      <a class="navbar-brand text-white fs-3" href="#">🦕 Draftosaurus</a>
      <div>
        <!-- Botón que lleva a las reglas del juego -->
        <a class="btn btn-safari fw-bold me-2" href="reglas.html">Reglas</a>
        <!-- Botón que lleva al tablero para jugar -->
        <a class="btn btn-safari fw-bold" href="login.php">¡Jugar!</a>
      </div>
    </div>
  </header>

  <!-- Contenido principal centrado vertical y horizontalmente -->
  <main class="flex-grow-1 d-flex justify-content-center align-items-center text-center text-white px-3">
    <div class="welcome-box">
      <!-- Título de bienvenida -->
      <h1 class="bienvenida">¡Bienvenido a la jungla del Draft!</h1>
      <!-- Subtítulo o descripción -->
      <p>Armá tu propio parque de dinosaurios en este divertido juego familiar y estratégico.</p>
    </div>
  </main>

  <!-- Footer al final de la página -->
  <footer class="footer mt-auto text-white text-center py-3">
    <p class="m-0">🦖 Desarrollado por JT Corp © 2025</p> <!-- Texto de copyright con ícono -->
  </footer>

</body>
</html>

