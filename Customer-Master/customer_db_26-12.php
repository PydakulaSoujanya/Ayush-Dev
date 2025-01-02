<?php
// Database configuration
include "../config.php";
// Retrieve form data
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    
    $patient_name = $_POST['patient_name'] ?? null; // Use null coalescing in case the field is empty
    $relationship = $_POST['relationship'];
    $customer_name = $_POST['customer_name'];
    $emergency_contact_number = $_POST['emergency_contact_number'];

    $blood_group = $_POST['blood_group'];
    $medical_conditions = $_POST['medical_conditions'];
    $email = $_POST['email'];
    $patient_age = $_POST['patient_age'];
    $gender = $_POST['gender'];
  
  // Check if the patient is the same as the customer
if ($patient_name === null || $patient_name === '') {
    // If patient name is not provided, set patient name to customer name
    $patient_name = $customer_name;
}
  
    $mobility_status = $_POST['mobility_status'];
    $address = $_POST['address'];

    // File Uploads (if any)
  
    $discharge = $_FILES['discharge']['name'];

    // Move uploaded files to the server (you may need to adjust the folder path)
  
    move_uploaded_file($_FILES['discharge']['tmp_name'], 'uploads/' . $discharge);

    // SQL Query to Insert Data into customer_master table
  $sql = "INSERT INTO customer_master (
            patient_name, relationship, customer_name, emergency_contact_number, blood_group, medical_conditions, 
            email, patient_age, gender, mobility_status, address, discharge_summary_sheet, created_at, updated_at
        ) 
        VALUES (
            '$patient_name', '$relationship', '$customer_name', '$emergency_contact_number', '$blood_group', '$medical_conditions',
            '$email', '$patient_age', '$gender', '$mobility_status', '$address', '$discharge', NOW(), NOW()
        )";


    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Customer/Patient created successfully"); 
window.location.href = "customer_table.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>