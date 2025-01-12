<?php
$servername = "localhost:3307";
$username = "root";  // Database username
$password = "";      // Database password
$dbname = "ayush_db"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
