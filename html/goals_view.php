<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Goals - Wellness Tracker</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>My Goals</h1>
        <p>Set and track your personal wellness goals.</p>
        <div class="auth-buttons">
            <a href="dashboard.php" class="button">Dashboard</a>
            
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
        <section class="add-goal-form">
            <h2>Add a New Goal</h2>
            <?php if(!empty($message)): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="goals.php" method="post">
                <div>
                    <label for="goal_text">Goal:</label>
                    <textarea name="goal_text" id="goal_text" rows="3" placeholder="e.g., Meditate for 10 minutes every day"></textarea>
                </div>
                <input type="submit" value="Add Goal" class="button primary">
            </form>
        </section>

        <hr>

        <section class="goals-list">
            <h2>Your Goals</h2>
            <?php if (empty($goals)): ?>
                <p>No goals set yet. Add your first goal above!</p>
            <?php else: ?>
                <table class="entries-table">
                    <thead>
                        <tr>
                            <th>Goal</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($goals as $goal): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($goal['goal_text']); ?></td>
                                <td><?php echo $goal['is_completed'] ? 'Completed' : 'In Progress'; ?></td>
                                <td>
                                    <?php if (!$goal['is_completed']): ?>
                                        <a href="goals.php?action=complete&goal_id=<?php echo $goal['goal_id']; ?>" class="button">Complete</a>
                                    <?php endif; ?>
                                    <a href="goals.php?action=delete&goal_id=<?php echo $goal['goal_id']; ?>" class="button">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
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
