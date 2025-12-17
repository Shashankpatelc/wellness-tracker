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

    // Get user ID and fetch their recent mood/stress scores
    require_once 'connect_db.php';
    $user_id = $_SESSION["user_id"];
    
    // Fetch latest mood and stress scores
    $user_stats = [
        'latest_mood' => 'Unknown',
        'latest_stress' => 'Unknown',
        'avg_mood' => 'Unknown',
        'avg_stress' => 'Unknown'
    ];
    
    // Get latest entry
    $sql = "SELECT mood_score, stress_score FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $user_stats['latest_mood'] = $row['mood_score'];
                $user_stats['latest_stress'] = $row['stress_score'];
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    // Get average scores for the month
    $sql = "SELECT AVG(mood_score) as avg_mood, AVG(stress_score) as avg_stress FROM mood_entries WHERE user_id = ? AND entry_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $user_stats['avg_mood'] = round($row['avg_mood'], 1);
                $user_stats['avg_stress'] = round($row['avg_stress'], 1);
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);

    // Ollama API Integration
    $ollama_url = 'http://localhost:11434/api/generate';
    $model_name = 'llama3:8b'; // Use the model specified by the user

    // System prompt to make AI act as a stress reliever
    $system_prompt = "You are a compassionate and empathetic stress relief coach. Your role is to help users relax, manage stress, and improve their mental wellness.

USER WELLNESS DATA:
- Current Mood Score: " . $user_stats['latest_mood'] . "/10
- Current Stress Score: " . $user_stats['latest_stress'] . "/10
- Average Mood (Last 30 days): " . $user_stats['avg_mood'] . "/10
- Average Stress (Last 30 days): " . $user_stats['avg_stress'] . "/10

IMPORTANT: Use this data to provide personalized, contextual support. Acknowledge their current state and tailor your suggestions accordingly.

Follow these guidelines:
1. Always be warm, supportive, and non-judgmental
2. Listen actively and validate their feelings
3. Reference their wellness data to show you care about their progress
4. Offer practical stress-relief techniques like deep breathing, meditation, or mindfulness exercises
5. Suggest relaxation methods appropriate to their situation and current stress level
6. Use calming language and positive affirmations
7. Ask follow-up questions to understand their stress better
8. Provide coping strategies and mental health tips based on their scores
9. Remind them that seeking professional help is okay
10. **MOST IMPORTANT: Keep responses SHORT and concise (2-4 sentences maximum)**
11. Always maintain a peaceful and supportive tone
12. Celebrate improvements in their scores when relevant

RESPONSE FORMAT: Your responses should be brief, focused, and actionable. Never provide lengthy explanations or multiple paragraphs.

Remember: You are here to help them feel better, not to diagnose or replace professional medical advice.";

    // Combine system prompt with user message
    $full_prompt = $system_prompt . "\n\nUser: " . $user_message . "\n\nStress Relief Coach:";

    $data = [
        'model' => $model_name,
        'prompt' => $full_prompt,
        'stream' => false, // We want the full response at once
        'num_predict' => 150 // Limit response to approximately 150 tokens (roughly 100-150 words)
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

// Load user stats for display in the view (when not handling AJAX)
require_once 'connect_db.php';
$user_id = $_SESSION["user_id"];

$user_stats = [
    'latest_mood' => 'No data',
    'latest_stress' => 'No data',
    'avg_mood' => 'No data',
    'avg_stress' => 'No data'
];

// Get latest entry
$sql = "SELECT mood_score, stress_score FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT 1";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $user_stats['latest_mood'] = $row['mood_score'];
            $user_stats['latest_stress'] = $row['stress_score'];
        }
    }
    mysqli_stmt_close($stmt);
}

// Get average scores for the month
$sql = "SELECT AVG(mood_score) as avg_mood, AVG(stress_score) as avg_stress FROM mood_entries WHERE user_id = ? AND entry_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $user_stats['avg_mood'] = $row['avg_mood'] ? round($row['avg_mood'], 1) : 'No data';
            $user_stats['avg_stress'] = $row['avg_stress'] ? round($row['avg_stress'], 1) : 'No data';
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

require_once '../html/ai_chat_view.php';
?>
