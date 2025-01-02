<?php
session_start(); // Start the session

// Destroy the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page or homepage
header("Location: index.php"); // Replace 'login.php' with your login page
exit();
?>
