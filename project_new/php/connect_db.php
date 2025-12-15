<?php
// php/connect_db.php

// Define database connection constants
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
// !! CRITICAL: Update this password !!
define('DB_PASSWORD', ''); 
define('DB_NAME', 'wellness_tracker_db'); 

/* Attempt to connect to MySQL database */
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn->connect_error){
    // Stop execution and display a clear error message if the connection fails
    die("ERROR: Could not connect to the database. Check credentials in php/connect_db.php. Error: " . $conn->connect_error);
}
// Connection successful. $conn is now ready for use.
?>