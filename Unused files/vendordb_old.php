<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle text input fields
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
    if (isset($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] == 0) {
        $file_tmp = $_FILES['supporting_documents']['tmp_name'];
        $file_name = $_FILES['supporting_documents']['name'];
        $file_size = $_FILES['supporting_documents']['size'];
        $file_type = $_FILES['supporting_documents']['type'];

        // Check file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file_type, $allowed_types)) {
            die("File type not allowed.");
        }

        // Move file to the destination folder
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_path = $upload_dir . basename($file_name);
        if (!move_uploaded_file($file_tmp, $file_path)) {
            die("Error uploading file.");
        }
    } else {
        $file_path = null; // No file uploaded
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ayush_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to insert data
    $stmt = $conn->prepare("INSERT INTO vendors (vendor_name, gstin, contact_person, phone_number, email, services_provided, vendor_type, address, additional_notes, bank_name, account_number, ifsc, status, supporting_documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $vendor_name, $gstin, $contact_person, $phone_number, $email, $services_provided, $vendor_type, $address, $additional_notes, $bank_name, $account_number, $ifsc, $status, $file_path);

    if ($stmt->execute()) {
        echo "Vendor added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
