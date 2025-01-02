<?php
include '../config.php';  // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $service_name = $_POST['service_name'];
    $status = $_POST['status'];
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'];
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'];
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    // Use prepared statement to call the stored procedure
    $stmt = $conn->prepare("CALL InsertIntoServiceMaster(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdds", $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description);

    if ($stmt->execute()) {
        // Success: Show popup and redirect
        echo "<script>
                alert('New record created successfully');
                window.location.href = 'view_servicemaster.php';
              </script>";
    } else {
        // Error: Show error message
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    // Close the statement
    $stmt->close();
}
?>
