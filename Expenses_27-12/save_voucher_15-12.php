<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billId = $_POST['bill_id'];
    $vendorName = $_POST['vendor_name'];
    $paidAmount = $_POST['paid_amount'];
    $paymentDate = $_POST['payment_date'];
    $paymentMode = $_POST['payment_mode'];
    $voucherDate = $_POST['voucher_date'];
    $transactionId = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
    $cardReferenceNumber = isset($_POST['card_reference_number']) ? $_POST['card_reference_number'] : null;
    $bankName = isset($_POST['bank_name']) ? $_POST['bank_name'] : null;

    // Generate the next voucher number
    $result = mysqli_query($conn, "SELECT MAX(id) AS last_id FROM vouchers");
    $row = mysqli_fetch_assoc($result);
    $nextId = isset($row['last_id']) ? $row['last_id'] + 1 : 1;
    $voucherNumber = "VOU" . str_pad($nextId, 4, "0", STR_PAD_LEFT);

    // Insert voucher into database
    $query = "INSERT INTO vouchers (voucher_number, voucher_date, bill_id, vendor_name, paid_amount, payment_date, payment_mode, transaction_id, card_reference_number, bank_name, created_at)
              VALUES ('$voucherNumber', '$voucherDate', '$billId', '$vendorName', '$paidAmount', '$paymentDate', '$paymentMode', '$transactionId', '$cardReferenceNumber', '$bankName', NOW())";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
