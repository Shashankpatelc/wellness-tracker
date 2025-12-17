<?php
// index.php (in Project Root)

// FIX: Use the modular connection file (connect_db.php is in php/ folder)
require_once 'php/connect_db.php'; 

// The rest of the HTML template remains the same.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Tracker - Your Personal Well-Being Companion</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>🌟 Wellness Tracker</h1>
        <p>Your private, supportive companion for tracking mood and managing stress</p>
        
        <div class="auth-buttons">
            <button id="showLoginPopupBtn" class="button">Log In</button>
            <button id="showRegisterPopupBtn" class="button primary">Sign Up Free</button>
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
        <section class="mission-section text-center">
            <h2>Welcome to Your Wellness Journey</h2>
            <p>Take control of your mental health with our simple, non-judgmental tracking platform.</p>
        </section>

        <section class="features-section">
            <h3>Why Choose Wellness Tracker?</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h4>Track Your Mood</h4>
                    <p>Monitor your daily mood and stress levels with our intuitive interface. Identify patterns and trends over time.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h4>100% Private & Secure</h4>
                    <p>Your data is encrypted and stored securely. Only you can access your personal wellness records.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🧘</div>
                    <h4>Grounding Techniques</h4>
                    <p>Get instant access to calming exercises and grounding techniques when you need them most.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📈</div>
                    <h4>Visual Insights</h4>
                    <p>Beautiful charts and graphs help you understand your emotional patterns at a glance.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h4>Journal Entries</h4>
                    <p>Reflect on your day with guided journal prompts. Write freely and process your emotions.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🤝</div>
                    <h4>Supportive Community</h4>
                    <p>Connect with others on their wellness journey. Find encouragement and share experiences.</p>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <h3>How It Works</h3>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h4>Sign Up</h4>
                    <p>Create your free account in seconds</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h4>Track Daily</h4>
                    <p>Log your mood, stress, and journal entries</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h4>Get Insights</h4>
                    <p>View trends and understand your patterns</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h4>Improve Wellbeing</h4>
                    <p>Use insights to make positive changes</p>
                </div>
            </div>
        </section>

        <section class="cta-section text-center">
            <h3>Ready to Start Your Journey?</h3>
            <p>Join thousands of people taking control of their mental health today.</p>
            <button id="showRegisterPopupBtn2" class="button primary" style="padding: 1rem 2rem; font-size: 1.1rem;">Get Started Now</button>
        </section>
    </div>

    <!-- The Popup Structure -->
    <div id="authPopup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopupBtn">&times;</span>
            <div id="popup-body">
                <!-- Login or Register form will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        const showLoginPopupBtn = document.getElementById("showLoginPopupBtn");
        const showRegisterPopupBtn = document.getElementById("showRegisterPopupBtn");
        const showRegisterPopupBtn2 = document.getElementById("showRegisterPopupBtn2");
        const authPopup = document.getElementById("authPopup");
        const closePopupBtn = document.getElementById("closePopupBtn");
        const popupBody = document.getElementById("popup-body");

        function openPopup() {
            authPopup.style.display = "flex"; // Use flex for centering
        }

        function closePopup() {
            authPopup.style.display = "none";
            popupBody.innerHTML = ''; // Clear popup content on close
        }

        showLoginPopupBtn.onclick = () => {
            loadFormIntoPopup('login');
            openPopup();
        };

        showRegisterPopupBtn.onclick = () => {
            loadFormIntoPopup('register');
            openPopup();
        };

        showRegisterPopupBtn2.onclick = () => {
            loadFormIntoPopup('register');
            openPopup();
        };

        closePopupBtn.onclick = () => {
            closePopup();
        };

        window.onclick = (e) => {
            if (e.target === authPopup) {
                closePopup();
            }
        };

        function loadFormIntoPopup(formType) {
            let url = '';
            if (formType === 'login') {
                url = 'html/login.html';
            } else if (formType === 'register') {
                url = 'html/register.html';
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    popupBody.innerHTML = html;
                    
                    // Re-attach event listeners for links inside the loaded forms
                    const switchToRegisterLink = popupBody.querySelector('#switch-to-register-popup');
                    const switchToLoginLink = popupBody.querySelector('#switch-to-login-popup');

                    if (switchToRegisterLink) {
                        switchToRegisterLink.addEventListener('click', (e) => {
                            e.preventDefault();
                            loadFormIntoPopup('register');
                        });
                    }
                    if (switchToLoginLink) {
                        switchToLoginLink.addEventListener('click', (e) => {
                            e.preventDefault();
                            loadFormIntoPopup('login');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading form:', error);
                    popupBody.innerHTML = '<p>Error loading form. Please try again.</p>';
                });
        }

        // ===== DARK MODE TOGGLE ===== 
        const darkModeSwitch = document.getElementById('dark-mode-switch');
        const htmlElement = document.documentElement;

        // Check for saved dark mode preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            darkModeSwitch.checked = true;
        }

        // Listen for toggle change
        darkModeSwitch.addEventListener('change', () => {
            const theme = darkModeSwitch.checked ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });
    </script>

</body>
</html>