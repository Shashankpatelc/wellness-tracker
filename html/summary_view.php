<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary - Wellness Tracker</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>My Summary</h1>
        <p>An overview of your wellness journey.</p>
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
        <section class="summary-section">
            <h2>Monthly Summary</h2>
            <?php if (empty($monthly_summary)): ?>
                <p>No data available for monthly summary.</p>
            <?php else: ?>
                <table class="entries-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Average Mood</th>
                            <th>Average Stress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthly_summary as $summary): ?>
                            <tr>
                                <td><?php echo date("F Y", strtotime($summary['month'])); ?></td>
                                <td><?php echo round($summary['avg_mood'], 2); ?></td>
                                <td><?php echo round($summary['avg_stress'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <hr>

        <section class="summary-section">
            <h2>Yearly Summary</h2>
            <?php if (empty($yearly_summary)): ?>
                <p>No data available for yearly summary.</p>
            <?php else: ?>
                <table class="entries-table">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Average Mood</th>
                            <th>Average Stress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($yearly_summary as $summary): ?>
                            <tr>
                                <td><?php echo $summary['year']; ?></td>
                                <td><?php echo round($summary['avg_mood'], 2); ?></td>
                                <td><?php echo round($summary['avg_stress'], 2); ?></td>
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
