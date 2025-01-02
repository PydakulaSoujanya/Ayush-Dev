<?php
include '../config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceName = $_POST['service_name'];

    // Insert new service into the database
    $sql = "INSERT INTO `service_master` (`service_name`, `status`) VALUES (?, 'active')";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $serviceName);
        $stmt->execute();
        echo "success";
    } else {
        http_response_code(500);
        echo "Error: " . $conn->error;
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
