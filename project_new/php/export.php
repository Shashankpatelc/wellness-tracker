<?php
// php/export.php

session_start();

// Access Control: If the user is NOT logged in, redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

require_once 'connect_db.php';

$user_id = $_SESSION["user_id"];

// Fetch all entries for the user
$sql = "SELECT entry_date, mood_score, stress_score, notes FROM mood_entries WHERE user_id = ? ORDER BY entry_date ASC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="wellness_tracker_data.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write CSV header
        fputcsv($output, ['entry_date', 'mood_score', 'stress_score', 'notes']);

        // Write data rows
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }

        fclose($output);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
exit;
?>
