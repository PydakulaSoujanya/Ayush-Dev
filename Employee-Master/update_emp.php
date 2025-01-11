<?php
// Include database connection
include('../config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve employee data
    $employee_id = $_POST['id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $doj = $_POST['doj'];
    $aadhar = $_POST['aadhar'];
    $police_verification = $_POST['police_verification'];
    $daily_rate8 = $_POST['daily_rate8'];
    $daily_rate12 = $_POST['daily_rate12'];
    $daily_rate24 = $_POST['daily_rate24'];
    $vendor_name = $_POST['vendor_name'];
    $beneficiary_name = $_POST['beneficiary_name'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];

    // Retrieve address data
    $address_ids = $_POST['address_id'] ?? [];
    $address_line1 = $_POST['address_line1'] ?? [];
    $address_line2 = $_POST['address_line2'] ?? [];
    $landmark = $_POST['landmark'] ?? [];
    $city = $_POST['city'] ?? [];
    $state = $_POST['state'] ?? [];
    $pincode = $_POST['pincode'] ?? [];
    $delete_markers = $_POST['delete'] ?? [];

    // Retrieve document data
    $document_ids = $_POST['document_id'] ?? [];
    $document_names = $_POST['other_doc_name'] ?? [];
    $delete_docs = $_POST['delete_doc'] ?? [];

    // File data
    $uploaded_files = $_FILES['other_doc'] ?? [];

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Update employee information in emp_info table
        $query = "UPDATE emp_info 
                  SET name = ?, dob = ?, gender = ?, phone = ?, email = ?, 
                      role = ?, qualification = ?, experience = ?, doj = ?, 
                      aadhar = ?, police_verification = ?,  
                      daily_rate8 = ?, daily_rate12 = ?, daily_rate24 = ?, 
                      vendor_name = ?, beneficiary_name = ?, bank_name = ?, bank_account_no = ?, ifsc_code = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'sssssssssssssssssssi',
            $name, $dob, $gender, $phone, $email,
            $role, $qualification, $experience, $doj,
            $aadhar, $police_verification,
            $daily_rate8, $daily_rate12, $daily_rate24, $vendor_name,
            $beneficiary_name, $bank_name, $bank_account_no, $ifsc_code,
            $employee_id
        );
        $stmt->execute();

        // Loop through addresses and update/insert/delete as needed
        for ($i = 0; $i < count($address_line1); $i++) {
            $address_id = $address_ids[$i];
            $address1 = $address_line1[$i];
            $address2 = $address_line2[$i];
            $landmark_address = $landmark[$i];
            $city_address = $city[$i];
            $state_address = $state[$i];
            $pincode_address = $pincode[$i];
            $delete = $delete_markers[$i] ?? 0;

            if ($delete == 1 && !empty($address_id)) {
                $query_delete = "DELETE FROM emp_addresses WHERE id = ? AND emp_id = ?";
                $stmt_delete = $conn->prepare($query_delete);
                $stmt_delete->bind_param('ii', $address_id, $employee_id);
                $stmt_delete->execute();
            } elseif (!empty($address_id)) {
                $query_update = "UPDATE emp_addresses 
                                 SET address_line1 = ?, address_line2 = ?, landmark = ?, city = ?, state = ?, pincode = ? 
                                 WHERE emp_id = ? AND id = ?";
                $stmt_update = $conn->prepare($query_update);
                $stmt_update->bind_param(
                    'ssssssii',
                    $address1, $address2, $landmark_address, 
                    $city_address, $state_address, $pincode_address, 
                    $employee_id, $address_id
                );
                $stmt_update->execute();
            } else {
                $query_insert = "INSERT INTO emp_addresses (emp_id, address_line1, address_line2, landmark, city, state, pincode) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->bind_param(
                    'issssss',
                    $employee_id, $address1, $address2, 
                    $landmark_address, $city_address, $state_address, $pincode_address
                );
                $stmt_insert->execute();
            }
        }

        // Handle documents
// Handle documents
foreach ($document_ids as $index => $doc_id) {
            // Sanitize document name
            $doc_name = $document_names[$index] ?? '';
            $doc_name = mysqli_real_escape_string($conn, $doc_name);

            // Check if the document should be deleted
            if (isset($delete_docs[$index]) && $delete_docs[$index] == 1) {
                // Prepare the DELETE query
                $query = "DELETE FROM emp_documents WHERE id = ? AND emp_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $doc_id, $employee_id);

                // Execute the DELETE query
                if (!$stmt->execute()) {
                    throw new Exception("Error deleting document: " . $stmt->error);
                }
                continue;  // Skip to the next document in the array
            }

            // File upload logic (only if file is uploaded)
            $file_path = null;
            if (isset($uploaded_files['name'][$index]) && $uploaded_files['error'][$index] == 0) {
                $file_name = $uploaded_files['name'][$index];
                $tmp_name = $uploaded_files['tmp_name'][$index];
                $upload_dir = 'uploads/documents/';
                $new_file_name = time() . "_" . basename($file_name);
                $file_path = $upload_dir . $new_file_name;

                // Ensure upload directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($tmp_name, $file_path)) {
                    throw new Exception("Failed to upload document: $file_name");
                }
            }

            // Update or Insert the document record
            if ($doc_id) {
                // Update existing document for the specific emp_id and doc_id
                $query = "UPDATE emp_documents SET document_name = ?, file_path = COALESCE(?, file_path) WHERE id = ? AND emp_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssii', $doc_name, $file_path, $doc_id, $employee_id);
            } else {
                // Insert new document for the given emp_id
                $query = "INSERT INTO emp_documents (emp_id, document_name, file_path) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iss', $employee_id, $doc_name, $file_path);
            }

            // Execute the query and check for errors
            if (!$stmt->execute()) {
                throw new Exception("Error updating/inserting document: " . $stmt->error);
            }
        }

// Commit transaction if all document handling is successful
$conn->commit();

// Success alert
echo "<script>
        alert('Successfully updated record');
        window.location.href = 'table.php';
      </script>";
} catch (Exception $e) {
    // Rollback transaction if there was an error
    $conn->rollback();

    // Display the error message
    echo "Error: " . $e->getMessage();
}

} else {
    die('Invalid request method.');
}
?>
