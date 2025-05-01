<?php
// functions.php

/**
 * Elimina espacios para generar nombres de carpetas válidos.
 */
function sanitizeUsername($username) {
    return str_replace(' ', '', $username);
}

/**
 * Carga el inventario desde $file usando un bloqueo compartido (LOCK_SH).
 * Devuelve un array de ['name','quantity','price'].
 */
function loadInventory($file = 'almacen.txt') {
    $inventory = [];

    if (!file_exists($file)) {
        return $inventory;
    }

    
    $handle = fopen($file, 'r');
    if ($handle === false) {
        return $inventory;
    }

    // Lock compartido para lectura
    if (flock($handle, LOCK_SH)) {
        while (($line = fgets($handle)) !== false) {
            $parts = explode("\t", trim($line));
            if (count($parts) === 3) {
                $inventory[] = [
                    'name'     => $parts[0],
                    'quantity' => intval($parts[1]),
                    'price'    => floatval($parts[2]),
                ];
            }
        }
        // Liberamos el lock
        flock($handle, LOCK_UN);
    }

    fclose($handle);
    return $inventory;
}

/**
 * Actualiza el inventario en $file usando un bloqueo exclusivo (LOCK_EX).
 * Recibe un array con 'name','quantity','price'.
 */
function updateInventory($inventory, $file = 'almacen.txt') {
    
    $handle = fopen($file, 'c+');
    if ($handle === false) {
        return false;
    }

    // Lock exclusivo para escritura
    if (flock($handle, LOCK_EX)) {
        // Vaciamos el fichero
        ftruncate($handle, 0);
        rewind($handle);

        foreach ($inventory as $product) {
            $line = sprintf(
                "%s\t%d\t%.2f\n",
                $product['name'],
                $product['quantity'],
                $product['price']
            );
            fwrite($handle, $line);
        }

        fflush($handle);
        flock($handle, LOCK_UN);
    }

    fclose($handle);
    return true;
}

/**
 * Devuelve la ruta al fichero de carrito del usuario en la fecha dada.
 */
function getOrCreateUserCart($username, $date) {
    $userDir = sanitizeUsername($username);
    if (!is_dir($userDir)) {
        mkdir($userDir, 0755, true);
    }
    return "{$userDir}/{$date}_cart.txt";
}

/**
 * Carga el carrito desde $cartFile 
 */
function loadCart($cartFile) {
    $cart = [];
    if (file_exists($cartFile)) {
        $lines = file($cartFile, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $item = explode("\t", trim($line));
            if (count($item) === 3) {
                $cart[] = [
                    'name'     => $item[0],
                    'quantity' => intval($item[1]),
                    'price'    => floatval($item[2]),
                ];
            }
        }
    }
    return $cart;
}

/**
 * Actualiza el carrito en $cartFile usando bloqueo exclusivo (LOCK_EX).
 * Misma lógica de flock que updateInventory.
 */
function updateCart($cart, $cartFile) {
    // Si no hay nada en el carrito, eliminamos el fichero
    if (empty($cart) && file_exists($cartFile)) {
        return unlink($cartFile);
    }

    $handle = fopen($cartFile, 'c+');
    if ($handle === false) {
        return false;
    }
    if (flock($handle, LOCK_EX)) {
        // Borramos contenido anterior
        ftruncate($handle, 0);
        rewind($handle);

        foreach ($cart as $item) {
            $line = sprintf(
                "%s\t%d\t%.2f\n",
                $item['name'],
                $item['quantity'],
                $item['price']
            );
            fwrite($handle, $line);
        }

        fflush($handle);
        flock($handle, LOCK_UN);
    }
    fclose($handle);
    return true;
}


function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}


function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}
?>
