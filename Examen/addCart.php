<?php
require_once 'functions.php';
session_start();

// 1) Verificar usuario
if (!isset($_SESSION['username'])) {
    header('Location: welcome.php');
    exit();
}

$username = $_SESSION['username'];
$date     = date('Y-m-d');
$cartFile = getOrCreateUserCart($username, $date);

// 2) Inicializar mensaje
$message      = '';
$message_type = '';

// 3) Procesar petición de añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['product'], $_POST['quantity'])
) {
    $product   = $_POST['product'];
    $qty       = max(0, intval($_POST['quantity']));
    $inventory = loadInventory();
    $cart      = loadCart($cartFile);

    foreach ($inventory as &$inv) {
        if ($inv['name'] === $product) {
            if ($qty <= 0) {
                $message      = "Cantidad inválida para «{$product}».";
                $message_type = 'error';
            }
            elseif ($qty > $inv['quantity']) {
                $message      = "Solo quedan {$inv['quantity']} unidades de «{$product}».";
                $message_type = 'error';
            }
            else {
                // Reducir stock
                $inv['quantity'] -= $qty;
                updateInventory($inventory);

                // Añadir al carrito
                $found = false;
                foreach ($cart as &$item) {
                    if ($item['name'] === $product) {
                        $item['quantity'] += $qty;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $cart[] = [
                        'name'     => $product,
                        'quantity' => $qty,
                        'price'    => $inv['price']
                    ];
                }
                updateCart($cart, $cartFile);

                $message      = "Añadidas {$qty} unidades de «{$product}» al carrito.";
                $message_type = 'success';
            }
            break;
        }
    }
}

// 4) Redirigir de vuelta a pedidos.php con el mensaje
header(
    "Location: pedidos.php"
  . "?message_type={$message_type}"
  . "&message=" . urlencode($message)
);
exit;
