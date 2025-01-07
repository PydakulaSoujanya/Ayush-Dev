<?php
// Start the session and connect to the database
session_start();
include('../config.php'); // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $role = $_POST['role'] ?? null;
    $qualification = $_POST['qualification'] ?? null;
    $experience = $_POST['experience'] ?? null;
    $doj = $_POST['doj'] ?? null;
    $aadhar = $_POST['aadhar'] ?? null;
    $police_verification = $_POST['police_verification'] ?? null;
    $status = $_POST['status'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $bank_account_no = $_POST['bank_account_no'] ?? null;
    $ifsc_code = $_POST['ifsc_code'] ?? null;
    $address = $_POST['address'] ?? null;
    $daily_rate8 = $_POST['daily_rate8'] ?? null;
    $daily_rate12 = $_POST['daily_rate12'] ?? null;
    $daily_rate24 = $_POST['daily_rate24'] ?? null;
    $reference = $_POST['reference'] ?? null;
    $vendor_name = $_POST['vendor_name'] ?? null;
    $vendor_id = $_POST['vendor_id'] ?? null;
    $vendor_contact = $_POST['vendor_contact'] ?? null;

    // Upload directory
    $uploadDir = "uploads/";

    // Begin transaction
    $conn->begin_transaction();
    try {
        // Insert employee data
        $stmt = $conn->prepare("INSERT INTO emp_info 
            (name, dob, gender, phone, email, role, qualification, experience, doj, aadhar, police_verification, status, daily_rate8, daily_rate12, daily_rate24, reference, vendor_name, vendor_id, vendor_contact, bank_name, bank_account_no, ifsc_code, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssssssssssssssss",
            $name, $dob, $gender, $phone, $email, $role, $qualification, $experience, $doj, $aadhar,
            $police_verification, $status, $daily_rate8, $daily_rate12, $daily_rate24, $reference,
            $vendor_name, $vendor_id, $vendor_contact, $bank_name, $bank_account_no, $ifsc_code, $address
        );
        $stmt->execute();

        // Get the inserted employee ID
        $emp_id = $conn->insert_id;

        // Handle file uploads
        foreach ($_FILES as $key => $file) {
            if ($file['error'] == 0) {
                $filePath = uploadFile($file, $uploadDir, $key);
                if ($filePath) {
                    // Insert document into emp_documents table
                    $docStmt = $conn->prepare("INSERT INTO emp_documents (emp_id, file_path, file_type) VALUES (?, ?, ?)");
                    $docStmt->bind_param("iss", $emp_id, $filePath, $key);
                    $docStmt->execute();
                }
            }
        }

        // Commit transaction
        $conn->commit();
        echo "<script>
                alert('Successfully added record');
                window.location.href = 'table.php';
              </script>";
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Close connection
$conn->close();

// File upload function
function uploadFile($file, $targetDir, $fieldName) {
    if (isset($file) && $file['error'] == 0) {
        // Ensure the target directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filePath = $targetDir . uniqid() . "_" . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            return $filePath; // Return file path to store in DB
        } else {
            echo "<script>alert('Error uploading $fieldName.');</script>";
        }
    }
    return null; // Return null if no file was uploaded
}
?>