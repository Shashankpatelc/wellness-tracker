<?php
// php/admin/check_admin.php

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../html/login.html");
    exit;
}

// Check if user is an admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin') {
    // Redirect non-admins to the regular dashboard with an error (optional)
    header("location: ../dashboard.php");
    exit;
}
?>
