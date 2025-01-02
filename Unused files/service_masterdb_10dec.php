<?php
include 'config.php';  // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $service_name = $_POST['service_name'];
    $status = $_POST['status'];
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'];
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'];
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    // SQL query to insert data
    $sql = "INSERT INTO service_master (service_name, status, daily_rate_8_hours, daily_rate_12_hours, daily_rate_24_hours, description)
            VALUES ('$service_name', '$status', '$daily_rate_8_hours', '$daily_rate_12_hours', '$daily_rate_24_hours', '$description')";

if ($conn->query($sql) === TRUE) {
    // Success: Show popup and redirect
    echo "<script>
            alert('New record created successfully');
            window.location.href = 'view_servicemaster.php';
          </script>";
} else {
    // Error: Show error message
    echo "<script>
            alert('Error: " . $conn->error . "');
            window.history.back();
          </script>";
}
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

?>
