<?php
// php/admin/users.php

require_once 'check_admin.php'; // Protect this page
require_once '../connect_db.php';

$message = "";
$error = "";

// --- Handle User Deletion ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
    $delete_id = intval($_POST['delete_user_id']);
    
    // Prevent deleting yourself
    if ($delete_id == $_SESSION['user_id']) {
        $error = "You cannot delete your own admin account.";
    } else {
        // Delete user (Cascading delete handles linked data IF set up, otherwise we might need manual cleanup)
        // Check schema: Usually goals/entries have ON DELETE CASCADE. If not, we should delete them first.
        // Let's assume basic DELETE for users works for now, or we delete dependencies.
        
        // 1. Delete Dependencies (Manual approach to be safe)
        $conn->query("DELETE FROM mood_entries WHERE user_id = $delete_id");
        $conn->query("DELETE FROM goals WHERE user_id = $delete_id");
        
        // 2. Delete User
        $sql = "DELETE FROM users WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                $message = "User deleted successfully.";
            } else {
                $error = "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}

// --- Fetch All Users ---
$users = [];
$sql = "SELECT user_id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$page_title = "Manage Users - Admin";
require_once 'views/users_view.php';
?>
