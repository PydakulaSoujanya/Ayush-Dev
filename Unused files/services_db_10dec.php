<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database configuration file
include("config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch form inputs, with default values for missing fields
    $customer_name = isset($_POST['customer_name']) ? $conn->real_escape_string($_POST['customer_name']) : '';
    $contact_no = isset($_POST['contact_no']) ? $conn->real_escape_string($_POST['contact_no']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $enquiry_date = isset($_POST['enquiry_date']) ? $conn->real_escape_string($_POST['enquiry_date']) : '';
    $enquiry_time = isset($_POST['enquiry_time']) ? $conn->real_escape_string($_POST['enquiry_time']) : '';
    $service_type = isset($_POST['service_type']) ? $conn->real_escape_string($_POST['service_type']) : '';
    $enquiry_source = isset($_POST['enquiry_source']) ? $conn->real_escape_string($_POST['enquiry_source']) : '';
    $priority_level = isset($_POST['priority_level']) ? $conn->real_escape_string($_POST['priority_level']) : '';
    $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';
    $request_details = isset($_POST['request_details']) ? $conn->real_escape_string($_POST['request_details']) : '';
    $resolution_notes = isset($_POST['resolution_notes']) ? $conn->real_escape_string($_POST['resolution_notes']) : '';
    $comments = isset($_POST['comments']) ? $conn->real_escape_string($_POST['comments']) : '';

    // Debug the inputs
    // echo "<pre>";
    // print_r([
    //     $customer_name, $contact_no, $email, $enquiry_date, $enquiry_time,
    //     $service_type, $enquiry_source, $priority_level, $status,
    //     $request_details, $resolution_notes, $comments
    // ]);
    // echo "</pre>";

    // SQL query to insert data into the database
    $sql = "INSERT INTO service_requests (
        customer_name, contact_no, email, enquiry_date, enquiry_time, 
        service_type, enquiry_source, priority_level, status, 
        request_details, resolution_notes, comments
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL preparation failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssss",
        $customer_name, $contact_no, $email, $enquiry_date, $enquiry_time,
        $service_type, $enquiry_source, $priority_level, $status,
        $request_details, $resolution_notes, $comments
    );

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                alert('Service added successfully.');
                window.location.href = 'view_services.php'; // Redirect to a listing page if needed
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
              </script>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>