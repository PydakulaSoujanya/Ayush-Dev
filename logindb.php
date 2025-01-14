<?php
session_start(); // Start session to manage user state
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

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // If passwords are stored as plain text (not recommended)
        if ($password == $user['password']) {
            // Generate OTP
            $otp = random_int(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $otp_expires = date('Y-m-d H:i:s', time() + 600); // OTP expiration time (10 minutes)

            // Update the OTP and expiry time in the database
            $update_sql = "UPDATE `login` SET otp = '$otp', otp_expires = '$otp_expires' WHERE email = '$email'";
            if ($conn->query($update_sql) === TRUE) {
                // Send OTP to email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Update SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'uppalahemanth4@gmail.com'; // Your email
                    $mail->Password = 'oimoftsgtwradkux'; // Your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home Health Care');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body = "Your OTP is <b>$otp</b>. It is valid for 10 minutes.";

                    $mail->send();
                    header("Location: verify_otp.php");
                    exit;
                } catch (Exception $e) {
                    header("Location: index.php?error=email");
                    exit;
                }
            } else {
                header("Location: index.php?error=otp_storage");
                exit;
            }
        } else {
            header("Location: index.php?error=invalid_password");
            exit;
        }
    } else {
        header("Location: index.php?error=email_not_found");
        exit;
    }
}

$conn->close();
?>
