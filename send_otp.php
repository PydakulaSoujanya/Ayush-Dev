<?php
// session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader
include "config.php"; // Ensure this connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch user by email
    $sql = "SELECT * FROM `login` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if ($password == $user['password']) {
            // Generate OTP
            $otp = rand(100000, 999999);
echo "Generated OTP: $otp<br>";

            $otp_expires = date("Y-m-d H:i:s", time() + 300); // Expire in 5 minutes

            // Update OTP in the database
            $update_sql = "UPDATE `login` SET otp = '$otp', otp_expires = '$otp_expires' WHERE email = '$email'";
            if ($conn->query($update_sql)) {
                // Send OTP via email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'uppalahemanth4@gmail.com'; // Replace with your email
                    $mail->Password = 'oimoftsgtwradkux'; // Replace with your email password or app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipient and sender
                    $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home helath care');
                    $mail->addAddress($email); // Recipient's email address

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Login OTP';
                    $mail->Body = "<p>Your OTP is <strong>$otp</strong>. It is valid for 5 minutes.</p>";

                    $mail->send();
                    $_SESSION['email'] = $email; // Store email in session
                    echo "<script>alert('OTP sent to your email!'); window.location.href = 'verify_otp.php';</script>";
                    exit;
                } catch (Exception $e) {
                    echo "<script>alert('Failed to send OTP email: {$mail->ErrorInfo}'); window.location.href = 'index.php';</script>";
                }
            } else {
                echo "Error updating OTP: " . $conn->error;
            }
        } else {
            echo "<script>alert('Invalid password!'); window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.location.href = 'index.php';</script>";
    }
}

$conn->close();
?>
