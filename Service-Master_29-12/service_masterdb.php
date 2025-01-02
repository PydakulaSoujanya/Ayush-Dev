<?php
include '../config.php'; // Include your database configuration file

// Include the function definition for `insertService`
function insertService(
    $conn, $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description
) {
    // SQL query to call the stored procedure
    $sql = "CALL InsertService(?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param(
        "ssddds", 
        $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description
    );

    // Execute the statement
    if ($stmt->execute()) {
        return true; // Success
    } else {
        return $stmt->error; // Error message
    }

    // Close the statement
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $service_name = $_POST['service_name'];
    $status = $_POST['status'];
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'];
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'];
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    // Call the insertService function
    $result = insertService(
        $conn, 
        $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description
    );

    // Handle the result
    if ($result === true) {
        // Success: Show popup and redirect
        echo "<script>
                alert('New record created successfully');
                window.location.href = 'view_servicemaster.php';
              </script>";
    } else {
        // Error: Show error message
        echo "<script>
                alert('Error: $result');
                window.history.back();
              </script>";
    }
}
?>
