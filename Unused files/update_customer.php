<?php
// Include database connection
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $customerId = $_POST['customer_id'];
    $patientStatus = $_POST['patient_status'];
    $patientName = $_POST['patient_name'];
    $relationship = $_POST['relationship'];
    $address = $_POST['address'];

    // Update the record in the database
    $sql = "UPDATE customers SET patient_status = ?, patient_name = ?, relationship = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $patientStatus, $patientName, $relationship, $address, $customerId);
    $stmt->execute();
}
?>