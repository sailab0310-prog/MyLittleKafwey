<?php
session_start();
include 'config.php';

$sql = "SELECT id, name, price FROM drinks";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Little Kafwey</title>
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
            cursor: pointer;
        }

        li:hover {
            background-color: #d0d0d0;
        }

        a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .price {
            float: right;
            font-weight: bold;
            color: #5d4037;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .nav-link {
            padding: 10px 20px;
            background-color: #5d4037;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: #4e342e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Little Kafwey</h1>
        <h2>Our Ice Blended Drinks Menu</h2>
        
        <div class="nav-links">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="nav-link">Login</a>
                <a href="signup.php" class="nav-link">Sign Up</a>
            <?php else: ?>
                <a href="profile.php" class="nav-link">Welcome, User!</a>
                <a href="logout.php" class="nav-link">Logout</a>
            <?php endif; ?>
            <a href="cart.php" class="nav-link">View My Cart</a>
        </div>
        
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='add_to_cart.php?id=" . $row["id"] . "&name=" . urlencode($row["name"]) . "&price=" . $row["price"] . "'>";
                    echo htmlspecialchars($row["name"]);
                    echo "<span class='price'>₱" . htmlspecialchars(number_format($row["price"], 2)) . "</span>";
                    echo "</a>";
                    echo "</li>";
                }
            } else {
                echo "<li>No drinks available.</li>";
            }
            $conn->close();
            ?>
        </ul>
    </div>
</body>
</html>
