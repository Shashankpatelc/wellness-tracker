<?php
// php/register.php

require_once 'connect_db.php'; // Use modular connection

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Use prepared statement to check existence
    $stmp = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmp->bind_param("ss", $username, $email);
    $stmp->execute();
    $stmp->store_result();
    
    if($stmp->num_rows > 0){
        echo "Username or Email already taken.";
        $stmp->close();
        $conn->close();
        exit();
    }
    $stmp->close();

    if($password === $confirm_password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if($stmt->execute()){
            // Redirect back to the login page
            echo "Registration successful. You can now <a href='../html/login.html'>log in</a>."; 
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Passwords do not match.";
    }
}

$conn->close();
?>