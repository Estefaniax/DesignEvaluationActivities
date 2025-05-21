<?php
include 'functions.php';

// logout
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: index.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $p = trim($_POST['password'] ?? '');
  if (authenticate($u, $p)) {
    header('Location: main.php');
    exit;
  } else {
    $error = 'Nombre de usuario o contraseña incorrectos.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Login – Mini Blog</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">
</head>
<body>
  <header>
    <div class="logo">Mini Blog</div>
    <nav><a href="index.php">Inicio</a></nav>
    <div id="accessibility-bar">
      <!-- botones de accesibilidad -->
      <button id="toggle-dark">Modo oscuro</button>
      <button id="toggle-contrast">Modo contraste</button>
      <button id="text-toggle">Aumentar texto</button>
      <button id="reset-accessibility">Resetear accesibilidad</button>
      <button id="simulate-vision-btn">Modo accesible</button>
      <div id="vision-modes">
        <button data-mode="normal">Visión normal</button>
        <button data-mode="protanopia">Protanopia</button>
        <button data-mode="deuteranopia">Deuteranopia</button>
        <button data-mode="tritanopia">Tritanopia</button>
      </div>
    </div>
  </header>

  <div class="container">
    <h1>Iniciar Sesión</h1>
    <?php if ($error): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="username">Nombre de usuario:</label>
      <input type="text" id="username" name="username" required placeholder="Tu nombre de usuario">

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required placeholder="Tu contraseña">

      <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
  </div>

  <footer>&copy; <?= date('Y') ?> Mini Blog. Citlaly Estefanía Samano López.</footer>
  <script src="assets/js/accessibility.js"></script>
</body>
</html>
