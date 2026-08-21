<?php
$host = "localhost";      // Server name
$user = "root";           // Default user for XAMPP
$password = "";           // Default password is empty
$database = "portfolio_db";  // Your database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
