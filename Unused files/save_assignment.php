<?php
include 'config.php';

// Check if data is received via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trainee_name = $_POST['trainee_name'];
    $customer_name = $_POST['customer_name'];
    $customer_mobile = $_POST['customer_mobile'];
    $customer_address = $_POST['customer_address'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Insert data into the assignments table
    $stmt = $conn->prepare("INSERT INTO assignments (trainee_name, customer_name, customer_mobile, customer_address, from_date, to_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $trainee_name, $customer_name, $customer_mobile, $customer_address, $from_date, $to_date);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Assignment saved successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error saving assignment.']);
    }

    $stmt->close();
}

$conn->close();
?>
