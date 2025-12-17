<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// dashboard.php (Controller - Full Code Snippet for reference)
// php/dashboard.php (The CONTROLLER)

session_start();

// Access Control: If the user is NOT logged in, redirect them to the login page (root to html)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

// Variables defined for the HTML view
$username = htmlspecialchars($_SESSION["username"]);
$user_id = $_SESSION["user_id"];
$submission_message = "";
$entries = []; 
$json_chart_data = json_encode(['dates' => [], 'mood_scores' => [], 'stress_scores' => []]);

require_once __DIR__ . '/connect_db.php'; // Connection is in the same folder

// === 1. Data Insertion Logic (Handles POST Request from the form) ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mood_score'])) {
    
    // Sanitize and validate inputs
    $mood_score = filter_input(INPUT_POST, 'mood_score', FILTER_VALIDATE_INT);
    $stress_score = filter_input(INPUT_POST, 'stress_score', FILTER_VALIDATE_INT);
    $notes = trim($_POST['notes']);
    $user_id = $_SESSION['user_id'];
    $entry_date = date("Y-m-d"); 

    $mood_err = $stress_err = "";

    if ($mood_score === false || $mood_score < 0 || $mood_score > 10) { $mood_err = "Invalid mood score."; }
    if ($stress_score === false || $stress_score < 0 || $stress_score > 10) { $stress_err = "Invalid stress score."; }

    if (empty($mood_err) && empty($stress_err)) {
        
        // 2. Check for duplicate entry 
        $check_sql = "SELECT entry_id FROM mood_entries WHERE user_id = ? AND entry_date = ?";
        if ($check_stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($check_stmt, "is", $user_id, $entry_date);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                // Update existing entry
                $update_sql = "UPDATE mood_entries SET mood_score = ?, stress_score = ?, notes = ? WHERE user_id = ? AND entry_date = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "iisis", $mood_score, $stress_score, $notes, $user_id, $entry_date);
                    if (mysqli_stmt_execute($update_stmt)) {
                        $submission_message = "Your entry for today has been updated.";
                    } else {
                        $submission_message = "Error: Could not update entry. " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($update_stmt);
                }
            } else {
                // 3. Insert the new entry
                $insert_sql = "INSERT INTO mood_entries (user_id, mood_score, stress_score, notes, entry_date) VALUES (?, ?, ?, ?, ?)";
                if ($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
                    mysqli_stmt_bind_param($insert_stmt, "iiiss", $user_id, $mood_score, $stress_score, $notes, $entry_date);
                    if (mysqli_stmt_execute($insert_stmt)) {
                        $submission_message = "Thank you! Your entry has been securely saved.";
                    } else {
                        $submission_message = "Error: Could not save entry. " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($insert_stmt);
                }
            }
            mysqli_stmt_close($check_stmt);
        }
    }
}


// Fetch a random journal prompt
$journal_prompt = "";
$sql_prompt = "SELECT prompt_text FROM journal_prompts ORDER BY RAND() LIMIT 1";
if ($result_prompt = mysqli_query($conn, $sql_prompt)) {
    if ($row_prompt = mysqli_fetch_assoc($result_prompt)) {
        $journal_prompt = $row_prompt['prompt_text'];
    }
}

// === 2. Data Retrieval Logic (For Table and Chart) ===
$period = isset($_GET['period']) ? (int)$_GET['period'] : 7;
$chart_label = "Last {$period} Days";

$entries = []; 
$chart_data = [
    'dates' => [],
    'mood_scores' => [],
    'stress_scores' => []
];

// Fetch entries for the chart (ORDER BY ASC for chronological chart)
$sql = "SELECT entry_date, mood_score, stress_score, notes FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT ?"; 

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $period);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Data for the Chart (Chronological order)
            $chart_data['dates'][] = date('M j', strtotime($row['entry_date']));
            $chart_data['mood_scores'][] = $row['mood_score'];
            $chart_data['stress_scores'][] = $row['stress_score'];
            
            // Data for the HTML Table (Will be reversed later)
            $entries[] = $row; 
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

// Reverse the chart data to be in chronological order
$chart_data['dates'] = array_reverse($chart_data['dates']);
$chart_data['mood_scores'] = array_reverse($chart_data['mood_scores']);
$chart_data['stress_scores'] = array_reverse($chart_data['stress_scores']);

// Convert PHP array to JSON string for use in JavaScript
$json_chart_data = json_encode($chart_data);

// FIX: Load the Dashboard HTML View (up one level, then into html/)
require_once '../html/dashboard_view.php'; 
?>