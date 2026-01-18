<?php
// php/export.php

session_start();

// Access Control: If the user is NOT logged in, redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /wellness-tracker/index.php");
    exit;
}

require_once 'connect_db.php';

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="wellness_tracker_data_' . date('Y-m-d') . '.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add BOM for proper UTF-8 encoding in Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// === EXPORT HEADER ===
fputcsv($output, ['Wellness Tracker - Complete Data Export']);
fputcsv($output, ['User: ' . $username]);
fputcsv($output, ['Export Date: ' . date('Y-m-d H:i:s')]);
fputcsv($output, []); // Blank line

// === MOOD TRACKING DATA ===
fputcsv($output, ['=== MOOD TRACKING ENTRIES ===']);
fputcsv($output, ['Date', 'Mood Score', 'Stress Score', 'Journal Notes']);

$mood_count = 0;
$sql = "SELECT entry_date, mood_score, stress_score, notes FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['entry_date'],
                $row['mood_score'],
                $row['stress_score'],
                $row['notes']
            ]);
            $mood_count++;
        }
    }
    mysqli_stmt_close($stmt);
}

if ($mood_count == 0) {
    fputcsv($output, ['No mood tracking entries found']);
}

fputcsv($output, []); // Blank line
fputcsv($output, ['Total Mood Entries: ' . $mood_count]);
fputcsv($output, []); // Blank line
fputcsv($output, []); // Extra blank line

// === WELLNESS GOALS ===
fputcsv($output, ['=== WELLNESS GOALS ===']);
fputcsv($output, ['Goal', 'Status', 'Created Date']);

$goals_count = 0;
$sql = "SELECT goal_text, is_completed, created_at FROM goals WHERE user_id = ? ORDER BY created_at DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['goal_text'],
                $row['is_completed'] ? 'Completed' : 'In Progress',
                date('Y-m-d', strtotime($row['created_at']))
            ]);
            $goals_count++;
        }
    }
    mysqli_stmt_close($stmt);
}

if ($goals_count == 0) {
    fputcsv($output, ['No wellness goals found']);
}

fputcsv($output, []); // Blank line
fputcsv($output, ['Total Goals: ' . $goals_count]);

fclose($output);
mysqli_close($conn);
exit;
?>
