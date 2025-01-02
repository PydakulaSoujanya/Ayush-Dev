<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Include database connection file
include('../config.php');

// Check if delete request is sent
if (isset($_GET['delete'])) {
    $vendor_id = $_GET['delete']; // Get the vendor_id from the URL (e.g., ?delete=1)

    // Call the stored procedure to delete the vendor
    $stmt = $conn->prepare("CALL DeleteVendor(?)");
    $stmt->bind_param("i", $vendor_id);

    if ($stmt->execute()) {
        echo "Vendor deleted successfully.";
        // Redirect to the vendor list page after deletion
        header("Location: vendors.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
