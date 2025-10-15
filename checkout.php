<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

$order_id = null;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'];
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO orders (total_amount) VALUES (?)");
        $stmt->bind_param("d", $total_amount);
        $stmt->execute();

        $order_id = $conn->insert_id;

        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, drink_id, price) VALUES (?, ?, ?)");

        foreach ($_SESSION['cart'] as $item) {
            $drink_id = $item['id'];
            $price = $item['price'];
            $stmt_items->bind_param("iid", $order_id, $drink_id, $price);
            $stmt_items->execute();
        }

        $conn->commit();

        unset($_SESSION['cart']);

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        die("<h1>Order Failed!</h1><p>Error: " . htmlspecialchars($exception->getMessage()) . "</p>");
    }

    $conn->close();

} else {
    header("Location: index.php");
    exit();
}

if (!$order_id) {
    die("<h1>Error</h1><p>Order ID not generated. Please try again.</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        .container {
            min-height: 220px;
            max-width: 600px;
            margin: 80px auto;
            padding: 40px 30px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.12);
            border-radius: 10px;
        }
        h1 {
            color: #5d4037;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .home-link {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #5d4037;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .home-link:hover {
            background-color: #433026;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Confirmed!</h1>
        <p>Thank you for your order from My Little Kafwey.</p>
        <p>Your order ID is: <strong><?php echo htmlspecialchars($order_id); ?></strong></p>
        <a href="index.php" class="home-link">Back to Home</a>
    </div>
</body>
</html>
