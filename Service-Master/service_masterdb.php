<?php
include '../config.php';  // Include your database configuration file

// Check if the form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $service_name = $_POST['service_name'] ?? '';
    $status = $_POST['status'] ?? '';
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'] ?? '';
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'] ?? '';
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'] ?? '';
    $description = $_POST['description'] ?? '';

    // Form validation: Ensure no fields are empty
    if (empty($service_name) || empty($status) || empty($daily_rate_8_hours) || empty($daily_rate_12_hours) || empty($daily_rate_24_hours)) {
        echo "<script>alert('Please fill all the required fields.'); window.history.back();</script>";
        exit;
    }

    // Debugging: Check POST data
    // var_dump($_POST); // Uncomment to debug POST data
    // exit; // Uncomment to stop execution here if you want to inspect the POST data

    // SQL query to insert data into the database
    $sql = "INSERT INTO service_master (service_name, status, daily_rate_8_hours, daily_rate_12_hours, daily_rate_24_hours, description)
            VALUES ('$service_name', '$status', '$daily_rate_8_hours', '$daily_rate_12_hours', '$daily_rate_24_hours', '$description')";

    // Check if the query is successful
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New record created successfully'); window.location.href = 'view_servicemaster.php';</script>";
    } else {
        // If there's an error in the query
        echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    }

} else {
    echo "<script>alert('Invalid request method'); window.history.back();</script>";
}
?>
