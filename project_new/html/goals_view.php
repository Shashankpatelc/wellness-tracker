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
            <a href="logout.php" class="button">Sign Out</a>
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
    const darkModeSwitch = document.getElementById('dark-mode-switch');
    const body = document.body;

    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        if(darkModeSwitch) darkModeSwitch.checked = true;
    }

    if(darkModeSwitch){
        darkModeSwitch.addEventListener('change', () => {
            if (darkModeSwitch.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }
</script>
</body>
</html>
