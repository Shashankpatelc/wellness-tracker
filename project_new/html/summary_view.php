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
    const body = document.body;
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }
</script>
</body>
</html>
