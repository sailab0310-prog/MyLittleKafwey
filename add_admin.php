<?php
include 'config.php';

$admin_username = "admin";
$admin_password = "password123";

$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password_hash) VALUES (?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $admin_username, $hashed_password);

    if ($stmt->execute()) {
        echo "Admin user **'" . $admin_username . "'** created successfully! Password hashed and stored.";
        echo "<br>Please delete this 'add_admin.php' file immediately after running it for security.";
    } else {
        echo "Error: Could not execute query. " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Could not prepare statement. " . $conn->error;
}

$conn->close();
?>