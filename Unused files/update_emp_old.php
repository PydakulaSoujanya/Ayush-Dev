<?php
// Include database connection
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the form data
    $id = $_POST['id'];
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
    $termination_date = $_POST['termination_date'];
    $document = $_POST['document'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $address = $_POST['address'];

    // Prepare the SQL update query
    $sql = "UPDATE emp_info SET
                name = ?,
                dob = ?,
                gender = ?,
                phone = ?,
                email = ?,
                role = ?,
                qualification = ?,
                experience = ?,
                doj = ?,
                aadhar = ?,
                police_verification = ?,
                daily_rate = ?,
                status = ?,
                termination_date = ?,
                document = ?,
                bank_name = ?,
                bank_account_no = ?,
                ifsc_code = ?,
                address = ?
            WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the SQL query: " . $conn->error);
}

// Bind parameters
$stmt->bind_param(
    'ssssssssssssssssssi',
    $name,
    $dob,
    $gender,
    $phone,
    $email,
    $role,
    $qualification,
    $experience,
    $doj,
    $aadhar,
    $police_verification,
    $daily_rate,
    $status,
    $termination_date,
    $document,
    $bank_name,
    $bank_account_no,
    $ifsc_code,
    $address,
    $id
);

// Execute the query
if ($stmt->execute()) {
    echo "Employee details updated successfully.";
    header("Location: table.php"); // Redirect to the table page
    exit;
} else {
    echo "Error updating employee: " . $stmt->error;
}
$stmt->close();
$conn->close();

} else {
    // Redirect if accessed without POST method
    header("Location: table.php");
    exit;
}
?>
