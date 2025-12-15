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

    // Ollama API Integration
    $ollama_url = 'http://localhost:11434/api/generate';
    $model_name = 'llama3:8b'; // Use the model specified by the user

    $data = [
        'model' => $model_name,
        'prompt' => $user_message,
        'stream' => false // We want the full response at once
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 60, // Timeout in seconds
        ],
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($ollama_url, false, $context);

    if ($result === FALSE) {
        $ai_response = "Error: Could not connect to Ollama API or API call failed.";
        error_log("Ollama API Error: " . error_get_last()['message']);
    } else {
        $response_data = json_decode($result, true);
        if (isset($response_data['response'])) {
            $ai_response = $response_data['response'];
        } else {
            $ai_response = "Error: Unexpected response from Ollama API.";
            error_log("Ollama API Unexpected Response: " . $result);
        }
    }

    echo json_encode(['response' => $ai_response]);
    exit;
}

require_once '../html/ai_chat_view.php';
?>
