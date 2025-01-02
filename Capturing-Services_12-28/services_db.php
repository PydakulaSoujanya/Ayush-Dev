<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include("../config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch customer-related data (same for all services)
    $customer_name = isset($_POST['customer_name']) ? $conn->real_escape_string($_POST['customer_name']) : null;
    $contact_no = isset($_POST['emergency_contact_number']) ? $conn->real_escape_string($_POST['emergency_contact_number']) : null;
    $patient_name = isset($_POST['patient_name']) ? $conn->real_escape_string($_POST['patient_name']) : null;
    $relationship = isset($_POST['relationship']) ? $conn->real_escape_string($_POST['relationship']) : null;
    $enquiry_date = isset($_POST['enquiry_date']) ? $conn->real_escape_string($_POST['enquiry_date']) : null;
    $enquiry_time = isset($_POST['enquiry_time']) ? $conn->real_escape_string($_POST['enquiry_time']) : null;
    $enquiry_source = isset($_POST['enquiry_source']) ? $conn->real_escape_string($_POST['enquiry_source']) : null;
    $priority_level = isset($_POST['priority_level']) ? $conn->real_escape_string($_POST['priority_level']) : null;
    $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : null;
    $request_details = isset($_POST['request_details']) ? $conn->real_escape_string($_POST['request_details']) : null;
    $resolution_notes = isset($_POST['resolution_notes']) ? $conn->real_escape_string($_POST['resolution_notes']) : null;
    $comments = isset($_POST['comments']) ? $conn->real_escape_string($_POST['comments']) : null;

    // Validate required fields
    if (!$customer_name || !$contact_no || !$enquiry_date) {
        die("Please fill in all required fields.");
    }

    // Fetch service-related data (multiple services)
    $service_types = $_POST['service_type'] ?? [];
    $per_day_service_prices = $_POST['per_day_service_price'] ?? [];
    $from_dates = $_POST['from_date'] ?? [];
    $end_dates = $_POST['end_date'] ?? [];
    $total_days_list = $_POST['total_days'] ?? [];
    $service_prices = $_POST['service_price'] ?? [];

    // Loop through the service data arrays
    $errors = [];
    foreach ($service_types as $index => $service_type) {
        // Ensure all required fields are set for each service
        if (!isset($service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], $total_days_list[$index], $service_prices[$index])) {
            $errors[] = "Incomplete data for service at index $index.";
            continue;
        }

        // Prepare SQL query to insert data
        $sql = "INSERT INTO service_requests (
            customer_name, contact_no,patient_name, relationship, enquiry_date, enquiry_time, 
            service_type, per_day_service_price, from_date, end_date, total_days, service_price, enquiry_source, priority_level, status, 
            request_details, resolution_notes, comments
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param(
            "ssssssssssssssssss",
            $customer_name, $contact_no, $patient_name, $relationship, $enquiry_date, $enquiry_time,
            $service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], $total_days_list[$index], $service_prices[$index], $enquiry_source, $priority_level, $status,
            $request_details, $resolution_notes, $comments
        );

        // Execute the query
        if (!$stmt->execute()) {
            $errors[] = "Error inserting service at index $index: " . $stmt->error;
        }

        // Close the statement for this iteration
        $stmt->close();
    }

    // Check if there were any errors
    if (empty($errors)) {
        echo "<script>
                alert('All services added successfully.');
                window.location.href = 'view_services.php';
              </script>";
    } else {
        echo "<script>
                alert('Some errors occurred:\\n" . implode("\\n", $errors) . "');
              </script>";
    }
}

// Close the database connection
$conn->close();
?>
