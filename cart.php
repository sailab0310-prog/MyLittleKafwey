<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #5d4037;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }
        
        li {
            background-color: #e0e0e0;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-grow: 1;
        }
        
        .item-name {
            text-align: left;
        }
        
        .item-price {
            font-weight: bold;
            color: #5d4037;
            margin-left: 20px;
        }

        .remove-link {
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 3px;
            margin-left: 20px;
        }

        .cart-link, .checkout-btn {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5d4037;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        
        .checkout-btn {
            background-color: #27ae60; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Cart</h1>
        <a href="index.php" class="cart-link">Back to Menu</a>
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $total = 0;
            echo '<ul>';
            foreach ($_SESSION['cart'] as $index => $item) {
                echo '<li>';
                echo '<div class="item-details">';
                echo '<span class="item-name">' . htmlspecialchars($item['name']) . '</span>';
                echo '<span class="item-price">₱' . htmlspecialchars(number_format($item['price'], 2)) . '</span>';
                echo '</div>';
                echo '<a href="add_to_cart.php?remove_item=' . $index . '" class="remove-link">Remove</a>';
                echo '</li>';
                $total += $item['price'];
            }
            echo '</ul>';
            echo '<h3>Total: ₱' . number_format($total, 2) . '</h3>';
            
            echo '<a href="checkout.php" class="checkout-btn">Checkout</a>';
        } else {
            echo '<p>Your cart is empty.</p>';
        }
        ?>
    </div>
</body>
</html>
