<?php
include "config.php"; // DB connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM `login` WHERE reset_token = '$token' AND token_expires > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid, so update the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updatePasswordSql = "UPDATE `login` SET password = '$hashed_password', reset_token = NULL, token_expires = NULL WHERE reset_token = '$token'";

        if ($conn->query($updatePasswordSql) === TRUE) {
            echo "<script>alert('Password reset successful!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error updating password!'); window.location.href = 'forgot_password.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired token!'); window.location.href = 'forgot_password.php';</script>";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "<script>alert('No token provided!'); window.location.href = 'forgot_password.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">Enter your new password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
