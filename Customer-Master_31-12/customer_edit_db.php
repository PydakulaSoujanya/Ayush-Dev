<?php
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the ID of the record being updated
    $id = $_POST['id'];

    // Get form data
    $patient_name = $_POST['patient_name'] ?? null;
    $relationship = $_POST['relationship'];
    $customer_name = $_POST['customer_name'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    $blood_group = $_POST['blood_group'];
    $medical_conditions = $_POST['medical_conditions'];
    $email = $_POST['email'];
    $patient_age = $_POST['patient_age'];
    $gender = $_POST['gender'];
    $mobility_status = $_POST['mobility_status'];
    $address = $_POST['address'];

    // Handle file uploads
    $discharge_summary_sheet = !empty($_FILES['discharge_summary_sheet']['name']) ? $_FILES['discharge_summary_sheet']['name'] : null;

    if ($discharge_summary_sheet) {
        move_uploaded_file($_FILES['discharge_summary_sheet']['tmp_name'], "uploads/" . $discharge_summary_sheet);
    }

    // Check if it's an update or insert
    if ($id > 0) {
        // Update existing record using stored procedure
        $sql = "CALL UpdateCustomerData(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issssssississ",
            $id,
            $patient_name,
            $relationship,
            $customer_name,
            $emergency_contact_number,
            $blood_group,
            $medical_conditions,
            $email,
            $patient_age,
            $gender,
            $mobility_status,
            $address,
            $discharge_summary_sheet
        );
    } else {
        // Insert new record using stored procedure
        $sql = "CALL InsertCustomerData(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssss",
            $patient_name,
            $relationship,
            $customer_name,
            $emergency_contact_number,
            $blood_group,
            $medical_conditions,
            $email,
            $patient_age,
            $gender,
            $mobility_status,
            $address,
            $discharge_summary_sheet
        );
    }

    // Execute the query
    if ($stmt->execute()) {
        // Success message
        echo "<script>
                alert('Customer details " . ($id > 0 ? 'updated' : 'added') . " successfully!');
                window.location.href = 'customer_table.php';
              </script>";
    } else {
        // Error message
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.location.href = 'customer_table.php';
              </script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
