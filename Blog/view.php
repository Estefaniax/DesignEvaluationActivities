<?php
include 'functions.php';

// Obtener el ID y la publicación
$id   = intval($_GET['id'] ?? 0);
$post = getPost($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>
    <?= $post
        ? htmlspecialchars($post['title']) . ' – Mini Blog'
        : 'Publicación no encontrada – Mini Blog' ?>
  </title>
  <!-- Fuente Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Estilos -->
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="logo">Mini Blog</div>
    <nav>
      <a href="index.php">Inicio</a>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="main.php">Panel</a>
        <a href="login.php?logout=1">Salir (<?= htmlspecialchars($_SESSION['user']) ?>)</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </nav>
    <div id="accessibility-bar">
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

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container">
    <?php if ($post): ?>
      <article class="post">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="meta">
          <?= $post['date'] ?> por <?= htmlspecialchars($post['author']) ?>
        </div>

        <?php if ($post['image']): ?>
          <img
            src="uploads/<?= htmlspecialchars($post['author']) ?>/<?= htmlspecialchars($post['image']) ?>"
            alt="Imagen de <?= htmlspecialchars($post['title']) ?>"
            class="post-image"
          >
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
      </article>
    <?php else: ?>
      <p>Lo sentimos, la publicación no pudo ser encontrada.</p>
    <?php endif; ?>
  </main>

  <!-- FOOTER -->
  <footer>
    &copy; <?= date('Y') ?> Mini Blog. Estefanía  López.
  </footer>

  <script src="assets/js/accessibility.js"></script>
</body>
</html>
