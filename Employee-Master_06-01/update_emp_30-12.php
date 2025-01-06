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
                      beneficiary_name = ?, bank_name = ?, bank_account_no = ?, ifsc_code = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'sssssssssssssssssii',
            $name, $dob, $gender, $phone, $email,
            $role, $qualification, $experience, $doj,
            $aadhar, $police_verification,
            $daily_rate8, $daily_rate12, $daily_rate24, 
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
        for ($i = 0; $i < count($document_names); $i++) {
            $document_id = $document_ids[$i] ?? null;
            $document_name = $document_names[$i];
            $delete = $delete_docs[$i] ?? 0;

            if ($delete == 1 && !empty($document_id)) {
                $query_delete_doc = "DELETE FROM emp_documents WHERE id = ? AND emp_id = ?";
                $stmt_delete_doc = $conn->prepare($query_delete_doc);
                $stmt_delete_doc->bind_param('ii', $document_id, $employee_id);
                $stmt_delete_doc->execute();
            } elseif (!empty($document_id)) {
                $query_update_doc = "UPDATE emp_documents 
                                     SET document_name = ? 
                                     WHERE emp_id = ? AND id = ?";
                $stmt_update_doc = $conn->prepare($query_update_doc);
                $stmt_update_doc->bind_param('sii', $document_name, $employee_id, $document_id);
                $stmt_update_doc->execute();
            } else {
                // Handle file upload for new documents
                if (!empty($uploaded_files['name'][$i])) {
                    $file_tmp_name = $uploaded_files['tmp_name'][$i];
                    $file_name = $uploaded_files['name'][$i];
                    $upload_dir = "uploads/documents/";
                    $file_path = $upload_dir . time() . "_" . $file_name;

                    if (move_uploaded_file($file_tmp_name, $file_path)) {
                        $query_insert_doc = "INSERT INTO emp_documents (emp_id, document_name, file_path) 
                                             VALUES (?, ?, ?)";
                        $stmt_insert_doc = $conn->prepare($query_insert_doc);
                        $stmt_insert_doc->bind_param('iss', $employee_id, $document_name, $file_path);
                        $stmt_insert_doc->execute();
                    }
                }
            }
        }

        // Commit transaction
        $conn->commit();

        echo "<script>
                alert('Successfully updated record');
                window.location.href = 'manage_employee.php';
              </script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    die('Invalid request method.');
}
?>