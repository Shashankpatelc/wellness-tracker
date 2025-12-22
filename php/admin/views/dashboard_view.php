<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Wellness Tracker</title>
    <link rel="stylesheet" href="../../style/style.css">
    <link rel="stylesheet" href="../../style/dark-mode.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); /* Darker theme for Admin */
        }
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .admin-stat-card {
            background: var(--bg-secondary);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            text-align: center;
        }
        .admin-stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .admin-nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .admin-nav-card {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }
        .admin-nav-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="header admin-header">
        <h1>🛡️ Admin Dashboard</h1>
        <p>System Overview & Management</p>
        <div class="auth-buttons">
            <a href="../dashboard.php" class="button">Back to App</a>
            <a href="../logout.php" class="button">Sign Out</a>
        </div>
    </div>

    <div class="container">
        <h2>System Statistics</h2>
        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <div class="admin-stat-value"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-value"><?php echo $new_users; ?></div>
                <div class="stat-label">New Users (7 Days)</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-value"><?php echo $total_entries; ?></div>
                <div class="stat-label">Total Mood Entries</div>
            </div>
        </div>

        <hr>

        <h2>Management Tools</h2>
        <div class="admin-nav-grid">
            <a href="users.php" class="admin-nav-card">
                <h3>👥 User Management</h3>
                <p>View, Ban, or Delete Users</p>
            </a>
            <a href="content.php" class="admin-nav-card">
                <h3>📝 Content Management</h3>
                <p>Manage Prompts & Resources</p>
            </a>
        </div>
    </div>
</body>
</html>
