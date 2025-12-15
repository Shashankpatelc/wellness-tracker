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
            <a href="html/login.html" class="button">Log In</a>
            <a href="html/register.html" class="button primary">Sign Up</a>
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
</body>
</html>