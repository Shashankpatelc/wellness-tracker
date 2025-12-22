<?php
// php/login.php

session_start();
require_once 'connect_db.php'; // Use modular connection

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if ($username === '' || $password === '') {
        echo "Please enter both username and password.";
        $conn->close();
        exit;
    } 
    }

// CRITICAL FIX: Select user_id and role
$user_id = null; // Initialize $user_id to prevent PHP warning
$stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows == 1){
    $stmt->bind_result($user_id, $fetched_username, $fetched_hashed_password, $fetched_role);
    $stmt->fetch();
    
    if($fetched_hashed_password !== null && password_verify($password, $fetched_hashed_password)){
        // --- SUCCESS: Create Session Variables ---
        $_SESSION["loggedin"] = true;
        $_SESSION["user_id"] = $user_id; // Store user_id
        $_SESSION["username"] = $fetched_username;
        $_SESSION["role"] = $fetched_role; // Store user role
        
        // Redirect logic based on role (optional, for now all go to dashboard, or admins can go to admin dash)
        header("location: /wellness-tracker/php/dashboard.php"); 
        exit;
    } else {
        echo "Invalid username or password."; 
    }
} else {
    echo "Invalid username or password."; 
}

$stmt->close();
$conn->close();
?>