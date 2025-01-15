<?php
// Include database connection
include('../config.php'); // Make sure this file contains your database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $vendor_name = $conn->real_escape_string($_POST['vendor_name']);
    $gstin = $conn->real_escape_string($_POST['gstin']);
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $supporting_documents = isset($_FILES['supporting_documents']) ? $_FILES['supporting_documents']['name'] : null;
    $vendor_type = $conn->real_escape_string($_POST['vendor_type']);
    $services_provided = isset($_POST['service_type']) ? implode(", ", $_POST['service_type']) : null;
    $address_line1 = $conn->real_escape_string($_POST['address_line1']);
    $address_line2 = $conn->real_escape_string($_POST['address_line2']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $landmark = $conn->real_escape_string($_POST['landmark']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $bank_name = $conn->real_escape_string($_POST['bank_name']);
    $account_number = $conn->real_escape_string($_POST['account_number']);
    $ifsc = $conn->real_escape_string($_POST['ifsc']);
    $branch = $conn->real_escape_string($_POST['branch']);
    

    // Handle file upload
    $supporting_documents = '';
    if (isset($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['supporting_documents']['name']);
        $supporting_documents = $target_dir . $file_name;
        if (!move_uploaded_file($_FILES['supporting_documents']['tmp_name'], $supporting_documents)) {
            die("Failed to upload supporting documents.");
        }
    }

    // Call the stored procedure
    $query = "CALL InsertVendor(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssssssss",
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
        echo "<script>alert('Vendor added successfully'); window.location.href='emp-form.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='emp-form.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
