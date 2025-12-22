<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Wellness Tracker</title>
    <link rel="stylesheet" href="../style/style.css"> 
    <link rel="stylesheet" href="../style/dark-mode.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="header">
        <h1>Hello, <?php echo $username; ?>!</h1> 
        <p>Your journey starts here. Take a moment to track your well-being.</p>
        
        <div class="auth-buttons">
            <a href="../php/help.php" class="button primary">Quick Support</a> 
            <a href="../php/goals.php" class="button primary">My Goals</a>
            <a href="../php/summary.php" class="button primary">Summary</a>
            <a href="../php/ai_chat.php" class="button primary">AI Chat</a>
            <a href="../php/export.php" class="button">Export to CSV</a>
            
            <div class="profile-dropdown">
                <button class="profile-dropdown-btn" id="profileDropdownBtn">
                    <svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Profile</span>
                    <span class="arrow">▼</span>
                </button>
                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="../php/admin/dashboard.php">
                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Admin Panel
                        </a>
                    <?php endif; ?>
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
        
        <section class="tracking-form">
            <h2>Track Your Day</h2>
            <div class="alert"> <?php echo $submission_message; ?></div>
            
            <form action="dashboard.php" method="post">
                
                <div class="slider-group">
                    <label for="mood_score">Your Mood (0 to 10): <span id="moodValue">5</span></label>
                    <input type="range" min="0" max="10" value="5" name="mood_score" id="mood_score" required>
                </div>

                <div class="slider-group">
                    <label for="stress_score">Your Stress Level (0 to 10): <span id="stressValue">5</span></label>
                    <input type="range" min="0" max="10" value="5" name="stress_score" id="stress_score" required>
                </div>

                <div class="journal-section">
                    <label for="notes">Journal Entry</label>
                    <p class="journal-prompt"><?php echo $journal_prompt; ?></p>
                    <textarea name="notes" id="notes" rows="5" placeholder="Reflect on your day..."></textarea>
                </div>

                <input type="submit" value="Save Entry" class="button primary">
            </form>
        </section>
        
        <section class="data-visualization container">
            <h2>Mood and Stress Trends (<?php echo $chart_label; ?>)</h2>
            <form action="dashboard.php" method="get" class="chart-period-form">
                <label for="period">Select Period:</label>
                <select name="period" id="period" onchange="this.form.submit()">
                    <option value="7" <?php if($period == 7) echo 'selected'; ?>>Last 7 Days</option>
                    <option value="30" <?php if($period == 30) echo 'selected'; ?>>Last 30 Days</option>
                    <option value="90" <?php if($period == 90) echo 'selected'; ?>>Last 90 Days</option>
                </select>
            </form>
            <canvas id="moodStressChart" width="400" height="150"></canvas>
        </section>

        <hr>
        
        <section class="past-entries">
            <h2>Your Recent Progress</h2>
            
            <?php if (empty($entries)): ?>
                <p>No entries yet! Track your first mood entry above.</p>
            <?php else: ?>
                <table class="entries-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Mood (0-10)</th>
                            <th>Stress (0-10)</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($entry['entry_date'])); ?></td>
                            <td><?php echo $entry['mood_score']; ?></td>
                            <td><?php echo $entry['stress_score']; ?></td>
                            <td><?php echo nl2br(htmlspecialchars($entry['notes'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
<script>
    document.getElementById('mood_score').oninput = function() {
        document.getElementById('moodValue').innerText = this.value;
    };
    document.getElementById('stress_score').oninput = function() {
        document.getElementById('stressValue').innerText = this.value;
    };
</script>
<script>
    // Chart.js Script
    const chartData = <?php echo $json_chart_data; ?>;
    const ctx = document.getElementById('moodStressChart');

    if (chartData.dates.length > 0) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.dates,
                datasets: [{
                    label: 'Mood Score (0-10)',
                    data: chartData.mood_scores,
                    borderColor: '#5aa65a',
                    backgroundColor: 'rgba(90, 166, 90, 0.2)',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Stress Score (0-10)',
                    data: chartData.stress_scores,
                    borderColor: '#d9534f',
                    backgroundColor: 'rgba(217, 83, 79, 0.2)',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        title: {
                            display: true,
                            text: 'Score (0 to 10)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    } else {
        ctx.style.height = '50px';
        ctx.style.display = 'block';
        ctx.parentElement.innerHTML += '<p style="text-align: center; margin-top: 10px;">Track a few days to see your trend visualization!</p>';
    }
</script>
<script>
    // Dark Mode Toggle
    const darkModeSwitch = document.getElementById('dark-mode-switch');
    const htmlElement = document.documentElement;

    // Check for saved dark mode preference
    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', currentTheme);
    if (currentTheme === 'dark') {
        darkModeSwitch.checked = true;
    }

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