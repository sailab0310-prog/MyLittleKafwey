<?php
// File: send_otp.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Error: Invalid email address format.";
            header("Location: forgot_password.php?message=" . urlencode($message));
            exit;
        }

        // --- Start: Core Application Logic ---
        
        /* * 1. Connect to Database (DB)
        * 2. Check if $email exists in the users table.
        * 3. If user is found:
        * a. Generate a secure, random OTP (e.g., 6 digits).
        * b. Store the OTP and an expiration timestamp in the DB.
        * c. Send the email containing the OTP to the user.
        * 4. If user is NOT found, still proceed to the success message 
        * to prevent user enumeration attacks.
        */

        // --- End: Core Application Logic ---

        // Success Redirection
        // Redirecting back to the original page with a generic success message 
        // is best practice for security, but you could redirect to a 
        // 'verify_otp.php' page instead.
        
        $message = "An OTP has been successfully sent to your email address.";
        
        header("Location: forgot_password.php?message=" . urlencode($message));
        exit;
        
    } else {
        $message = "Error: Email field is missing from the form submission.";
        header("Location: forgot_password.php?message=" . urlencode($message));
        exit;
    }
} else {
    header("Location: forgot_password.php");
    exit;
}
?>
