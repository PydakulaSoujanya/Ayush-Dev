<?php
// Database configuration
include "../config.php";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Collecting customer data
        $patient_status = $_POST['patient_status'];
        $patient_name = $_POST['patient_name'] ?? null;
        $customer_name = $_POST['customer_name'];
        
        // If patient name is not provided, set it to customer name
        if (empty($patient_name)) {
            $patient_name = $customer_name;
        }
        
        $relationship = $_POST['relationship'] ?? null;
        $emergency_contact_number = $_POST['emergency_contact_number'];
        $blood_group = $_POST['blood_group'];
        $medical_conditions = $_POST['medical_conditions'];
        $email = $_POST['email'];
        $patient_age = $_POST['patient_age'];
        $gender = $_POST['gender'];
        $mobility_status = $_POST['mobility_status'];

        // Handle file upload
        $discharge = null;
        if (isset($_FILES['discharge']) && $_FILES['discharge']['error'] === UPLOAD_ERR_OK) {
            $discharge = $_FILES['discharge']['name'];
            $upload_path = 'uploads/' . $discharge;
            move_uploaded_file($_FILES['discharge']['tmp_name'], $upload_path);
        }

        // Call stored procedure to insert customer data
        $sql = "CALL insert_customer(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssss",
            $patient_name, $relationship, $customer_name, $emergency_contact_number,
            $blood_group, $medical_conditions, $email, $patient_age, $gender,
            $mobility_status, $discharge
        );
        $stmt->execute();
        
        // Get the customer_id for address relationships
        $stmt->close();
        $customer_id = $conn->insert_id; // Retrieve the inserted customer_id
        
        // If customer_id is invalid, throw an error
        if (!$customer_id) {
            throw new Exception("Failed to retrieve customer_id");
        }

        // Validate and handle multiple addresses
        $pincodes = is_array($_POST['pincode']) ? $_POST['pincode'] : [];
        $address_line1s = is_array($_POST['address_line1']) ? $_POST['address_line1'] : [];
        $address_line2s = is_array($_POST['address_line2']) ? $_POST['address_line2'] : [];
        $landmarks = is_array($_POST['landmark']) ? $_POST['landmark'] : [];
        $cities = is_array($_POST['city']) ? $_POST['city'] : [];
        $states = is_array($_POST['state']) ? $_POST['state'] : [];

        // Call stored procedure to insert each address
        $addr_sql = "CALL insert_customer_address(?, ?, ?, ?, ?, ?, ?)";
        $addr_stmt = $conn->prepare($addr_sql);

        // Insert each address
        for ($i = 0; $i < count($pincodes); $i++) {
            // Assign variables before binding
            $pincode = $pincodes[$i] ?? null;
            $address_line1 = $address_line1s[$i] ?? null;
            $address_line2 = $address_line2s[$i] ?? null;
            $landmark = $landmarks[$i] ?? null;
            $city = $cities[$i] ?? null;
            $state = $states[$i] ?? null;

            // Only insert if pincode and address_line1 are provided
            if (!empty($pincode) && !empty($address_line1)) {
                $addr_stmt->bind_param(
                    "issssss",
                    $customer_id,  // Pass the customer_id
                    $pincode,
                    $address_line1,
                    $address_line2,
                    $landmark,
                    $city,
                    $state
                );
                
                if (!$addr_stmt->execute()) {
                    throw new Exception("Failed to insert address: " . $addr_stmt->error);
                }
            }
        }

        $addr_stmt->close();

        // Commit transaction
        $conn->commit();
        
        echo '<script>
            alert("Customer/Patient and addresses created successfully."); 
            window.location.href = "services.php";
        </script>';
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        file_put_contents('error_log.txt', "Transaction error: " . $e->getMessage() . "\n", FILE_APPEND);
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
}
?>
