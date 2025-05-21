<?php
require_once 'functions.php';
session_start();

// 1) Verificar o recibir usuario
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
} elseif (!isset($_SESSION['username'])) {
    header('Location: welcome.php');
    exit();
}

$username      = $_SESSION['username'];

// 2) Mensaje de retorno desde addCart.php
$message       = isset($_GET['message'])      ? $_GET['message']      : '';
$message_type  = isset($_GET['message_type']) ? $_GET['message_type'] : '';

// 3) Cargar inventario  
$inventory = loadInventory();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Realizar Pedido</title>
  <link id="main-css" rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="actividad4C.css">

  <link id="alt-css"  rel="stylesheet" href="actividad4B_accesibilidad.css" disabled>
  <script src="accessibility.js" defer></script>
</head>
<body>
  <?php include 'accessibility_header.php'; ?>

  <div class="container">
    <h1>Realizar Pedido</h1>

    <?php if ($message): ?>
      <div class="message <?php echo htmlspecialchars($message_type); ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form action="addCart.php" method="post">
      <label for="product">Selecciona un producto:</label>
      <select name="product" id="product" required>
        <option value="" disabled selected> Elige un producto </option>
        <?php foreach ($inventory as $item): ?>
          <option value="<?php echo htmlspecialchars($item['name']); ?>">
            <?php 
              echo htmlspecialchars($item['name'])
                   . " – " 
                   . formatCurrency($item['price']);
            ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="quantity">Cantidad:</label>
      <input 
        type="number" 
        name="quantity" 
        id="quantity" 
        min="1" 
        required 
        placeholder="Cantidad"
      >

      <button type="submit" class="btn">Añadir al Carrito</button>
    </form>

    <div class="cart-actions">
      <a href="viewCart.php" class="btn">Ver Carrito</a>
    </div>
  </div>
</body>
</html>
