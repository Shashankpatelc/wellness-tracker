<?php
// php/profile.php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../html/login.html");
    exit;
}

require_once 'connect_db.php';

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$message = "";
$error = "";

// Fetch current user data
$user_data = [];
$sql = "SELECT username, email, created_at FROM users WHERE user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $user_data = $row;
        }
    }
    mysqli_stmt_close($stmt);
}

// Calculate user statistics
$stats = [
    'total_entries' => 0,
    'current_streak' => 0,
    'avg_mood' => 'No data',
    'avg_stress' => 'No data',
    'account_age_days' => 0
];

// Total entries
$sql = "SELECT COUNT(*) as total FROM mood_entries WHERE user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $stats['total_entries'] = $row['total'];
        }
    }
    mysqli_stmt_close($stmt);
}

// Calculate current streak
$sql = "SELECT entry_date FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $dates = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $dates[] = $row['entry_date'];
        }
        
        // Calculate streak
        $streak = 0;
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        if (!empty($dates)) {
            // Check if there's an entry today or yesterday
            if ($dates[0] == $today || $dates[0] == $yesterday) {
                $streak = 1;
                $current_date = new DateTime($dates[0]);
                
                for ($i = 1; $i < count($dates); $i++) {
                    $prev_date = new DateTime($dates[$i]);
                    $diff = $current_date->diff($prev_date)->days;
                    
                    if ($diff == 1) {
                        $streak++;
                        $current_date = $prev_date;
                    } else {
                        break;
                    }
                }
            }
        }
        $stats['current_streak'] = $streak;
    }
    mysqli_stmt_close($stmt);
}

// Average mood and stress (last 30 days)
$sql = "SELECT AVG(mood_score) as avg_mood, AVG(stress_score) as avg_stress FROM mood_entries WHERE user_id = ? AND entry_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $stats['avg_mood'] = $row['avg_mood'] ? round($row['avg_mood'], 1) : 'No data';
            $stats['avg_stress'] = $row['avg_stress'] ? round($row['avg_stress'], 1) : 'No data';
        }
    }
    mysqli_stmt_close($stmt);
}

// Account age
if (!empty($user_data['created_at'])) {
    $created = new DateTime($user_data['created_at']);
    $now = new DateTime();
    $stats['account_age_days'] = $created->diff($now)->days;
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    
    $username_err = $email_err = "";
    
    // Validate username
    if (empty($new_username)) {
        $username_err = "Username cannot be empty.";
    } elseif ($new_username != $user_data['username']) {
        // Check if username is already taken
        $sql = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_username, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $username_err = "This username is already taken.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate email
    if (empty($new_email)) {
        $email_err = "Email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } elseif ($new_email != $user_data['email']) {
        // Check if email is already taken
        $sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_email, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "This email is already registered.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Update if no errors
    if (empty($username_err) && empty($email_err)) {
        $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $new_username, $new_email, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION["username"] = $new_username;
                $user_data['username'] = $new_username;
                $user_data['email'] = $new_email;
                $message = "Profile updated successfully!";
            } else {
                $error = "Error updating profile. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $error = $username_err . " " . $email_err;
    }
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $password_err = "";
    
    // Verify current password
    $sql = "SELECT password_hash FROM users WHERE user_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (!password_verify($current_password, $row['password_hash'])) {
                    $password_err = "Current password is incorrect.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    // Validate new password
    if (empty($password_err)) {
        if (strlen($new_password) < 6) {
            $password_err = "New password must be at least 6 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $password_err = "New passwords do not match.";
        }
    }
    
    // Update password if no errors
    if (empty($password_err)) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_password_hash, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Password changed successfully!";
            } else {
                $error = "Error changing password. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $error = $password_err;
    }
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $confirm_password = $_POST['confirm_delete_password'];
    
    // Verify password
    $sql = "SELECT password_hash FROM users WHERE user_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($confirm_password, $row['password_hash'])) {
                    // Delete user account (cascade will delete related data)
                    $sql_delete = "DELETE FROM users WHERE user_id = ?";
                    if ($stmt_delete = mysqli_prepare($conn, $sql_delete)) {
                        mysqli_stmt_bind_param($stmt_delete, "i", $user_id);
                        if (mysqli_stmt_execute($stmt_delete)) {
                            mysqli_stmt_close($stmt_delete);
                            mysqli_close($conn);
                            session_destroy();
                            header("location: ../index.php?deleted=1");
                            exit;
                        }
                        mysqli_stmt_close($stmt_delete);
                    }
                } else {
                    $error = "Incorrect password. Account not deleted.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);

require_once '../html/profile_view.php';
?>
