<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['id'], $_GET['name'], $_GET['price'])) {
    $id = (int)$_GET['id'];
    $name = $_GET['name'];
    $price = (float)$_GET['price'];
    
    // Create a unique key for the item in the cart
    $item_key = $id;

    if (isset($_SESSION['cart'][$item_key])) {
        // Item exists, just increment quantity (or handle options if you had them)
        $_SESSION['cart'][$item_key]['quantity'] += 1;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_key] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

// Redirect back to the menu or the cart page
header('Location: index.php'); 
exit;
?>