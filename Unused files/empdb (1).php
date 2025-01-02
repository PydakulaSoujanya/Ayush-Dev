<?php
// Start the session and connect to the database
session_start();
include('config.php'); // Your database connection file

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
    // $daily_rate = $_POST['daily_rate'];
    $status = $_POST['status'];
    $daily_rate8 = $_POST['daily_rate8'];
    $daily_rate12 = $_POST['daily_rate12'];
    $daily_rate24 = $_POST['daily_rate24'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $reference = $_POST['reference'];
    $vendor_name = $_POST['vendor_name'];
     $vendor_id = $_POST['vendor_id'];
    $vendor_contact = $_POST['vendor_contact'];
    $address = $_POST['address'];
    
    // File upload handling
    $document = '';
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $target_dir = "uploads/";
        $document = $target_dir . basename($_FILES["document"]["name"]);
        move_uploaded_file($_FILES["document"]["tmp_name"], $document);
    }

    // Insert data into database
    $sql = "INSERT INTO emp_info (name, dob, gender, phone, email, role, qualification, experience, doj, aadhar, police_verification, status, daily_rate8, daily_rate12, daily_rate24, document, bank_name, bank_account_no, ifsc_code, reference, vendor_id,vendor_name, vendor_contact, address)
            VALUES ('$name', '$dob', '$gender', '$phone', '$email', '$role', '$qualification', '$experience', '$doj', '$aadhar', '$police_verification', '$status', '$daily_rate8', '$daily_rate12', '$daily_rate12', '$document', '$bank_name', '$bank_account_no', '$ifsc_code', '$reference','$vendor_id', '$vendor_name', '$vendor_contact', '$address')";

    if ($conn->query($sql) === TRUE) {
        // Show alert and redirect using JavaScript
        echo "<script>
                alert('Successfully added record');
                window.location.href = 'table.php';
              </script>";
    } else {
        // Display error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

mysqli_close($conn);
?>
