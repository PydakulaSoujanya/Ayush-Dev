<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer autoload

include "config.php"; // DB connection

// Set the timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.location.href = 'forgot_password.php';</script>";
        exit();
    }

    // Check if email exists in the database using prepared statements
    $sql = $conn->prepare("SELECT * FROM `login` WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(32));

        // Get current time and format it to Indian format (DD/MM/YYYY HH:MM:SS)
        $current_time = new DateTime();  // Get current time
        $current_time_indian = $current_time->format('d/m/Y H:i:s');  // Indian format: DD/MM/YYYY HH:MM:SS

        // Expiration time (1 day later) in Indian format
        $expires_at = new DateTime(); // Set current time
        $expires_at->modify('+1 day');  // Add 1 day
        $expires_at_indian = $expires_at->format('d/m/Y H:i:s');  // Indian format: DD/MM/YYYY HH:MM:SS

        // Save the token and expiration (1 day) in the database (MySQL format)
        $current_time_mysql = $current_time->format('Y-m-d H:i:s');  // MySQL format: YYYY-MM-DD HH:MM:SS
        $expires_at_mysql = $expires_at->format('Y-m-d H:i:s');  // Expiration time in MySQL format

        // Save the token and expiration time to the database
        $updateTokenSql = $conn->prepare("UPDATE `login` SET reset_token = ?, token_expires = ? WHERE email = ?");
        $updateTokenSql->bind_param("sss", $token, $expires_at_mysql, $email);
        $updateTokenSql->execute();

        // Create the reset link
        $resetLink = "http://localhost/Ayush-Home%20helath%20care/reset_password.php?token=$token";  // Make sure the link is correct

        // PHPMailer Setup
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'uppalahemanth4@gmail.com'; // Your email
            $mail->Password = 'oimoftsgtwradkux'; // Your email password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home helath care');
            $mail->addAddress($email);

            // Email content
           $mail->isHTML(true);
$mail->Subject = 'Password Reset Request';

// HTML for the email body with a styled button
$mail->Body = "
    <p>Click the following link to reset your password:</p>
    <a href='$resetLink' style='
        background-color: #4CAF50; 
        color: white; 
        padding: 14px 20px; 
        text-align: center; 
        text-decoration: none; 
        display: inline-block; 
        border-radius: 5px; 
        font-size: 16px;
    '>
        Reset Password
    </a><br><br>
    <strong>Token Created At (Indian Format):</strong> $current_time_indian<br>
    <strong>Token Expiration (Indian Format):</strong> $expires_at_indian
";

// The alternative plain text body
$mail->AltBody = "Click the following link to reset your password: $resetLink\n\n
                 Token Created At (Indian Format): $current_time_indian\n
                 Token Expiration (Indian Format): $expires_at_indian";

$mail->send();

            echo "<script>alert('Password reset link sent to your email!'); window.location.href = 'index.php';</script>";
        } catch (Exception $e) {
            // Log the error message
            error_log("Error sending email: " . $mail->ErrorInfo);
            echo "<script>alert('Failed to send email. Please try again later.'); window.location.href = 'forgot_password.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.location.href = 'forgot_password.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <style>
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
