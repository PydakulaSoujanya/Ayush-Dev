<?php
// Include database connection file
include '../config.php'; // Ensure you have this file for DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $patient_name = $_POST['patient_name'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    $status = $_POST['patient_status'];
    $medical_conditions = $_POST['medical_conditions'];
    $relationship = $_POST['relationship'];
    $blood_group = $_POST['blood_group'];
    $mobility_status = $_POST['mobility_status'];
    $gender = $_POST['gender'];
    $patient_age = $_POST['patient_age'];

    // Address fields
    $pincode = $_POST['pincode'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $landmark = $_POST['landmark'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Validate inputs (optional, based on your needs)
    if (empty($customer_name) || empty($email) || empty($emergency_contact_number) || empty($status) || empty($medical_conditions)) {
        echo "All fields are required!";
        exit;
    }

    // Check if the email already exists
    $check_email_query = "SELECT * FROM customer_master WHERE email = ?";
    if ($stmt = $conn->prepare($check_email_query)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Email already exists
            echo "The email address is already registered. Please use a different email.";
            $stmt->close();
            exit;
        }
        $stmt->close();
    }

    // Prepare SQL statement to insert customer data
    $sql = "INSERT INTO customer_master (
                patient_name, customer_name, email, emergency_contact_number, patient_status, medical_conditions, 
                relationship, blood_group, mobility_status, gender, patient_age, pincode, address_line1, address_line2, 
                landmark, city, state
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param(
            'sssssssssssssssss',  // data types for each field (17 placeholders)
            $patient_name, $customer_name, $email, $emergency_contact_number, $status, $medical_conditions, 
            $relationship, $blood_group, $mobility_status, $gender, $patient_age, $pincode, $address_line1, 
            $address_line2, $landmark, $city, $state
        );

        // Execute the statement
       
    if ($stmt->execute()) {
        echo "<script>alert('Customer added successfully'); window.location.href='services.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='services.php';</script>";
    }}

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    // Close the connection
    $conn->close();

?>