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
    $remainingBalance = floatval($_POST['remaining_balance'] ?? 0);
    $paymentStatus = trim($_POST['payment_status'] ?? '');
    $paymentMode = $_POST['payment_mode'] ?? '';

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
              (voucher_number, voucher_date, purchase_invoice_number, paid_amount, remaining_balance, payment_status, payment_mode, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "sssdsss",
            $voucherNumber,
            $voucherDate,
            $purchaseInvoiceNumber,
            $paidAmount,
            $remainingBalance,
            $paymentStatus,
            $paymentMode
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
