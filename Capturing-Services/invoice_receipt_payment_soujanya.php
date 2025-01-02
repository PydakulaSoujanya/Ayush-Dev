<?php
header('Content-Type: application/json');

require '../config.php'; // Database connection

$input_data = json_decode(file_get_contents('php://input'), true);

if (isset($input_data['invoice_id']) && isset($input_data['amount_paid'])) {
    $invoice_id = $input_data['invoice_id'];
    $amount_paid = $input_data['amount_paid'];

    // Generate the next receipt ID
    $receipt_id_query = $conn->prepare("SELECT receipt_id FROM invoice ORDER BY id DESC LIMIT 1");
    $receipt_id_query->execute();
    $receipt_id_result = $receipt_id_query->get_result();

    if ($receipt_id_result->num_rows > 0) {
        $latest_receipt = $receipt_id_result->fetch_assoc()['receipt_id'];
        if (preg_match('/RC(\d{4,5})$/', $latest_receipt, $matches)) {
            $next_number = (int)$matches[1] + 1;
            $receipt_id = 'RC' . str_pad($next_number, 4, '0', STR_PAD_LEFT);
        } else {
            $receipt_id = 'RC0001';
        }
    } else {
        $receipt_id = 'RC0001';
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
    $customer_id = $invoice['customer_id'];
    $service_id = $invoice['service_id'];
    $customer_name = $invoice['customer_name'];
    $mobile_number = $invoice['mobile_number'];
    $customer_email = $invoice['customer_email'];
    $total_amount = $invoice['total_amount'];
    $pdf_invoice_path = $invoice['pdf_invoice_path'];
    $due_date = $invoice['due_date'];
    $status = ($amount_paid >= $total_amount) ? 'Paid' : 'Pending';
    $new_due_amount = $total_amount - $amount_paid;
    $created_at = date('Y-m-d H:i:s');

    // Insert a new row into the `invoice` table for the receipt
    $insert_query = $conn->prepare("
        INSERT INTO invoice (
            invoice_id, receipt_id, customer_id, service_id, 
            customer_name, mobile_number, customer_email, total_amount,
            paid_amount, pdf_invoice_path, due_date, status, created_at,
            updated_at
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $paid_amount = $amount_paid;
    $insert_query->bind_param(
        'sssssssddsssss',
        $invoice_id, $receipt_id, $customer_id, $service_id,
        $customer_name, $mobile_number, $customer_email,
        $total_amount, $paid_amount, 
        $pdf_invoice_path,
        $due_date, $status, $created_at, $created_at
    );
    $insert_success = $insert_query->execute();

    if ($insert_success) {
        echo json_encode(['success' => true, 'receipt_id' => $receipt_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to insert new invoice row']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing invoice_id or amount_paid']);
}

$conn->close();
?>
