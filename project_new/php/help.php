<?php
// php/help.php

session_start();

require_once 'connect_db.php'; 

$message = "";

// Handle add resource form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_SESSION['user_id'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO coping_resources (category, title, content, sort_order, user_id) VALUES ('User-Defined', ?, ?, 99, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $message = "New resource added successfully!";
            } else {
                $message = "Error: Could not add resource.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$resources = [];
$user_resources = [];

// Fetch all coping resources
$sql = "SELECT category, title, content, user_id FROM coping_resources ORDER BY sort_order ASC, category";

if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['category'] == 'User-Defined' && $row['user_id'] == $_SESSION['user_id']) {
            $user_resources[] = $row;
        } elseif ($row['category'] != 'User-Defined') {
            $resources[] = $row;
        }
    }
    mysqli_free_result($result);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quick Support & Resources</title>
    <link rel="stylesheet" href="../style/style.css"> 
    <link rel="stylesheet" href="../style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>Immediate Support & Coping Resources</h1>
        <p>If you are in crisis, please use the contact resources below. You are not alone.</p>
        <div class="auth-buttons">
            <a href="<?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? 'dashboard.php' : '../index.php'; ?>" class="button primary">Back to App</a>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <section class="add-resource-form">
            <h2>Add Your Own Resource</h2>
            <?php if(!empty($message)): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="help.php" method="post">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div>
                    <label for="content">Content:</label>
                    <textarea name="content" id="content" rows="3" required></textarea>
                </div>
                <input type="submit" value="Add Resource" class="button primary">
            </form>
        </section>
        <hr>
        <?php endif; ?>

        <?php 
        $current_category = '';
        foreach ($resources as $resource): 
            if ($resource['category'] !== $current_category): 
                if ($current_category !== '') echo '</div>'; 
                
                $current_category = $resource['category'];
                echo "<h3>{$current_category}</h3>";
                echo '<div class="resource-group">';
            endif;
        ?>
            <div class="resource-item">
                <h4><?php echo htmlspecialchars($resource['title']); ?></h4>
                <p><?php echo nl2br(htmlspecialchars($resource['content'])); ?></p>
            </div>
        <?php 
        endforeach; 
        if ($current_category !== '') echo '</div>';
        ?>

        <?php if (!empty($user_resources)): ?>
            <h3>Your Personal Resources</h3>
            <div class="resource-group">
            <?php foreach ($user_resources as $resource): ?>
                <div class="resource-item">
                    <h4><?php echo htmlspecialchars($resource['title']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($resource['content'])); ?></p>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<script>
    // Dark Mode Toggle
    const body = document.body;
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }
</script>
</body>
</html>