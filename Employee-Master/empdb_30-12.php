<?php
include '../config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();

    try {
        // Insert into `emp_info`
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $gender = isset($_POST['gender']) && !empty($_POST['gender']) ? $_POST['gender'] : null;
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $qualification = $_POST['qualification'];
        $experience = $_POST['experience'];
        $doj = $_POST['doj'];
        $aadhar = $_POST['aadhar'];
        $daily_rate8 = $_POST['daily_rate8'];
        $daily_rate12 = $_POST['daily_rate12'];
        $daily_rate24 = $_POST['daily_rate24'];
        $reference = $_POST['reference'];
        $bank_name = $_POST['bank_name'];
        $branch = $_POST['branch'];
        $bank_account_no = $_POST['bank_account_no'];
        $ifsc_code = $_POST['ifsc_code'];
        $vendor_name = $_POST['vendor_name'];
        $vendor_contact = $_POST['vendor_contact'];
        $vendor_id = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : null; // Fix: Check if key exists
        $police_verification = $_POST['police_verification'];
        
        $police_verification_form = null;
        if (!empty($_FILES['verification_document']['name'])) {
            $police_verification_form = "../uploads/police_verification_" . time() . "_" . basename($_FILES['verification_document']['name']);
            move_uploaded_file($_FILES['verification_document']['tmp_name'], $police_verification_form);
        }

        $adhar_upload_doc = null;
        if (!empty($_FILES['adhar_upload_doc']['name'])) {
            $adhar_upload_doc = "../uploads/aadhar_" . time() . "_" . basename($_FILES['adhar_upload_doc']['name']);
            move_uploaded_file($_FILES['adhar_upload_doc']['tmp_name'], $adhar_upload_doc);
        }

        $sql = "INSERT INTO emp_info (name, dob, gender, phone, email, role, qualification, experience, doj, aadhar, police_verification, police_verification_form, adhar_upload_doc, daily_rate8, daily_rate12, daily_rate24, bank_name, bank_account_no, ifsc_code, branch, reference, vendor_name, vendor_id, vendor_contact)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssssssssss", $name, $dob, $gender, $phone, $email, $role, $qualification, $experience, $doj, $aadhar, $police_verification, $police_verification_form, $adhar_upload_doc, $daily_rate8, $daily_rate12, $daily_rate24, $bank_name, $bank_account_no, $ifsc_code, $branch, $reference, $vendor_name, $vendor_id, $vendor_contact);
        $stmt->execute();
        $emp_id = $conn->insert_id;

        // Insert into `emp_addresses`
        if (!empty($_POST['address_line1'])) {
            $addresses = $_POST['address_line1'];
            $pincodes = $_POST['pincode'];
            $cities = $_POST['city'];
            $states = $_POST['state'];
            $landmarks = $_POST['landmark'];
            $address_line2 = isset($_POST['address_line2']) ? $_POST['address_line2'] : [];

            foreach ($addresses as $index => $address) {
                if (!empty($address) && !empty($pincodes[$index]) && !empty($cities[$index]) && !empty($states[$index])) {
                    $pincode = $pincodes[$index];
                    $city = $cities[$index];
                    $state = $states[$index];
                    $landmark = isset($landmarks[$index]) ? $landmarks[$index] : null;
                    $line2 = isset($address_line2[$index]) ? $address_line2[$index] : null;

                    $sql = "INSERT INTO emp_addresses (emp_id, address_line1, address_line2, landmark, city, state, pincode)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("issssss", $emp_id, $address, $line2, $landmark, $city, $state, $pincode);
                    $stmt->execute();
                } else {
                    echo "Address at index $index is incomplete and was skipped.";
                }
            }
        }

        // Insert other documents into `emp_documents`
        if (!empty($_FILES['other_doc']['name'][0])) {
            foreach ($_FILES['other_doc']['name'] as $index => $fileName) {
                $fileTmp = $_FILES['other_doc']['tmp_name'][$index];
                $documentName = $_POST['other_doc_name'][$index];
                $filePath = "../uploads/other_doc_" . time() . "_" . basename($fileName);
                move_uploaded_file($fileTmp, $filePath);

                $sql = "INSERT INTO emp_documents (emp_id, file_path, document_name) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $emp_id, $filePath, $documentName);
                $stmt->execute();
            }
        }

        $conn->commit();
        echo "<script>alert('Employee and related data saved successfully.'); window.location.href='table.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();

        // Show error alert
echo "<script>alert(" . json_encode("Error: " . $e->getMessage()) . "); window.location.href='table.php';</script>";
    }
}
?>
