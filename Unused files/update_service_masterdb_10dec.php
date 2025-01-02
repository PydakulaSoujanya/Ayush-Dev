<?php
include 'config.php'; // Database connection

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $service_name = $_POST['service_name'];
    $status = $_POST['status'];
    $daily_rate_8_hours = $_POST['daily_rate_8_hours'];
    $daily_rate_12_hours = $_POST['daily_rate_12_hours'];
    $daily_rate_24_hours = $_POST['daily_rate_24_hours'];
    $description = $_POST['description'];

    $sql = "UPDATE `service_master` SET 
                `service_name` = ?, 
                `status` = ?, 
                `daily_rate_8_hours` = ?, 
                `daily_rate_12_hours` = ?, 
                `daily_rate_24_hours` = ?, 
                `description` = ? 
            WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddsd", $service_name, $status, $daily_rate_8_hours, $daily_rate_12_hours, $daily_rate_24_hours, $description, $id);

    if ($stmt->execute()) {
        // Redirect with success status
        header("Location: view_servicemaster.php?msg=Record updated successfully");
     
        exit();
    } else {
        echo "Error updating service: " . $stmt->error;
    }
} else {
    echo "Invalid request!";
}
?>
