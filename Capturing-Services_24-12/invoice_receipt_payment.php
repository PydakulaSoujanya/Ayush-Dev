<?php
header('Content-Type: application/json');

require '../config.php'; // Database connection

// Retrieve data from the request
$data = json_decode(file_get_contents('php://input'), true);

$receipt_id = $data['receipt_id'];
$invoice_id = $data['invoice_id'];
$amount_paid = $data['amount_paid'];


// Fetch existing invoice details
$invoice_query = $conn->prepare("SELECT * FROM invoice WHERE invoice_id = ? ORDER BY id ASC");

$invoice_query->bind_param('s', $invoice_id);
$invoice_query->execute();
$invoice_result = $invoice_query->get_result();

if ($invoice_result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Invoice not found']);
    exit;
}

$invoice = $invoice_result->fetch_assoc();

// Extract existing invoice data
$customer_id = $invoice['customer_id'];
$service_id = $invoice['service_id'];
$customer_name = $invoice['customer_name'];
$mobile_number = $invoice['mobile_number'];
$customer_email = $invoice['customer_email'];
$total_amount = $invoice['total_amount'];
$pdf_invoice_path=$invoice['pdf_invoice_path'];
$due_date = $invoice['due_date'];
$status = ($amount_paid >= $total_amount) ? 'Paid' : 'Pending'; // Determine status

$new_due_amount = $total_amount - $amount_paid;
$created_at = date('Y-m-d H:i:s'); // Current timestamp

// Insert a new row into the `invoice` table for the receipt
$insert_query = $conn->prepare("
    INSERT INTO invoice (
        invoice_id, receipt_id, customer_id, service_id, 
        customer_name, mobile_number, customer_email, total_amount,
        paid_amount, pdf_invoice_path, due_date, status, created_at,
        updated_at
    ) 
    VALUES (?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?)
");
$paid_amount = $amount_paid; // New paid amount
$insert_query->bind_param(
    'sssssssddsssss',
    $invoice_id, $receipt_id, $customer_id, $service_id,
    $customer_name,     $mobile_number, $customer_email,
    $total_amount, $paid_amount, 
    $pdf_invoice_path,
    $due_date, $status, $created_at, $created_at
);
$insert_success = $insert_query->execute();

if ($insert_success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to insert new invoice row']);
}

$conn->close();
?>
