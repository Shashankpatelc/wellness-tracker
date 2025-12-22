<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../../style/style.css">
    <link rel="stylesheet" href="../../style/dark-mode.css">
    <style>
         .admin-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .user-table {
            width: 100%;
            border-collapse: separate; /* Changed for border-radius */
            border-spacing: 0;
            margin-top: 1.5rem;
            background: var(--bg-secondary);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .user-table th, .user-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .user-table th {
            background: rgba(0,0,0,0.05); /* Slight contrast */
            font-weight: 600;
            color: var(--text-primary);
        }
        .user-table tr:last-child td {
            border-bottom: none;
        }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-admin {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        .badge-user {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }
        .action-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
        }
        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        .btn-delete:hover {
            background: var(--danger-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="header admin-header">
        <h1>👥 User Management</h1>
        <div class="auth-buttons">
            <a href="dashboard.php" class="button">Back to Dashboard</a>
            <a href="../logout.php" class="button">Sign Out</a>
        </div>
    </div>

    <div class="container">
        
        <?php if (!empty($message)): ?>
            <div class="alert" style="border-left-color: var(--success-color);">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert" style="border-left-color: var(--danger-color);">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?php echo $user['user_id']; ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge <?php echo ($user['role'] === 'admin') ? 'badge-admin' : 'badge-user'; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                <form action="users.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" class="action-btn btn-delete">
                                        <svg class="svg-icon" viewBox="0 0 24 24" style="width:16px; height:16px;">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg> Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: var(--text-secondary); font-size: 0.8rem;">(Current Account)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
