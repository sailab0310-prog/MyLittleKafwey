<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$sql = "
    SELECT
        o.id AS order_id,
        u.username AS customer_name,
        o.order_date,
        o.total_price,
        o.status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    </head>
<body>
    <div class="container">
        <h1>Admin Order Management</h1>
        <p>Logged in as: <?= htmlspecialchars($_SESSION['username']) ?> | <a href="logout.php">Logout</a></p>
        
        <h2>Recent Orders</h2>
        <table border="1" style="width:100%; text-align:left;">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='order_details.php?id=" . $row['order_id'] . "'>#" . $row['order_id'] . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                    echo "<td>₱" . htmlspecialchars(number_format($row['total_price'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td><a href='update_status.php?id=" . $row['order_id'] . "'>Update</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
