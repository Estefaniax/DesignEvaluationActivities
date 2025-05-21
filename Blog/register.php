<?php
include 'functions.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    if ($u === '' || $p === '') {
        $error = 'Rellena ambos campos.';
    } elseif (userExists($u)) {
        $error = 'El nombre de usuario ya existe.';
    } else {
        registerUser($u, $p);
        authenticate($u, $p);
        header('Location: main.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Registro – Mini Blog</title>
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
    <h1>Regístrate</h1>
    <?php if ($error): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="username">Nombre de usuario:</label>
      <input type="text" id="username" name="username" required placeholder="Tu nombre de usuario">

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required placeholder="Tu contraseña">

      <button type="submit">Crear cuenta</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
  </div>

  <footer>&copy; <?= date('Y') ?> Mini Blog. Citlaly Estefanía Samano López.</footer>
  <script src="assets/js/accessibility.js"></script>
</body>
</html>
