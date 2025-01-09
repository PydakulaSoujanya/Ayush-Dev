<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = $_SESSION['email'];  // Retrieve email from session

    // Query to fetch the OTP and expiration time for the user
    $sql = "SELECT otp, otp_expires FROM `login` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_otp = $user['otp'];
        $otp_expires = strtotime($user['otp_expires']);
        $current_time = time();

        if ($otp == $stored_otp && $current_time <= $otp_expires) {
            // OTP verified successfully
            echo "<script>alert('OTP verified successfully! Redirecting to the dashboard.'); window.location.href = 'dashboard/dashboard.php';</script>";
            exit(); // Stop further script execution
        } else {
            // Invalid OTP or expired
            echo "<script>alert('Invalid OTP or OTP expired. Please try again.');</script>";
        }
    } else {
        // No user found with the given email
        echo "<script>alert('Invalid request. Please login again.'); window.location.href = 'index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Verify OTP</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .otp-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .otp-container h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .styled-input {
            font-size: 16px;
        }
        .btn-submit {
            width: 100%;
        }
        .note {
            font-size: 14px;
            color: #6c757d;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h3>OTP Verification</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" class="form-control styled-input" id="otp" name="otp" placeholder="Enter your 6-digit OTP" required>
            </div>
            <button type="submit" class="btn btn-primary btn-submit">Verify OTP</button>
            <p class="note">The OTP is valid for 5 minutes.</p>
        </form>
    </div>
</body>
</html>
