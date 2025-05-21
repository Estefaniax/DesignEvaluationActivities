<?php
include 'functions.php';   // functions.php ya hace session_start()
  
// Eliminar si viene por índice
if (isset($_GET['delete'])) {
    requireLogin();
    deletePost(intval($_GET['delete']));
    header('Location: index.php');
    exit;
}

$posts = getPosts();
$user  = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Mini Blog – Inicio</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="logo">Mini Blog</div>
    <nav>
      <a href="index.php">Inicio</a>
      <?php if ($user): ?>
        <a href="main.php">Panel</a>
        <a href="login.php?logout=1">Salir (<?= htmlspecialchars($user) ?>)</a>
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
  <h1>¡Bienvenido/a Comienza a compartir ideas sobre Películas o Series!</h1>

  <?php if (!empty($posts)): ?>
    <?php foreach (array_reverse($posts) as $post): ?>
      <article class="post">
        <!-- 1) Header con título + acciones -->
        <div class="post-header">
          <h2 class="post-title">
            <a href="view.php?id=<?= $post['id'] ?>">
              <?= htmlspecialchars($post['title']) ?>
            </a>
          </h2>
          <?php if ($user === $post['author']): ?>
            <div class="actions">
              <a href="edit.php?id=<?= $post['id'] ?>" class="action-icon" title="Editar">
                <!-- SVG editar -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
                  <path d="M20.71 7.04a1 1 0 000-1.41l-2.34-2.34
                           a1 1 0 00-1.41 0l-1.41 1.41 3.75 3.75
                           1.41-1.41z"/>
                </svg>
              </a>
              <a href="index.php?delete=<?= $post['id'] ?>" class="action-icon" title="Eliminar"
                 onclick="return confirm('¿Eliminar esta publicación?')">
                <!-- SVG eliminar -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon">
                  <path d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7H6v12z"/>
                  <path d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
              </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- 2) Fecha y autor -->
        <div class="post-meta">
          Publicado el <?= $post['date'] ?> por <?= htmlspecialchars($post['author']) ?>
        </div>

        <!-- 3) Descripción -->
        <p>
          <?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>
          <?= strlen($post['content']) > 200 ? '…' : '' ?>
        </p>

        <!-- 4) Imagen si existe -->
        <?php if (!empty($post['image'])): ?>
          <img
            src="uploads/<?= htmlspecialchars($post['author']) ?>/<?= htmlspecialchars($post['image']) ?>"
            alt="Imagen de <?= htmlspecialchars($post['title']) ?>"
            class="post-image"
          >
        <?php endif; ?>

      </article>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No hay publicaciones aún.</p>
  <?php endif; ?>
</main>


  <!-- FOOTER -->
  <footer>
    &copy; <?= date('Y') ?> Mini Blog. Citlaly Estefanía Samano López.
  </footer>

  <script src="assets/js/accessibility.js"></script>
</body>
</html>
