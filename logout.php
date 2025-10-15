<?php
session_start();
// Destroy all session data
session_unset();
session_destroy();
// Redirect back to the login page or the home page
header('Location: index.php');
exit;
?>