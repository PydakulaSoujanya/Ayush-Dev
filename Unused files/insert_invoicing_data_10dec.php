<?php
// Database connection
include 'config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$customer_name = $_POST['customer_name'];
$service_type = $_POST['service_type'];
$from_date = $_POST['from_date'];
$end_date = $_POST['end_date'];
$duration = $_POST['duration'];
$base_charges = $_POST['base_charges'];
$total_amount = $_POST['total_amount'];
$status = $_POST['status'];

// Insert query
$sql = "INSERT INTO invoices (customer_name, service_type, from_date, end_date, duration, base_charges, total_amount, status) 
        VALUES ('$customer_name', '$service_type', '$from_date', '$end_date', '$duration', '$base_charges', '$total_amount', '$status')";

if ($conn->query($sql) === TRUE) {
    // Success: Show popup and redirect
    echo "<script>
            alert('New record created successfully');
            window.location.href = 'view_invoice.php';
          </script>";
} else {
    // Error: Show error message
    echo "<script>
            alert('Error: " . $conn->error . "');
            window.history.back();
          </script>";
}

// Close connection
$conn->close();
?>
