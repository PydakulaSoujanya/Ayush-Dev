

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $conn->begin_transaction(); // Start the transaction

    try {
        // Prepare and sanitize input data
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
        $daily_rate8 = $_POST['daily_rate8'] ?? null;
        $daily_rate12 = $_POST['daily_rate12'] ?? null;
        $daily_rate24 = $_POST['daily_rate24'] ?? null;
        $reference = $_POST['reference'] ?? null;
        $bank_name = $_POST['bank_name'] ?? null;
        $branch = $_POST['branch'] ?? null;
        $bank_account_no = $_POST['bank_account_no'] ?? null;
        $ifsc_code = $_POST['ifsc_code'] ?? null;
        $vendor_name = $_POST['vendor_name'] ?? null;
        $vendor_contact = $_POST['vendor_contact'] ?? null;
        $beneficiary_name = $_POST['beneficiary_name'] ?? null;
        $police_verification = $_POST['police_verification'] ?? null;

        // Handle file uploads for police verification document
        $police_verification_document = null;
        if (!empty($_FILES['police_verification_document']['name'])) {
            $police_verification_document = "../uploads/police_verification_" . time() . "_" . basename($_FILES['police_verification_document']['name']);
            if (!move_uploaded_file($_FILES['police_verification_document']['tmp_name'], $police_verification_document)) {
                throw new Exception("Failed to upload police verification document.");
            }
        }

        // Handle file upload for Aadhar
        $adhar_upload_doc = null;
        if (!empty($_FILES['adhar_upload_doc']['name'])) {
            $adhar_upload_doc = "../uploads/aadhar_" . time() . "_" . basename($_FILES['adhar_upload_doc']['name']);
            if (!move_uploaded_file($_FILES['adhar_upload_doc']['tmp_name'], $adhar_upload_doc)) {
                throw new Exception("Failed to upload Aadhar document.");
            }
        }

        // Insert into emp_info table
        $sql = "INSERT INTO emp_info (
                    name, dob, gender, phone, email, role, qualification, experience, doj, aadhar,
                    police_verification, police_verification_document, adhar_upload_doc, daily_rate8, daily_rate12,
                    daily_rate24, bank_name, bank_account_no, ifsc_code, branch, reference, vendor_name, vendor_contact, beneficiary_name
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssssssssss",
            $name, $dob, $gender, $phone, $email, $role, $qualification, $experience, $doj, $aadhar,
            $police_verification, $police_verification_document, $adhar_upload_doc, $daily_rate8, $daily_rate12,
            $daily_rate24, $bank_name, $bank_account_no, $ifsc_code, $branch, $reference, $vendor_name, $vendor_contact, $beneficiary_name
        );
        $stmt->execute();
        $emp_id = $conn->insert_id; // Get the inserted employee ID

        // Insert into emp_addresses
        if (!empty($_POST['address_line1'])) {
            $addresses = $_POST['address_line1'];
            $pincodes = $_POST['pincode'] ?? [];
            $cities = $_POST['city'] ?? [];
            $states = $_POST['state'] ?? [];
            $landmarks = $_POST['landmark'] ?? [];
            $address_line2 = $_POST['address_line2'] ?? [];

            foreach ($addresses as $index => $address) {
                if (!empty($address)) {
                    $pincode = $pincodes[$index] ?? null;
                    $city = $cities[$index] ?? null;
                    $state = $states[$index] ?? null;
                    $landmark = $landmarks[$index] ?? null;
                    $line2 = $address_line2[$index] ?? null;

                    $sql = "INSERT INTO emp_addresses (emp_id, address_line1, address_line2, landmark, city, state, pincode)
                            VALUES (?, ?, ?, ?, ?, ?, ?);";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("issssss", $emp_id, $address, $line2, $landmark, $city, $state, $pincode);
                    $stmt->execute();
                }
            }
        }

        // Insert into emp_documents
        if (!empty($_FILES['other_doc']['name'][0])) {
            foreach ($_FILES['other_doc']['name'] as $index => $fileName) {
                $fileTmp = $_FILES['other_doc']['tmp_name'][$index];
                $documentName = $_POST['other_doc_name'][$index] ?? "Untitled Document";
                $filePath = "../uploads/other_doc_" . time() . "_" . basename($fileName);

                if (move_uploaded_file($fileTmp, $filePath)) {
                    $sql = "INSERT INTO emp_documents (emp_id, file_path, document_name) VALUES (?, ?, ?);";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iss", $emp_id, $filePath, $documentName);
                    $stmt->execute();
                }
            }
        }

        $conn->commit(); // Commit the transaction
        header("Location: table.php?status=success");
        exit;
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction in case of an error
        header("Location: table.php?status=error&message=" . urlencode($e->getMessage()));
        exit;
    }
}
?>
