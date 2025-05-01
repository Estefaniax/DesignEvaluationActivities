<?php
require_once 'functions.php';
session_start();

// Si no hay usuario, redirige a welcome
if (!isset($_SESSION['username'])) {
    header('Location: welcome.php');
    exit();
}

$username     = $_SESSION['username'];
$date         = date('Y-m-d');
$cartFile     = getOrCreateUserCart($username, $date);

// Mensajes para el usuario (POST o GET desde addCart)
$message      = '';
$message_type = '';
if (!empty($_GET['message']) && !empty($_GET['message_type'])) {
    $message      = $_GET['message'];
    $message_type = $_GET['message_type'];
}

// Procesar formulario de actualización o borrado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['product'])) {
    $action    = $_POST['action'];
    $product   = $_POST['product'];
    $cart      = loadCart($cartFile);
    $inventory = loadInventory();

    if ($action === 'update' && isset($_POST['quantity'])) {
        $newQty = max(0, intval($_POST['quantity']));
        foreach ($cart as $i => $item) {
            if ($item['name'] === $product) {
                $oldQty = $item['quantity'];
                $delta  = $newQty - $oldQty;
                // Ajustar inventario
                foreach ($inventory as &$inv) {
                    if ($inv['name'] === $product) {
                        $inv['quantity'] -= $delta;
                        break;
                    }
                }
                updateInventory($inventory);

                // Ajustar carrito
                if ($newQty > 0) {
                    $cart[$i]['quantity'] = $newQty;
                    $message      = "Cantidad de «{$product}» actualizada a {$newQty}.";
                    $message_type = 'success';
                } else {
                    array_splice($cart, $i, 1);
                    $message      = "«{$product}» eliminado (cant. 0).";
                    $message_type = 'success';
                }
                updateCart($cart, $cartFile);
                break;
            }
        }
    }
    elseif ($action === 'delete') {
        foreach ($cart as $i => $item) {
            if ($item['name'] === $product) {
                $removedQty = $item['quantity'];
                // Devolver al inventario
                foreach ($inventory as &$inv) {
                    if ($inv['name'] === $product) {
                        $inv['quantity'] += $removedQty;
                        break;
                    }
                }
                updateInventory($inventory);

                // Quitar del carrito
                array_splice($cart, $i, 1);
                updateCart($cart, $cartFile);

                $message      = "«{$product}» eliminado del carrito.";
                $message_type = 'success';
                break;
            }
        }
    }
}

// Recarga del carrito y total
$cart  = loadCart($cartFile);
$total = calculateCartTotal($cart);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mi Carrito</title>
  <link id="main-css" rel="stylesheet" href="styles.css">
  <link id="alt-css"  rel="stylesheet" href="actividad4B_accesibilidad.css" disabled>
  <script src="accessibility.js" defer></script>
</head>
<body>
  <?php include 'accessibility_header.php'; ?>

  <div class="container">
    <h1>Mi Carrito</h1>

    <?php if ($message): ?>
      <div class="message <?php echo htmlspecialchars($message_type); ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <?php if (count($cart) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td><?php echo formatCurrency($item['price']); ?></td>
              <td>
                <form 
                  method="post" 
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
                  style="display:inline-block;"
                >
                  <input type="hidden" name="action"  value="update">
                  <input type="hidden" name="product" value="<?php echo htmlspecialchars($item['name']); ?>">
                  <input 
                    type="number" 
                    name="quantity" 
                    value="<?php echo $item['quantity']; ?>" 
                    min="0" 
                    style="width:60px;"
                  >
                  <input 
                    type="submit" 
                    value="Actualizar" 
                    title="Actualizar cantidad"
                  >
                </form>
              </td>
              <td><?php echo formatCurrency($item['quantity'] * $item['price']); ?></td>
              <td>
                <form 
                  method="post" 
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
                  onsubmit="return confirm('¿Eliminar <?php echo htmlspecialchars($item['name']); ?> del carrito?');" 
                  style="display:inline-block;"
                >
                  <input type="hidden" name="action"  value="delete">
                  <input type="hidden" name="product" value="<?php echo htmlspecialchars($item['name']); ?>">
                  <input 
                    type="submit" 
                    value="🗑️" 
                    title="Eliminar producto"
                  >
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td class="total-highlight"><?php echo formatCurrency($total); ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    <?php else: ?>
      <p>Tu carrito está vacío.</p>
    <?php endif; ?>

    <div class="cart-actions">
      <a href="pedidos.php" class="btn"> Volver a Pedidos</a>
    </div>
  </div>
</body>
</html>
