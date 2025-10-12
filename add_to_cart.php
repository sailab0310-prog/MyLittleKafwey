<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['id'], $_GET['name'], $_GET['price'])) {
    $id = (int)$_GET['id'];
    $name = $_GET['name'];
    $price = (float)$_GET['price'];
    
    $item_key = $id;

    if (isset($_SESSION['cart'][$item_key])) {
        $_SESSION['cart'][$item_key]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$item_key] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

header('Location: index.php'); 
exit;
?>
