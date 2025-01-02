<?php
// Start the session and connect to the database
session_start();
include('config.php');  // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $doj = $_POST['doj'];
    $aadhar = $_POST['aadhar'];
    $police_verification = $_POST['police_verification'];
    $daily_rate = $_POST['daily_rate'];
    $status = $_POST['status'];
    // $termination_date = $_POST['termination_date'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $address = $_POST['address'];
    
    // File upload handling
    $document = '';
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $target_dir = "uploads/";
        $document = $target_dir . basename($_FILES["document"]["name"]);
        move_uploaded_file($_FILES["document"]["tmp_name"], $document);
    }

    // Insert data into database
    $sql = "INSERT INTO emp_info (name, dob, gender, phone, email, role, qualification, experience, doj, aadhar, police_verification, daily_rate, status, document, bank_name, bank_account_no, ifsc_code, address)
            VALUES ('$name', '$dob', '$gender', '$phone', '$email', '$role', '$qualification', '$experience', '$doj', '$aadhar', '$police_verification', '$daily_rate', '$status', '$document', '$bank_name', '$bank_account_no', '$ifsc_code', '$address')";

if (mysqli_query($conn, $sql)) {
    $_SESSION['alert_message'] = "Employee record added successfully!";
    $_SESSION['alert_type'] = "success"; // Type of alert (success, error, etc.)
} else {
    $_SESSION['alert_message'] = "Error: " . mysqli_error($conn);
    $_SESSION['alert_type'] = "danger"; // Type of alert (success, error, etc.)
}

// Redirect back to the form
header("Location: emp-form.php");
exit;
}

mysqli_close($conn);
?>
