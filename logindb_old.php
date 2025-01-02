<?php
session_start(); // Start session to keep the user logged in if needed

include "config.php"; // Include your config file to establish DB connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to find user by email
    $sql = "SELECT * FROM `login` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        // If the password is NOT hashed, use simple string comparison
        if ($password == $user['password']) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            echo "<script>alert('Login successful!'); window.location.href = 'Employee-Master/manage_employee.php';</script>";
        } else {
            // Incorrect password
            echo "<script>alert('Invalid password!'); window.location.href = 'index.php';</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Email not found!'); window.location.href = 'index.php';</script>";
    }
}

$conn->close();
?>
