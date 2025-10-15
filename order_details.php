<?php
session_start();
include 'config.php'; 

// CRITICAL SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// 1. Get the Order ID from the URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id === 0) {
    die("Invalid Order ID provided.");
}

// 2. Fetch the specific items in the order
// Joins order_items and drinks to get the drink name
$item_sql = "
    SELECT 
        d.name AS drink_name,
        oi.quantity,
        oi.price_at_order
    FROM order_items oi
    JOIN drinks d ON oi.drink_id = d.id
    WHERE oi.order_id = ?
";

$stmt = $conn->prepare($item_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

// 3. Fetch the primary order details (like customer name and total)
$order_sql = "
    SELECT u.username, o.total_price, o.status, o.order_date
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_details = $order_stmt->get_result()->fetch_assoc();

if (!$order_details) {
    die("Order not found.");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?> Details</title>
    </head>
<body>
    <div class="container">
        <h1>Details for Order #<?= $order_id ?></h1>
        <p>
            **Customer:** <?= htmlspecialchars($order_details['username']) ?> | 
            **Date:** <?= htmlspecialchars(date("Y-m-d H:i", strtotime($order_details['order_date']))) ?> | 
            **Status:** <?= htmlspecialchars($order_details['status']) ?>
        </p>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
        
        <h2>Items Ordered</h2>
        <table border="1" style="width:50%; border-collapse: collapse; text-align:left; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Drink Name</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while($item = $items_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['drink_name']) . "</td>";
                echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                echo "<td>₱" . htmlspecialchars(number_format($item['price_at_order'], 2)) . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        
        <h3 style="margin-top: 20px;">Total Price: ₱<?= htmlspecialchars(number_format($order_details['total_price'], 2)) ?></h3>
    </div>
</body>
</html>
