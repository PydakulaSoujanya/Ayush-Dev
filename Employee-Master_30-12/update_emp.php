<?php
// Include database connection
include('../config.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $employee_id = $_POST['id'];
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
    $status = $_POST['status'];
    $daily_rate8 = $_POST['daily_rate8'];
    $daily_rate12 = $_POST['daily_rate12'];
    $daily_rate24 = $_POST['daily_rate24'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $address = $_POST['address'];
    $document = null;

    // Handle file upload (if provided)
    if (!empty($_FILES['document']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['document']['name']);
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_file)) {
            $document = $target_file;
        }
    }

    // Construct the query
    $query = "UPDATE emp_info 
              SET name = ?, dob = ?, gender = ?, phone = ?, email = ?, 
                  role = ?, qualification = ?, experience = ?, doj = ?, 
                  aadhar = ?, police_verification = ?, status = ?, 
                  daily_rate8 = ?, daily_rate12 = ?, daily_rate24 = ?, 
                  bank_name = ?, bank_account_no = ?, ifsc_code = ?, 
                  address = ?" . ($document ? ", document = ?" : "") . " 
              WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters dynamically
    if ($document) {
        $stmt->bind_param(
            'sssssssssssssssisssisi',
            $name, $dob, $gender, $phone, $email,
            $role, $qualification, $experience, $doj,
            $aadhar, $police_verification, $status,
            $daily_rate8, $daily_rate12, $daily_rate24,
            $bank_name, $bank_account_no, $ifsc_code,
            $address, $document, $employee_id
        );
    } else {
        $stmt->bind_param(
            'sssssssssssssssssssi',
            $name, $dob, $gender, $phone, $email,
            $role, $qualification, $experience, $doj,
            $aadhar, $police_verification, $status,
            $daily_rate8, $daily_rate12, $daily_rate24,
            $bank_name, $bank_account_no, $ifsc_code,
            $address, $employee_id
        );
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
                alert('Successfully updated record');
                window.location.href = 'manage_employee.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    die('Invalid request method.');
}
?>
