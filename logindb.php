<?php
session_start();
include "config.php"; // Include DB connection file

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to find user by email
    $sql = "SELECT * FROM `login` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // If passwords are stored as plain text (not recommended)
        if ($password == $user['password']) {
            // Generate OTP
            $otp = random_int(100000, 999999);
            echo "Generated OTP: $otp"; // Debug
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Send OTP to email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'uppalahemanth4@gmail.com';
                $mail->Password = 'oimoftsgtwradkux'; // App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home Healthcare');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP is <b>$otp</b>. It is valid for 10 minutes.";

                if ($mail->send()) {
                    echo "<script>
                        alert('OTP sent to your email!');
                        window.location.href = 'verify_otp.php';
                    </script>";
                } else {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                echo "<script>
                    alert('Error sending OTP: {$mail->ErrorInfo}');
                    window.location.href = 'index.php';
                </script>";
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
