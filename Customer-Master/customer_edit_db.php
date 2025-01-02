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

    if ($id > 0) {
        // Update existing record
        $sql = "UPDATE customer_master 
                SET  patient_name=?, relationship=?, customer_name=?, 
                    emergency_contact_number=?, blood_group=?, medical_conditions=?, email=?, 
                    patient_age=?, gender=?, mobility_status=?, address=?, discharge_summary_sheet=? 
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssississi",
            
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
            $discharge_summary_sheet,
            $id
        );
    } else {
        // Insert new record
        $sql = "INSERT INTO customer_master 
                (patient_name, relationship, customer_name, emergency_contact_number, 
                 blood_group, medical_conditions, email, patient_age, gender, mobility_status, address, discharge_summary_sheet) 
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssississ",
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

    if ($stmt->execute()) {
        // Success message
        echo "<script>
                alert('Customer details updated successfully!');
                window.location.href = 'customer_table.php';
              </script>";
    } else {
        // Error message
        echo "<script>
                alert('Error updating customer details: " . $stmt->error . "');
                window.location.href = 'customer_table.php';
              </script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>