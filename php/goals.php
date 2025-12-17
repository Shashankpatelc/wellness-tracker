<?php
// php/goals.php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

require_once 'connect_db.php';

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$message = "";

// Handle add goal form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['goal_text'])) {
    $goal_text = trim($_POST['goal_text']);
    if (!empty($goal_text)) {
        $sql = "INSERT INTO goals (user_id, goal_text) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "is", $user_id, $goal_text);
            if (mysqli_stmt_execute($stmt)) {
                $message = "New goal added successfully!";
            } else {
                $message = "Error: Could not add goal.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Handle mark as complete/delete
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    $goal_id = $_GET['goal_id'];
    if ($_GET['action'] == 'complete') {
        $sql = "UPDATE goals SET is_completed = 1 WHERE goal_id = ? AND user_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $goal_id, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } elseif ($_GET['action'] == 'delete') {
        $sql = "DELETE FROM goals WHERE goal_id = ? AND user_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $goal_id, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch all goals for the user
$goals = [];
$sql = "SELECT goal_id, goal_text, is_completed, created_at FROM goals WHERE user_id = ? ORDER BY created_at DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $goals[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

require_once '../html/goals_view.php';
?>
