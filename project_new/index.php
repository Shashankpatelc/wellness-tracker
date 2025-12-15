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
            <a href="#" id="show-login-modal" class="button">Log In</a>
            <a href="#" id="show-register-modal" class="button primary">Sign Up</a>
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

    <!-- The Modal Structure -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div id="modal-body">
                <!-- Login or Register form will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // JavaScript for Modal functionality will go here
        const authModal = document.getElementById('authModal');
        const closeButton = document.querySelector('.close-button');
        const showLoginModalButton = document.getElementById('show-login-modal');
        const showRegisterModalButton = document.getElementById('show-register-modal');
        const modalBody = document.getElementById('modal-body');

        function openModal() {
            authModal.style.display = 'block';
        }

        function closeModal() {
            authModal.style.display = 'none';
            modalBody.innerHTML = ''; // Clear modal content on close
        }

        closeButton.addEventListener('click', closeModal);
        window.addEventListener('click', (event) => {
            if (event.target == authModal) {
                closeModal();
            }
        });

        showLoginModalButton.addEventListener('click', (e) => {
            e.preventDefault();
            loadFormIntoModal('login');
            openModal();
        });

        showRegisterModalButton.addEventListener('click', (e) => {
            e.preventDefault();
            loadFormIntoModal('register');
            openModal();
        });

        function loadFormIntoModal(formType) {
            let url = '';
            if (formType === 'login') {
                url = 'html/login.html'; // Will recreate this file
            } else if (formType === 'register') {
                url = 'html/register.html'; // Will recreate this file
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    // Re-attach event listeners for links inside the loaded forms
                    const switchToRegisterLink = modalBody.querySelector('#switch-to-register-modal');
                    const switchToLoginLink = modalBody.querySelector('#switch-to-login-modal');

                    if (switchToRegisterLink) {
                        switchToRegisterLink.addEventListener('click', (e) => {
                            e.preventDefault();
                            loadFormIntoModal('register');
                        });
                    }
                    if (switchToLoginLink) {
                        switchToLoginLink.addEventListener('click', (e) => {
                            e.preventDefault();
                            loadFormIntoModal('login');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading form:', error);
                    modalBody.innerHTML = '<p>Error loading form. Please try again.</p>';
                });
        }
    </script>
</body>
</html>