<?php
header('Content-Type: application/json');

require '../config.php'; // Database connection

$input_data = json_decode(file_get_contents('php://input'), true);

// Check if 'invoice_id' and 'amount_paid' are set in the decoded data
if (isset($input_data['invoice_id']) && isset($input_data['amount_paid'])) {
    $invoice_id = $input_data['invoice_id'];
    $amount_paid = $input_data['amount_paid'];

    $receipt_id_query = $conn->prepare("
    SELECT *, 
           (SELECT receipt_id FROM invoice ORDER BY id DESC LIMIT 1) AS latest_receipt_id 
    FROM invoice 
    WHERE invoice_id = ? 
    ORDER BY id ASC
");
$receipt_id_query->bind_param("i", $invoice_id); // Assuming $invoice_id is passed as input
$receipt_id_query->execute();
$receipt_id_result = $receipt_id_query->get_result();

// Check if any invoice details are returned
if ($receipt_id_result->num_rows > 0) {
    // Fetch the first row
    $invoice_details = $receipt_id_result->fetch_assoc();

    // Get the latest receipt_id from the subquery
    $latest_receipt_id = $invoice_details['latest_receipt_id'];

    // If the latest receipt_id exists, increment it
    if ($latest_receipt_id) {
        preg_match('/RCPT(\d+)$/', $latest_receipt_id, $matches);
        if (isset($matches[1])) {
            // Increment by 1 and format as 3 digits
            $next_receipt_number = str_pad($matches[1] + 1, 3, '0', STR_PAD_LEFT);
            $receipt_id = 'RCPT' . $next_receipt_number;
         
        }
    } else {
        // If no receipt exists, set the first receipt_id to RCPT001
        $receipt_id = 'RCPT001';
      
    }
} else {
    
}


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
}
else {
    // If invoice_id or amount_paid is missing
    $response = [
        'success' => false,
        'error' => 'Missing invoice_id or amount_paid'
    ];
}

$conn->close();
?>
