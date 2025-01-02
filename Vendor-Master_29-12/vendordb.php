<?php
// Include database connection
include('../config.php'); // Make sure this file contains your database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $vendor_name = $_POST['vendor_name'];
    $gstin = $_POST['gstin'];
    $contact_person = $_POST['contact_person'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $services_provided = $_POST['services_provided'];
    $vendor_type = $_POST['vendor_type'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $landmark = $_POST['landmark'];
    $pincode = $_POST['pincode'];
   
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc = $_POST['ifsc'];
    $branch = $_POST['branch'];

    // Handle file upload
    $supporting_documents = '';
    if (isset($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] == 0) {
        $target_dir = "../uploads/"; // Directory where files will be stored
        $supporting_documents = $target_dir . basename($_FILES['supporting_documents']['name']);

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['supporting_documents']['tmp_name'], $supporting_documents)) {
            die("Failed to upload supporting documents.");
        }
    }

    // Prepare the SQL query
    $query = "INSERT INTO vendors (
                vendor_name, gstin, contact_person, supporting_documents, phone_number, email, services_provided, 
                vendor_type, address_line1, address_line2, city, state, landmark, pincode, 
                bank_name, account_number, ifsc, branch, created_at, updated_at)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    // Initialize prepared statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssssssss", // Data types for all fields
        $vendor_name,
        $gstin,
        $contact_person,
        $supporting_documents,
        $phone_number,
        $email,
        $services_provided,
        $vendor_type,
        $address_line1,
        $address_line2,
        $city,
        $state,
        $landmark,
        $pincode,
        $bank_name,
        $account_number,
        $ifsc,
        $branch
    );

    if ($stmt->execute()) {
        echo "<script>alert('Vendor added successfully'); window.location.href='vendors.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='vendor_form.php';</script>";
    }}

    // Close the statement and connection
    $stmt->close();
    $conn->close();

?>