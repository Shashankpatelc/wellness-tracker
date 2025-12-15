<?php
// php/ai_chat.php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

$username = $_SESSION["username"];

// Handle AJAX requests for AI chat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    header('Content-Type: application/json');
    $user_message = $_POST['message'];

    // --- Mock AI Response Logic ---
    $ai_response = "That's an interesting thought, " . $username . ". Tell me more about it.";
    
    if (strpos(strtolower($user_message), 'hello') !== false || strpos(strtolower($user_message), 'hi') !== false) {
        $ai_response = "Hello there, " . $username . "! How can I help you find some peace today?";
    } elseif (strpos(strtolower($user_message), 'stress') !== false || strpos(strtolower($user_message), 'anxious') !== false) {
        $ai_response = "It sounds like you're feeling some stress. Remember to take a deep breath. What's on your mind?";
    } elseif (strpos(strtolower($user_message), 'mood') !== false || strpos(strtolower($user_message), 'happy') !== false) {
        $ai_response = "That's wonderful to hear! What made you feel happy today?";
    } elseif (strpos(strtolower($user_message), 'sad') !== false || strpos(strtolower($user_message), 'down') !== false) {
        $ai_response = "I'm sorry to hear that you're feeling down. It's okay to feel this way. Would you like to talk about it?";
    } elseif (strpos(strtolower($user_message), 'thank you') !== false || strpos(strtolower($user_message), 'thanks') !== false) {
        $ai_response = "You're very welcome, " . $username . "! I'm always here to support you.";
    }

    echo json_encode(['response' => $ai_response]);
    exit;
}

require_once '../html/ai_chat_view.php';
?>
