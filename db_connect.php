<?php
$servername = "localhost";  // Default XAMPP server
$username = "root";  // Default XAMPP username
$password = "";  // Default (empty) password
$dbname = "library_db";  // Ensure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
