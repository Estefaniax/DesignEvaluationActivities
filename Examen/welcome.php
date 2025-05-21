<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a la Tienda</title>
  <link id="main-css" rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="actividad4C.css">

  <link id="alt-css"  rel="stylesheet" href="actividad4B_accesibilidad.css" disabled>
  <script src="accessibility.js" defer></script>
</head>
<body>
  <?php include 'accessibility_header.php'; ?>

  <div class="container">
    <h1>Bienvenido a la Tienda Electrónica</h1>
    <form action="pedidos.php" method="post">
      <label for="username">Por favor, introduce tu nombre:</label>
      <input type="text" id="username" name="username" required>
      <button type="submit">Comenzar Compra</button>
    </form>
  </div>
</body>
</html>
