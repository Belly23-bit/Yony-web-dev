<?php
$servername = "localhost";
$username = "root";  // Change to your DB user
$password = "";  // Change to your DB password
$dbname = "pdbureau";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash passwords
function hashPassword($pass) {
    return password_hash($pass, PASSWORD_BCRYPT);
}
?>
