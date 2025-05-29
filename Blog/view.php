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
<?php include 'header.php'; ?>


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
    &copy; <?= date('Y') ?> Mini Blog. ODS 12 Garantizar modalidades de consumo y producción sostenibles.
  </footer>

  <script src="assets/js/accessibility.js"></script>
</body>
</html>
