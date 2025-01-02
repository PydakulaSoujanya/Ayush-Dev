<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../config.php';  // Ensure the correct path to your database connection file

// Handle the form submission for update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data from the POST request
    $vendor_id = $_POST['vendor_id'];
    $vendor_name = $_POST['vendor_name'];
    $gstin = $_POST['gstin'];
    $contact_person = $_POST['contact_person'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    // More fields as needed

    $updated_by = $_SESSION['user_id']; // Assuming the logged-in user ID is in session

    // Prepare the SQL query to call the stored procedure for updating the vendor
    $query = "CALL UpdateVendor(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $vendor_id, $vendor_name, $gstin, $contact_person, $phone_number, $email); // Add more fields as necessary

    if ($stmt->execute()) {
        echo "Vendor details updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
