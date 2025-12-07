<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', 'root');
define('DB_NAME', 'wellness_tracker_db'); 

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    // Stops the script and displays an error if connection fails
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Set character set to UTF8
mysqli_set_charset($conn, "utf8");
?>