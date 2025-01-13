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

        if ($password == $user['password']) {
            $otp = random_int(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $otp_expires = date('Y-m-d H:i:s', time() + 600);

            $update_sql = "UPDATE `login` SET otp = '$otp', otp_expires = '$otp_expires' WHERE email = '$email'";
            if ($conn->query($update_sql) === TRUE) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'your-email@gmail.com';
                    $mail->Password = 'your-app-password';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home Health Care');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body = "Your OTP is <b>$otp</b>. It is valid for 10 minutes.";

                    $mail->send();

                    $_SESSION['alert'] = [
                        'title' => 'Success!',
                        'message' => 'OTP sent to your email!',
                        'icon' => 'success',
                        'redirect' => 'verify_otp.php'
                    ];
                } catch (Exception $e) {
                    $_SESSION['alert'] = [
                        'title' => 'Error!',
                        'message' => 'Error sending OTP: ' . $mail->ErrorInfo,
                        'icon' => 'error',
                        'redirect' => 'index.php'
                    ];
                }
            } else {
                $_SESSION['alert'] = [
                    'title' => 'Error!',
                    'message' => 'Error storing OTP.',
                    'icon' => 'error',
                    'redirect' => 'index.php'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'title' => 'Invalid Password!',
                'message' => 'Please check your credentials and try again.',
                'icon' => 'error',
                'redirect' => 'index.php'
            ];
        }
    } else {
        $_SESSION['alert'] = [
            'title' => 'Email Not Found!',
            'message' => 'No account is associated with this email.',
            'icon' => 'error',
            'redirect' => 'index.php'
        ];
    }

    // Redirect to trigger the alert
    header("Location: index.php");
    exit;
}

$conn->close();
?>
