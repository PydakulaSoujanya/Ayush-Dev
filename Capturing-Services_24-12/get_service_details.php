<?php
// Include database connection
include '../config.php';

// Check if service type is provided
if (isset($_POST['service_type'])) {
    $serviceType = $_POST['service_type'];

    // Fetch service details from the database
    $query = "SELECT daily_rate_8_hours, daily_rate_12_hours, daily_rate_24_hours FROM service_master WHERE service_name = '$serviceType' AND status = 'active' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $serviceDetails = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'serviceDetails' => $serviceDetails]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Service not found or inactive']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Service type is required']);
}

mysqli_close($conn);
?>
