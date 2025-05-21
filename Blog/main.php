<?php
include 'functions.php';
requireLogin();

// Eliminar publicación
if (isset($_GET['delete'])) {
    deletePost(intval($_GET['delete']));
    header('Location: main.php');
    exit;
}

// Crear publicación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    savePost($_POST['title'] ?? '', $_POST['content'] ?? '');
    header('Location: main.php');
    exit;
}

$user  = $_SESSION['user'];
$all   = getPosts();
$posts = array_filter($all, fn($p) => $p['author'] === $user);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel – Mini Blog</title>
  <!-- Fuente -->
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
      <a href="main.php">Panel</a>
      <a href="login.php?logout=1">Salir (<?= htmlspecialchars($user) ?>)</a>
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

  <!-- MAIN -->
  <main class="container">
    <!-- Título centrado -->
    <h1 class="admin-title">Panel de Administración</h1>

    <div class="panel-container">
      <!-- CREAR PUBLICACIÓN -->
      <section class="panel-card">
        <h2>Crear Publicación</h2>
        <form method="post" class="form-panel" enctype="multipart/form-data">
          <label for="title">Título:</label>
          <input type="text" id="title" name="title" required>

          <label for="content">Contenido:</label>
          <textarea id="content" name="content" rows="6" required></textarea>

          <label for="image">Imagen (opcional):</label>
          <input type="file" id="image" name="image" accept="image/*">

          <button type="submit" class="btn-primary">Publicar</button>
        </form>
      </section>

      <!-- MIS PUBLICACIONES -->
      <section class="panel-card panel-list">
        <h2>Mis Publicaciones</h2>

        <?php if (!empty($posts)): ?>
          <?php foreach (array_reverse($posts) as $p): ?>
            <article class="post-card">
              <div class="post-header">
                <h3>
                  <a href="view.php?id=<?= $p['id'] ?>">
                    <?= htmlspecialchars($p['title']) ?>
                  </a>
                </h3>
                <div class="actions">
                  <a href="edit.php?id=<?= $p['id'] ?>"
                     class="action-icon"
                     title="Editar publicación">
                    <!-- SVG editar -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="currentColor" class="icon">
                      <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
                      <path d="M20.71 7.04a1 1 0 000-1.41l-2.34-2.34
                               a1 1 0 00-1.41 0l-1.41 1.41 3.75 3.75
                               1.41-1.41z"/>
                    </svg>
                  </a>
                  <a href="main.php?delete=<?= $p['id'] ?>"
                     class="action-icon"
                     title="Eliminar publicación"
                     onclick="return confirm('¿Eliminar esta publicación?')">
                    <!-- SVG eliminar -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="currentColor" class="icon">
                      <path d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7H6v12z"/>
                      <path d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                    </svg>
                  </a>
                </div>
              </div>

              <?php if ($p['image']): ?>
                <img src="uploads/<?= htmlspecialchars($user) ?>/<?= htmlspecialchars($p['image']) ?>"
                     alt="Imagen de <?= htmlspecialchars($p['title']) ?>"
                     class="post-image">
              <?php endif; ?>

              <div class="meta">
                <?= $p['date'] ?> por <?= htmlspecialchars($p['author']) ?>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aún no tienes publicaciones.</p>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    &copy; <?= date('Y') ?> Mini Blog. Citlaly Estefanía Samano López.
  </footer>

  <script src="assets/js/accessibility.js"></script>
</body>
</html>
