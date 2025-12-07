<?php
// B2: Registration Logic
require_once 'connect_db.php'; // Use the correct filename

// Initialize variables
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Validation (omitted for brevity, assume detailed validation from previous draft)
    // 2. Hash and Insert
    if (empty($username_err) && empty($email_err) && empty($password_err)) {

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password_hash);

            $param_username = trim($_POST["username"]);
            $param_email = trim($_POST["email"]);
            // CRITICAL: HASHING THE PASSWORD!
            $param_password_hash = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); 

            if (mysqli_stmt_execute($stmt)) {
                // Success
                header("location: login.php?registered=true");
                exit;
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>
