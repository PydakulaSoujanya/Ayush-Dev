<?php
// Include database connection
include('config.php'); // Make sure this file contains your database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $vendor_name = $_POST['vendor_name'];
    $gstin = $_POST['gstin'];
    $contact_person = $_POST['contact_person'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $services_provided = $_POST['services_provided'];
    $vendor_type = $_POST['vendor_type'];
    $address = $_POST['address'];
    $additional_notes = $_POST['additional_notes'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc = $_POST['ifsc'];
    $status = $_POST['status'];

    // Handle file upload
    $supporting_documents = '';
    if (isset($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] == 0) {
        $target_dir = "uploads/"; // Directory where files will be stored
        $supporting_documents = $target_dir . basename($_FILES['supporting_documents']['name']);

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['supporting_documents']['tmp_name'], $supporting_documents)) {
            die("Failed to upload supporting documents.");
        }
    }

    // Prepare the SQL query
    $query = "INSERT INTO vendors (vendor_name, gstin, contact_person, supporting_documents, phone_number, email, services_provided, vendor_type, address, additional_notes, bank_name, account_number, ifsc, status, created_at, updated_at)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    // Initialize prepared statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssss",
        $vendor_name,
        $gstin,
        $contact_person,
        $supporting_documents,
        $phone_number,
        $email,
        $services_provided,
        $vendor_type,
        $address,
        $additional_notes,
        $bank_name,
        $account_number,
        $ifsc,
        $status
    );

    // Execute the query
    if ($stmt->execute()) {
        echo "Vendor added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
