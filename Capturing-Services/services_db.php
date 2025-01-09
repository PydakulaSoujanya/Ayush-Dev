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
    $customer_name = $_POST['customer_name'] ?? null;
    $contact_no = $_POST['emergency_contact_number'] ?? null;
    $patient_name = $_POST['patient_name'] ?? null;
    $relationship = $_POST['relationship'] ?? null;
    $enquiry_date = $_POST['enquiry_date'] ?? null;
    $enquiry_time = $_POST['enquiry_time'] ?? null;
    $enquiry_source = $_POST['enquiry_source'] ?? null;
    $priority_level = $_POST['priority_level'] ?? null;
    $status = $_POST['status'] ?? null;
    $request_details = $_POST['request_details'] ?? null;
    $resolution_notes = $_POST['resolution_notes'] ?? null;
    $comments = $_POST['comments'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;

    // Validate required fields
    if (!$customer_name || !$contact_no || !$enquiry_date) {
        die("Please fill in all required fields.");
    }

    // Default empty values to NULL
    $priority_level = !empty($priority_level) ? $priority_level : null;

    // Fetch service-related data (multiple services)
    $service_types = $_POST['service_type'] ?? [];
    $per_day_service_prices = $_POST['per_day_service_price'] ?? [];
    $from_dates = $_POST['from_date'] ?? [];
    $end_dates = $_POST['end_date'] ?? [];
    $total_days_list = $_POST['total_days'] ?? [];
    $service_prices = $_POST['service_price'] ?? [];
    $discount_prices = $_POST['discount_price'] ?? [];
    $total_service_prices = $_POST['total_service_price'] ?? [];

    // Loop through the service data arrays
    $errors = [];
    foreach ($service_types as $index => $service_type) {
        if (!isset($service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], $total_days_list[$index], $service_prices[$index])) {
            $errors[] = "Incomplete data for service at index $index.";
            continue;
        }

       

        // Apply discount if provided or default to 0
        $discount_price = isset($discount_prices[$index]) && !empty($discount_prices[$index]) 
            ? $discount_prices[$index] 
            : 0;

        // Calculate total price after applying the discount
        $total_price = $service_prices[$index] - $discount_price;

        // Insert into `service_requests` table
        $sql = "INSERT INTO service_requests (
            customer_name, contact_no, patient_name, customer_id, relationship, enquiry_date, enquiry_time, 
            service_type, per_day_service_price, from_date, end_date, total_days, service_price, 
            discount_price, total_service_price, total_price, enquiry_source, priority_level, status, 
            request_details, resolution_notes, comments
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param(
            "ssssssssssssssssssssss",
            $customer_name, $contact_no, $patient_name, $customer_id, $relationship, $enquiry_date, $enquiry_time,
            $service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], 
            $total_days_list[$index], $service_prices[$index], 
            $discount_price, $total_service_prices[$index], $total_price, $enquiry_source, 
            $priority_level, $status, $request_details, $resolution_notes, $comments
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
