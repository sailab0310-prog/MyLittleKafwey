<?php
session_start();
include 'config.php';

$result = null; 
$message = "";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$sql = "
SELECT
    o.id AS order_id,
    o.user_id,
    o.total_amount AS order_total,
    o.order_date,
    di.price AS quantity,
    d.name AS item_name,
    d.price AS unit_price_from_menu
FROM
    orders o
JOIN
    order_items di ON o.id = di.order_id
JOIN
    drinks d ON di.drink_id = d.id
ORDER BY
    o.order_date DESC
LIMIT 0, 25";

$query_result = $conn->query($sql); 

if ($query_result === FALSE) {
    $message = "Error fetching orders. Check your table structure and SQL query. Database error: " . $conn->error;
} else {
    $result = $query_result; 
    
    if ($result->num_rows == 0) {
        $message = "No orders have been placed yet.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English+SC&display=swap" rel="stylesheet">
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
        }
        .container {
            max-width: 90%;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        h1 {
            font-family: 'IM Fell English SC', serif;
            color: #5d4037;
            font-size: 3em;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px 8px;
            text-align: left;
        }
        th {
            background-color: #5d4037;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .logout-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #795548;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>All Customer Orders</h2>
        <p>Welcome, **<?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?>**.</p>
        
        <?php if ($message): ?>
            <p style="color: red;">**ERROR:** <?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Item Name</th>
                        <th>Unit Price (₱)</th>
                        <th>Qty</th>
                        <th>Line Total (₱)</th>
                        <th>Order Total (₱)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): 
                        $unit_price = $row['unit_price_from_menu'];
                        $quantity = $row['quantity'];
                        $line_total = $unit_price * $quantity;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($unit_price, 2)); ?></td>
                        <td><?php echo htmlspecialchars($quantity); ?></td>
                        <td>**<?php echo htmlspecialchars(number_format($line_total, 2)); ?>**</td>
                        <td>**<?php echo htmlspecialchars(number_format($row['order_total'], 2)); ?>**</td>
                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders have been placed yet.</p>
        <?php endif; ?>

        <a href="logout.php?admin=true" class="logout-link">Admin Logout</a>
    </div>
</body>
</html>