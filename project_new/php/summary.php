<?php
// php/summary.php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

require_once 'connect_db.php';

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch monthly summary
$monthly_summary = [];
$sql_monthly = "SELECT 
                    DATE_FORMAT(entry_date, '%Y-%m') as month, 
                    AVG(mood_score) as avg_mood, 
                    AVG(stress_score) as avg_stress 
                FROM mood_entries 
                WHERE user_id = ? 
                GROUP BY month 
                ORDER BY month DESC";

if ($stmt_monthly = mysqli_prepare($conn, $sql_monthly)) {
    mysqli_stmt_bind_param($stmt_monthly, "i", $user_id);
    if (mysqli_stmt_execute($stmt_monthly)) {
        $result_monthly = mysqli_stmt_get_result($stmt_monthly);
        while ($row_monthly = mysqli_fetch_assoc($result_monthly)) {
            $monthly_summary[] = $row_monthly;
        }
    }
    mysqli_stmt_close($stmt_monthly);
}

// Fetch yearly summary
$yearly_summary = [];
$sql_yearly = "SELECT 
                    DATE_FORMAT(entry_date, '%Y') as year, 
                    AVG(mood_score) as avg_mood, 
                    AVG(stress_score) as avg_stress 
                FROM mood_entries 
                WHERE user_id = ? 
                GROUP BY year 
                ORDER BY year DESC";

if ($stmt_yearly = mysqli_prepare($conn, $sql_yearly)) {
    mysqli_stmt_bind_param($stmt_yearly, "i", $user_id);
    if (mysqli_stmt_execute($stmt_yearly)) {
        $result_yearly = mysqli_stmt_get_result($stmt_yearly);
        while ($row_yearly = mysqli_fetch_assoc($result_yearly)) {
            $yearly_summary[] = $row_yearly;
        }
    }
    mysqli_stmt_close($stmt_yearly);
}

mysqli_close($conn);

require_once '../html/summary_view.php';
?>
