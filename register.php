
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Create an account to begin your tracking journey.</p>
        
        <form action="register.php" method="post" autocomplete="off">
            <div>
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off" placeholder="username">
            </div>    
            
            <div>
                <label>Email</label>
                <input type="email" name="email" required autocomplete="off" placeholder="example@gmail.com">
            </div>
            
            <div>
                <label>Password</label>
                <input type="password" name="password" required autocomplete="new-password" placeholder="Password">
            </div>
            
            <div>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required autocomplete="new-password" placeholder="Confirm Password">
            </div>
            
            <div>
                <input type="submit" value="Register" class="button primary">
            </div>
            
            <p>Already have an account? <a href="login.php">Log in here</a>.</p>
        </form>
    </div>    
</body>
</html>