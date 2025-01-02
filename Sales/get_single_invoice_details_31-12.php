<?php
require '../config.php';
// Get the receiptId from the request
$receiptId = $_GET['receiptId']; // Safely convert to integer
if (!$receiptId) {
    // If receiptId is not provided or invalid
    echo json_encode(['success' => false, 'message' => 'Invalid or missing receiptId', 'receiptId' => $receiptId]);
    exit;
}
// Fetch the invoice details from the database
$sql = "SELECT `id`, `invoice_id`, `customer_id`, `service_id`, `customer_name`, `mobile_number`, `customer_email`, `total_amount`, `due_date`, `status`, `created_at`, `updated_at`
        FROM `invoice` 
        WHERE `invoice_id` = '$receiptId'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch data as associative array
    $row = $result->fetch_assoc();

    // Return the data as JSON
    echo json_encode([
        'success' => true,
        'invoice_id' => $row['invoice_id'],
        'customer_name' => $row['customer_name'],
        'mobile_number' => $row['mobile_number'],
        'customer_email' => $row['customer_email'],
        'total_amount' => $row['total_amount'],
        'due_date' => $row['due_date'],
        'status' => $row['status'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing receiptId', 'receiptId' => $receiptId]);
}

$conn->close();
?>
