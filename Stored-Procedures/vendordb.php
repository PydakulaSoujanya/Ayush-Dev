<?php
session_start(); // Start the session

// Include database connection file
include('../config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $vendor_name = $_POST['vendor_name'];
    $gstin = $_POST['gstin'];
    $contact_person = $_POST['contact_person'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $services_provided = $_POST['services_provided'];
    $vendor_type = $_POST['vendor_type'];
    $vendor_groups = $_POST['vendor_groups'];
    $pincode = $_POST['pincode'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $landmark = $_POST['landmark'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc = $_POST['ifsc'];
    $branch = $_POST['branch'];

    // Additional parameter for created_by
    $created_by = "Admin"; // Replace with session user if needed

    // Prepare the stored procedure call
$stmt = $conn->prepare("CALL InsertVendor(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    // Bind parameters
$stmt->bind_param(
    "ssssssssssssssssss",
    $vendor_name,
    $gstin,
    $contact_person,
    $phone_number,
    $email,
    $services_provided,
    $vendor_type,
    $pincode,
    $address_line1,
    $address_line2,
    $landmark,
    $city,
    $state,
    $bank_name,
    $account_number,
    $ifsc,
    $branch,
    $created_by
);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Vendor added successfully'); window.location.href='vendors.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='vendor_form.php';</script>";
    }


    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>
