<?php
include '../config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data (use null coalescing operator to handle empty fields)
$patient_status = $_POST['patient_status'] ?? null;
$patient_name = $_POST['patient_name'] ?? null;
$relationship = $_POST['relationship'] ?? null;
$customer_name = $_POST['customer_name'] ?? null;
$emergency_contact_number = $_POST['emergency_contact_number'] ?? null;
$blood_group = $_POST['blood_group'] ?? null;
$medical_conditions = $_POST['medical_conditions'] ?? null;
$email = $_POST['email'] ?? null;
$patient_age = $_POST['patient_age'] ?? null;
$gender = $_POST['gender'] ?? null;
$mobility_status = $_POST['mobility_status'] ?? null;
$pincode = $_POST['pincode'] ?? null;
$address_line1 = $_POST['address_line1'] ?? null;
$address_line2 = $_POST['address_line2'] ?? null;
$landmark = $_POST['landmark'] ?? null;
$city = $_POST['city'] ?? null;
$state = $_POST['state'] ?? null;

// Handle file upload (discharge file)
$discharge_file = null;
if (isset($_FILES['discharge']) && $_FILES['discharge']['error'] == 0) {
    $target_dir = "uploads/";
    $discharge_file = $target_dir . basename($_FILES['discharge']['name']);
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create uploads directory if not exists
    }
    move_uploaded_file($_FILES['discharge']['tmp_name'], $discharge_file);
}

// Insert into database
$sql = "INSERT INTO customer_master (
            patient_status, patient_name, relationship, customer_name, 
            emergency_contact_number, blood_group, medical_conditions, 
            email, patient_age, gender, mobility_status, 
          pincode, address_line1, address_line2, 
            landmark, city, state
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssissssssss",
    $patient_status,
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
   
    $pincode,
    $address_line1,
    $address_line2,
    $landmark,
    $city,
    $state
);

if ($stmt->execute()) {
    echo "<script>alert('Form submitted successfully!'); window.location.href = 'services.php';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'services.php';</script>";
}

// Close connections
$stmt->close();
$conn->close();
?>
