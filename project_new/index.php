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
            <button id="show-login" class="button">Log In</button>
            <button id="show-register" class="button primary">Sign Up</button>
        </div>
    </div>
    
    <div class="container">
        <div id="auth-forms">
            <div id="login-form-container" class="auth-form">
                <h2>Login</h2>
                <p>Please enter your credentials to access your dashboard.</p>

                <form action="php/login.php" method="post" autocomplete="off">
                    <div>
                        <label>Username</label>
                        <input type="text" name="username" placeholder="username" autocomplete="off" required>
                    </div>    
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" placeholder="password" autocomplete="new-password" required>
                    </div>
                    <div>
                        <input type="submit" value="Login" class="button primary">
                    </div>
                    <p>Don't have an account? <a href="#" id="switch-to-register">Sign up now</a>.</p>
                </form>
            </div>

            <div id="register-form-container" class="auth-form" style="display: none;">
                <h2>Sign Up</h2>
                <p>Create an account to begin your tracking journey.</p>
                
                <form action="php/register.php" method="post" autocomplete="off">
                    <div>
                        <label>Username</label>
                        <input type="text" id="username" name="username" required autocomplete="off" placeholder="username">
                    </div>    
                    
                    <div>
                        <label>Email</label>
                        <input type="email" id="email" name="email" required autocomplete="off" placeholder="example@gmail.com">
                    </div>
                    
                    <div>
                        <label>Password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Password">
                    </div>
                    
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" placeholder="Confirm Password">
                    </div>
                    
                    <div>
                        <input type="submit" value="Register" class="button primary">
                    </div>
                    
                    <p>Already have an account? <a href="#" id="switch-to-login">Log in here</a>.</p>
                </form>
            </div>
        </div>

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

    <script>
        const showLoginButton = document.getElementById('show-login');
        const showRegisterButton = document.getElementById('show-register');
        const loginFormContainer = document.getElementById('login-form-container');
        const registerFormContainer = document.getElementById('register-form-container');
        const switchToRegisterLink = document.getElementById('switch-to-register');
        const switchToLoginLink = document.getElementById('switch-to-login');

        function showForm(formToShow) {
            if (formToShow === 'login') {
                loginFormContainer.style.display = 'block';
                registerFormContainer.style.display = 'none';
            } else {
                loginFormContainer.style.display = 'none';
                registerFormContainer.style.display = 'block';
            }
        }

        showLoginButton.addEventListener('click', () => showForm('login'));
        showRegisterButton.addEventListener('click', () => showForm('register'));
        switchToRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            showForm('register');
        });
        switchToLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            showForm('login');
        });

        // Initially show the login form or based on some condition
        showForm('login'); 
    </script>
</body>
</html>