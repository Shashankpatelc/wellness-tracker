<?php
// php/admin/dashboard.php

require_once 'check_admin.php';
require_once '../connect_db.php';

// --- Fetch System Statistics ---

// 1. Total Users
$total_users = 0;
$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $total_users = $row['count'];
}

// 2. Total Mood Entries
$total_entries = 0;
$sql = "SELECT COUNT(*) as count FROM mood_entries";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $total_entries = $row['count'];
}

// 3. New Users (Last 7 Days)
$new_users = 0;
$sql = "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $new_users = $row['count'];
}

$page_title = "Admin Dashboard";
require_once 'views/dashboard_view.php';
?>
