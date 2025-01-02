<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Fetch form inputs
    $voucherNumber = $_POST['voucher_number'] ?? '';
    $voucherDate = $_POST['voucher_date'] ?? '';
    $purchaseInvoiceNumber = $_POST['purchase_invoice_number'] ?? '';
    $paidAmount = floatval($_POST['paid_amount'] ?? 0);
    $paymentMode = $_POST['payment_mode'] ?? '';
    $paidBy = $_POST['paid_by'] ?? '';
    $transactionId = $_POST['transaction_id'] ?? null;
    $referenceNumber = $_POST['reference_number'] ?? null;
    $remainingBalance = floatval($_POST['remaining_balance'] ?? 0);
    $paymentStatus = trim($_POST['payment_status'] ?? '');
    $vendorId = $_POST['vendor_id'] ?? '';

    // Validate payment_status
    $validStatuses = ['Pending', 'Partially Paid', 'Paid'];
    if (!in_array($paymentStatus, $validStatuses)) {
        $paymentStatus = ($paidAmount == 0) ? 'Pending' : (($remainingBalance <= 0) ? 'Paid' : 'Partially Paid');
    }

    // Debugging: Log all received values
    error_log("Voucher Number: $voucherNumber");
    error_log("Voucher Date: $voucherDate");
    error_log("Invoice Number: $purchaseInvoiceNumber");
    error_log("Paid Amount: $paidAmount");
    error_log("Remaining Balance: $remainingBalance");
    error_log("Payment Status: $paymentStatus");
    error_log("Payment Mode: $paymentMode");

    // Insert the voucher into the database
    $query = "INSERT INTO vouchers_new 
              (voucher_number, voucher_date, purchase_invoice_number, paid_amount, payment_mode, paid_by, transaction_id, reference_number, remaining_balance, payment_status, created_at, vendor_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "sssdsssssss",
            $voucherNumber,
            $voucherDate,
            $purchaseInvoiceNumber,
            $paidAmount,
            $paymentMode,
            $paidBy,
            $transactionId,
            $referenceNumber,
            $remainingBalance,
            $paymentStatus,
            $vendorId
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Voucher saved successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error saving voucher: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error preparing statement: " . $conn->error]);
    }
}
$conn->close();

?>
