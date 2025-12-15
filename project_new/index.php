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
    <title>Wellness Tracker - Home</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="header">
        <h1>Welcome to Wellness Tracker</h1>
        <p>Your private, supportive tool for tracking mood and stress.</p>
        
        <div class="auth-buttons">
            <button id="showLoginPopupBtn" class="button">Log In</button>
            <button id="showRegisterPopupBtn" class="button primary">Sign Up</button>
        </div>
    </div>
    
    <div class="container">
        <h2>Our Mission</h2>
        <p>We aim to provide a simple, non-judgmental space for you to monitor your well-being and identify helpful patterns.</p>
        
        <section>
            <h3>Features</h3>
            <ul>
                <li>Daily Mood & Stress Tracking</li>
                <li>Quick Access to Grounding Techniques</li>
                <li>Secure & Private Data Storage</li>
            </ul>
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
    </script>

</body>
</html>