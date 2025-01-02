<?php
include '../config.php'; // Database connection

if (isset($_POST['id'])) {
    // Retrieve form data
    $id = intval($_POST['id']);
    $service_name = $_POST['service_name'];
    $status = $_POST['status'];
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'];
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'];
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    // Call the stored procedure
    $sql = "CALL UpdateServiceMaster(?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("issddds", $id, $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: view_servicemaster.php?msg=Record updated successfully");
            exit();
        } else {
            // Handle execution error
            echo "Error updating service: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle preparation error
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

// Close the database connection
$conn->close();
?>
