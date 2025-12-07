
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please enter your credentials to access your dashboard.</p>

        <form action="login.php" method="post" autocomplete="off">
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
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>