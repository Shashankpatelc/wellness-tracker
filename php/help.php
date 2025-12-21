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
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="profile-dropdown">
                    <button class="profile-dropdown-btn" id="profileDropdownBtn">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Profile</span>
                        <span class="arrow">▼</span>
                    </button>
                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <a href="profile.php">
                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> View Profile
                        </a>
                        <a href="profile.php#edit">
                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit Information
                        </a>
                        <a href="logout.php" class="danger">
                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Sign Out
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="dark-mode-toggle">
                <label for="dark-mode-switch">Dark Mode</label>
                <label class="toggle-switch">
                    <input type="checkbox" id="dark-mode-switch">
                    <span class="slider"></span>
                </label>
            </div>
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
    const htmlElement = document.documentElement;
    const darkModeSwitch = document.getElementById('dark-mode-switch');
    
    // Set initial theme from localStorage
    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', currentTheme);
    if (currentTheme === 'dark') {
        darkModeSwitch.checked = true;
    }
    
    // Listen for dark mode toggle changes
    darkModeSwitch.addEventListener('change', () => {
        const theme = darkModeSwitch.checked ? 'dark' : 'light';
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    });
    
    // Profile Dropdown Menu
    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileDropdown = profileDropdownBtn?.parentElement;
    
    if (profileDropdownBtn && profileDropdown) {
        profileDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('active');
            }
        });
    }
</script>
</body>
</html>