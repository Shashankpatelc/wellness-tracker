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
            <a href="logout.php" class="button">Sign Out</a>
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
</script>
</body>
</html>
