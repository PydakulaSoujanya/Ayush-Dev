<?php
include '../config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceName = $_POST['service_name'];
    $status = $_POST['status'];
    $dailyRate8 = $_POST['daily_rate_8_hours'];
    $dailyRate12 = $_POST['daily_rate_12_hours'];
    $dailyRate24 = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    // Insert into the database
    $sql = "INSERT INTO `service_master` (`service_name`, `status`, `daily_rate_8_hours`, `daily_rate_12_hours`, `daily_rate_24_hours`, `description`) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssiiis", $serviceName, $status, $dailyRate8, $dailyRate12, $dailyRate24, $description);
        $stmt->execute();
        header("Location: service_form.php?status=success"); // Redirect after successful submission
        exit();
    } else {
        die("Error: " . $conn->error);
    }
} else {
    die("Invalid request.");
}
?>
