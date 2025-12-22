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
        .content-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            margin-bottom: 2rem;
        }
        .content-table th, .content-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .content-table th {
            font-weight: 600;
            color: var(--text-primary);
        }
        .form-row {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            margin-top: 1rem;
        }
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        .delete-btn {
            background: none;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            padding: 0.25rem;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header admin-header">
        <h1>📝 Content Management</h1>
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

        <!-- JOURNAL PROMPTS SECTION -->
        <div class="content-section">
            <h2>Daily Journal Prompts</h2>
            <p>These prompts appear randomly when users create a new mood entry.</p>

            <table class="content-table">
                <thead>
                    <tr>
                        <th style="width: 80%;">Prompt Text</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prompts as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['prompt_text']); ?></td>
                            <td style="text-align: right;">
                                <form action="content.php" method="post" onsubmit="return confirm('Delete this prompt?');">
                                    <input type="hidden" name="delete_prompt_id" value="<?php echo $p['prompt_id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Add New Prompt</h3>
            <form action="content.php" method="post" class="form-row">
                <div class="form-group" style="flex: 4;">
                    <input type="text" name="prompt_text" placeholder="e.g., What made you feel proud today?" required>
                </div>
                <button type="submit" name="add_prompt" class="button primary">Add Prompt</button>
            </form>
        </div>

        <!-- COPING RESOURCES SECTION -->
        <div class="content-section">
            <h2>Coping Resources (Global)</h2>
            <p>Helpful resources available to all users in the 'Quick Support' section.</p>

            <table class="content-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Content/Description</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resources as $r): ?>
                        <tr>
                            <td><span class="badge"><?php echo htmlspecialchars($r['category']); ?></span></td>
                            <td>**<?php echo htmlspecialchars($r['title']); ?>**</td>
                            <td><?php echo htmlspecialchars(substr($r['content'], 0, 50)) . (strlen($r['content']) > 50 ? '...' : ''); ?></td>
                            <td style="text-align: right;">
                                <form action="content.php" method="post" onsubmit="return confirm('Delete this resource?');">
                                    <input type="hidden" name="delete_resource_id" value="<?php echo $r['resource_id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Add New Resource</h3>
            <form action="content.php" method="post" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; gap: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="resource_category" placeholder="Category (e.g., Meditation)" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="resource_title" placeholder="Title (e.g., 5-Minute Breathing)" required>
                    </div>
                </div>
                <!-- Fixed: Ensure the text input for description covers full width -->
                <div class="form-group">
                    <textarea name="resource_content" placeholder="Description or Instructions..." rows="2" style="width: 100%; border-radius: 8px; border: 1px solid var(--border-color); padding: 0.8rem; background: var(--bg-primary); color: var(--text-primary);"></textarea>
                </div>
                <button type="submit" name="add_resource" class="button primary" style="align-self: flex-start;">Add Resource</button>
            </form>
        </div>

    </div>
</body>
</html>
