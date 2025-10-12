<?php
session_start();
include '../config.php'; // Note the path change if it's in a subdirectory

// Essential security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect non-admins or guests
    exit;
}

// Admin logic starts here
?>