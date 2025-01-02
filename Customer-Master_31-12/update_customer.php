<?php
// Include database connection
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $customerId = $_POST['customer_id'];
    $patientStatus = $_POST['patient_status'];
    $patientName = $_POST['patient_name'];
    $relationship = $_POST['relationship'];
    $address = $_POST['address'];

    // Call the stored procedure to update the record
    $sql = "CALL UpdateCustomer(?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssssi', $patientStatus, $patientName, $relationship, $address, $customerId);

        // Execute the stored procedure
        if ($stmt->execute()) {
            echo "Record updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }
}

// Close the connection
$conn->close();
?>
