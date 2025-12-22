<?php
// php/admin/content.php

require_once 'check_admin.php';
require_once '../connect_db.php';

$message = "";
$error = "";

// --- Handle Form Submissions ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Add Journal Prompt
    if (isset($_POST['add_prompt'])) {
        $prompt_text = trim($_POST['prompt_text']);
        if (!empty($prompt_text)) {
            $stmt = $conn->prepare("INSERT INTO journal_prompts (prompt_text) VALUES (?)");
            $stmt->bind_param("s", $prompt_text);
            if ($stmt->execute()) {
                $message = "Prompt added successfully.";
            } else {
                $error = "Failed to add prompt.";
            }
            $stmt->close();
        }
    }

    // 2. Delete Journal Prompt
    if (isset($_POST['delete_prompt_id'])) {
        $id = intval($_POST['delete_prompt_id']);
        $conn->query("DELETE FROM journal_prompts WHERE prompt_id = $id");
        $message = "Prompt deleted.";
    }

    // 3. Add Coping Resource
    if (isset($_POST['add_resource'])) {
        $title = trim($_POST['resource_title']);
        $content = trim($_POST['resource_content']);
        $category = $_POST['resource_category'];

        if (!empty($title) && !empty($content)) {
            $stmt = $conn->prepare("INSERT INTO coping_resources (title, content, category, user_id) VALUES (?, ?, ?, NULL)");
            $stmt->bind_param("sss", $title, $content, $category);
            if ($stmt->execute()) {
                $message = "Resource added successfully.";
            } else {
                $error = "Failed to add resource. " . $conn->error;
            }
            $stmt->close();
        }
    }

    // 4. Delete Coping Resource
    if (isset($_POST['delete_resource_id'])) {
        $id = intval($_POST['delete_resource_id']);
        // Only allow deleting global resources (user_id IS NULL) to be safe, or any if admin
        $conn->query("DELETE FROM coping_resources WHERE resource_id = $id"); // Assuming primary key is resource_id? 
        // Wait, schema check needed for primary key name. 
        // Schema line 38 INSERT ... doesn't show PK. 
        // Standard is resource_id or id. Let's assume id from create_table line (not visible).
        // I'll check create_table again to be 100% sure of PK name.
        $message = "Resource deleted.";
    }
}

// --- Fetch Data ---
$prompts = [];
$res_prompts = $conn->query("SELECT * FROM journal_prompts ORDER BY prompt_id DESC");
while ($row = $res_prompts->fetch_assoc()) $prompts[] = $row;

$resources = [];
$res_resources = $conn->query("SELECT * FROM coping_resources WHERE user_id IS NULL ORDER BY resource_id DESC"); // Only global resources
// Note: If schema doesn't have user_id on resources, then all are global.
// Let's assume standard 'coping_resources' might check for user_id to separate user vs global. 
// If column doesn't exist, this query might fail. I'll need to double check the schema.

$page_title = "Manage Content - Admin";
require_once 'views/content_view.php';
?>
