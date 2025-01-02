<?php
// Include database connection
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../config.php';
// Decode the JSON input
$requestPayload = file_get_contents("php://input");
$data = json_decode($requestPayload, true);


if (json_last_error() !== JSON_ERROR_NONE) {
    // Send error response if the JSON is malformed
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input',
    ]);
    exit();
}

// Extract fields with default values for missing keys
$vendorName = $data['vendor_name'] ?? null;
$gstin = $data['gstin'] ?? null;

$phoneNumber = $data['phone_number'] ?? null;
$email = $data['email'] ?? null;
$vendorType = $data['vendor_type'] ?? null;
$bankName = $data['bank_name'] ?? null;
$accountNumber = $data['account_number'] ?? null;

// Handle optional fields (set to default values if missing)
$address = $data['address'] ?? ''; // Default to empty string
$servicesProvided = $data['services_provided'] ?? ''; // Default to empty string
$additionalNotes = $data['additional_notes'] ?? ''; // Default to empty string
$ifsc = $data['ifsc'] ?? ''; // Default to empty string
$paymentTerms = $data['payment_terms'] ?? ''; // Default to empty string



// Sanitize inputs
$vendorName = mysqli_real_escape_string($conn, $vendorName);
$gstin = mysqli_real_escape_string($conn, $gstin);
$contactPerson = mysqli_real_escape_string($conn, $contactPerson);
$phoneNumber = mysqli_real_escape_string($conn, $phoneNumber);
$email = mysqli_real_escape_string($conn, $email);
$vendorType = mysqli_real_escape_string($conn, $vendorType);
$bankName = mysqli_real_escape_string($conn, $bankName);
$accountNumber = mysqli_real_escape_string($conn, $accountNumber);
$address = mysqli_real_escape_string($conn, $address);
$servicesProvided = mysqli_real_escape_string($conn, $servicesProvided);
$additionalNotes = mysqli_real_escape_string($conn, $additionalNotes);
$ifsc = mysqli_real_escape_string($conn, $ifsc);
$paymentTerms = mysqli_real_escape_string($conn, $paymentTerms);

// Insert data into the database
$sql = "INSERT INTO vendors (
            vendor_name, gstin,  phone_number, email, vendor_type, 
            bank_name, account_number, address, services_provided, additional_notes, 
            ifsc, payment_terms, created_at, updated_at
        ) VALUES (
            '$vendorName', '$gstin',  '$phoneNumber', '$email', '$vendorType', 
            '$bankName', '$accountNumber', '$address', '$servicesProvided', '$additionalNotes', 
            '$ifsc', '$paymentTerms', NOW(), NOW()
        )";

// Execute the query
if (mysqli_query($conn, $sql)) {
    // Get the last inserted vendor ID
    $vendorId = mysqli_insert_id($conn);

    // Send success response
    echo json_encode([
        'success' => true,
        'vendor' => [
            'id' => $vendorId,
            'vendor_name' => $vendorName,
            'phone_number' => $phoneNumber,
        ],
    ]);
} else {
    // Handle error and return the SQL query for debugging
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($conn),
        'sql' => $sql,  // Include the SQL query in the error response
    ]);
}

// Close the database connection
mysqli_close($conn);
}
?>
