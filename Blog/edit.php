<?php
include 'functions.php';
requireLogin();

$user = $_SESSION['user'];
$id   = intval($_GET['id'] ?? 0);
$post = getPost($id);

if (!$post || $post['author'] !== $user) {
    header('Location: main.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (updatePost($id, $_POST['title'] ?? '', $_POST['content'] ?? '')) {
        header('Location: main.php');
        exit;
    }
    $error = 'Título y contenido son obligatorios.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Publicación – Mini Blog</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">
</head>
<body>
  <!-- HEADER -->
<?php include 'header.php'; ?>


  <!-- MAIN -->
  <main class="container">
    <h1 class="admin-title">Editar Publicación</h1>
    <section class="panel-card">
      <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post" class="form-panel" enctype="multipart/form-data">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title"
               value="<?= htmlspecialchars($post['title']) ?>" required>

        <label for="content">Contenido:</label>
        <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>

        <?php if ($post['image']): ?>
          <label>Imagen actual:</label>
          <img src="uploads/<?= htmlspecialchars($user) ?>/<?= htmlspecialchars($post['image']) ?>"
               alt="Imagen de <?= htmlspecialchars($post['title']) ?>"
               class="post-image">
        <?php endif; ?>

        <label for="image">Actualizar Imagen (opcional):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <div style="margin-top: var(--spacing);">
          <button type="submit" class="btn-primary">Guardar cambios</button>
          <a href="main.php" class="btn-secondary">Cancelar</a>
        </div>
      </form>
    </section>
  </main>

  <!-- FOOTER -->
  <footer>
    &copy; <?= date('Y') ?> Mini Blog. ODS 12 Garantizar modalidades de consumo y producción sostenibles.
  </footer>

  <script src="assets/js/accessibility.js"></script>
</body>
</html>
