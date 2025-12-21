<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Wellness Tracker</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>👤 My Profile</h1>
        <p>Manage your account settings and view your wellness journey</p>
        
        <div class="auth-buttons">
            <a href="../php/dashboard.php" class="button">Dashboard</a>
            <a href="../php/goals.php" class="button">My Goals</a>
            <a href="../php/summary.php" class="button">Summary</a>
            <a href="../php/ai_chat.php" class="button">AI Chat</a>
            <a href="../php/help.php" class="button">Quick Support</a>
            
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
        
        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert" style="background: rgba(239, 68, 68, 0.1); border-left-color: var(--danger-color); color: var(--danger-color);">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Profile Information -->
        <section class="profile-info-section">
            <h2>Account Information</h2>
            <div class="profile-info-card">
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_data['username']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_data['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value"><?php echo date('F j, Y', strtotime($user_data['created_at'])); ?></span>
                </div>
            </div>
        </section>

        <!-- Statistics Dashboard -->
        <section class="stats-section">
            <h2>Your Wellness Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M12 20V10M6 20V16M18 20V4"></path></svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['total_entries']; ?></div>
                    <div class="stat-label">Total Entries</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M17.657 18.657A8 8 0 0 1 6.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0 1 20 13a7.975 7.975 0 0 1-2.343 5.657z"></path><path d="M9.879 16.121A3 3 0 1 0 12.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['current_streak']; ?> Days</div>
                    <div class="stat-label">Day Streak</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="svg-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['avg_mood']; ?></div>
                    <div class="stat-label">Avg Mood (30d)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['avg_stress']; ?></div>
                    <div class="stat-label">Avg Stress (30d)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['account_age_days']; ?></div>
                    <div class="stat-label">Days Active</div>
                </div>
            </div>
        </section>

        <hr>

        <!-- Edit Profile Form -->
        <section class="edit-profile-section">
            <h2>Edit Profile</h2>
            <form action="profile.php" method="post" class="profile-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>

                <input type="submit" name="update_profile" value="Update Profile" class="button primary">
            </form>
        </section>

        <hr>

        <!-- Change Password Form -->
        <section class="change-password-section">
            <h2>Change Password</h2>
            <form action="profile.php" method="post" class="profile-form">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" minlength="6" required>
                    <small style="color: var(--text-secondary); font-size: 0.875rem;">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" minlength="6" required>
                </div>

                <input type="submit" name="change_password" value="Change Password" class="button primary">
            </form>
        </section>

        <hr>

        <!-- Danger Zone -->
        <section class="danger-zone">
            <h2>⚠️ Danger Zone</h2>
            <div class="danger-card">
                <h3>Delete Account</h3>
                <p>Once you delete your account, there is no going back. This will permanently delete all your mood entries, goals, and personal data.</p>
                <button id="showDeleteModal" class="button" style="background: var(--danger-color);">Delete My Account</button>
            </div>
        </section>

    </div>

    <!-- Delete Account Confirmation Modal -->
    <div id="deleteModal" class="popup">
        <div class="popup-content">
            <span class="close" id="closeDeleteModal">&times;</span>
            <h2 style="color: var(--danger-color);">⚠️ Confirm Account Deletion</h2>
            <p>This action cannot be undone. All your data will be permanently deleted.</p>
            
            <form action="profile.php" method="post" id="deleteForm">
                <div class="form-group">
                    <label for="confirm_delete_password">Enter your password to confirm:</label>
                    <input type="password" name="confirm_delete_password" id="confirm_delete_password" required>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" id="cancelDelete" class="button" style="flex: 1;">Cancel</button>
                    <input type="submit" name="delete_account" value="Yes, Delete My Account" class="button" style="flex: 1; background: var(--danger-color);">
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeSwitch = document.getElementById('dark-mode-switch');
        const htmlElement = document.documentElement;

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

        // Delete Account Modal
        const deleteModal = document.getElementById('deleteModal');
        const showDeleteBtn = document.getElementById('showDeleteModal');
        const closeDeleteBtn = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDelete');

        showDeleteBtn.onclick = () => {
            deleteModal.style.display = 'flex';
        };

        closeDeleteBtn.onclick = () => {
            deleteModal.style.display = 'none';
        };

        cancelDeleteBtn.onclick = () => {
            deleteModal.style.display = 'none';
        };

        window.onclick = (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        };
    </script>
</body>
</html>
