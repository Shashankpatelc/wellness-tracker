<?php
// php/ai_chat.php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /wellness-tracker/index.php");
    exit;
}

$username = $_SESSION["username"];

// Handle AJAX requests for AI chat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    header('Content-Type: application/json');
    $user_message = $_POST['message'];

    // Get user ID and fetch their recent mood/stress scores
    require_once 'connect_db.php';
    require_once '../config/ai_config.php';
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

    // Groq API Integration (OpenAI-compatible)
    $api_url = GROQ_API_URL;

    // System prompt to make AI act as a stress reliever
    $system_instruction = "You are a compassionate and empathetic stress relief coach. Your role is to help users relax, manage stress, and improve their mental wellness.

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

    $data = [
        'model' => GROQ_MODEL,
        'messages' => [
            [
                'role' => 'system',
                'content' => $system_instruction
            ],
            [
                'role' => 'user',
                'content' => $user_message
            ]
        ],
        'max_tokens' => AI_MAX_TOKENS,
        'temperature' => AI_TEMPERATURE
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\nAuthorization: Bearer " . GROQ_API_KEY . "\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 30,
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($api_url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        $ai_response = "I'm having trouble connecting right now. Please check your API key and try again.";
        error_log("Groq API Connection Error: " . ($error['message'] ?? 'Unknown error'));
    } else {
        $response_data = json_decode($result, true);
        
        // Check for API errors
        if (isset($response_data['error'])) {
            $error_message = $response_data['error']['message'] ?? 'Unknown API error';
            $ai_response = "API Error: " . $error_message;
            error_log("Groq API Error Response: " . json_encode($response_data['error']));
        } elseif (isset($response_data['choices'][0]['message']['content'])) {
            $ai_response = $response_data['choices'][0]['message']['content'];
        } else {
            $ai_response = "I'm having trouble understanding. Could you rephrase that?";
            error_log("Groq API Unexpected Response Structure: " . $result);
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

// Capture session flags BEFORE clearing them (for view to use)
$show_sentiment_notification = isset($_SESSION['sentiment_redirect']) && $_SESSION['sentiment_redirect'] === true;
$sentiment_prompt = $_SESSION['ai_pre_prompt'] ?? null;

require_once '../html/ai_chat_view.php';

// Clear sentiment redirect flags AFTER view has displayed them
if (isset($_SESSION['sentiment_redirect'])) {
    unset($_SESSION['sentiment_redirect']);
    unset($_SESSION['ai_pre_prompt']);
}
?>
