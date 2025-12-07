<?php
// B4/B5: Tracking Input & Data Retrieval
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'connect_db.php'; 

$submission_message = "";

// === Data Insertion Logic (B4) ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Logic to validate and INSERT data into mood_entries table
    // (Ensure you check for one entry per day per user before inserting)
}

// === Data Retrieval Logic (B5) ===
$entries = [];
$sql = "SELECT entry_date, mood_score, stress_score, notes FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT 7";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $entries[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>