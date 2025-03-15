<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Change this if your MySQL username is different
$password = ""; // Change this if you have a password
$dbname = "cms_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
