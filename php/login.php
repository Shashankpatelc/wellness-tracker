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

// CRITICAL FIX: Select user_id
$user_id = null; // Initialize $user_id to prevent PHP warning
$stmt = $conn->prepare("SELECT user_id, username, password_hash FROM users WHERE username = ?");$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows == 1){
    $stmt->bind_result($user_id, $fetched_username, $fetched_hashed_password);
    $stmt->fetch();
    
    if($fetched_hashed_password !== null && password_verify($password, $fetched_hashed_password)){
        // --- SUCCESS: Create Session Variables ---
        $_SESSION["loggedin"] = true;
        $_SESSION["user_id"] = $user_id; // Store user_id
        $_SESSION["username"] = $fetched_username;
        
        // Redirect to the Dashboard CONTROLLER in the php/ folder
        header("location: /project1/php/dashboard.php"); 
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