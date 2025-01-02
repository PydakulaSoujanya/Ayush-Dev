<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_id = $_POST['bill_id'];
    $vendor_name = $_POST['vendor_name'];
    $amount_to_pay = $_POST['amount_to_pay'];
    $payment_mode = $_POST['payment_mode'];
    $transaction_id = $_POST['transaction_id'] ?? null;

    // Fetch the latest remaining balance and payment amount for the bill ID
    $query = "SELECT payment_amount, paid_amount, remaining_balance 
              FROM vendor_payments 
              WHERE bill_id = '$bill_id' 
              ORDER BY created_at DESC 
              LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $currentRemainingBalance = $row['remaining_balance'];
        $currentPaidAmount = $row['paid_amount'];
        $currentPaymentAmount = $row['payment_amount'];

        // Ensure the amount to pay does not exceed the remaining balance
        if ($amount_to_pay > $currentRemainingBalance) {
            echo json_encode(['success' => false, 'error' => 'Payment exceeds remaining balance']);
            exit;
        }

        // Calculate new values
        $newPaidAmount = $currentPaidAmount + $amount_to_pay;
        $newRemainingBalance = $currentRemainingBalance - $amount_to_pay;

        // Determine payment status
        $paymentStatus = $newRemainingBalance > 0 ? 'Partially Paid' : 'Paid';

        // Insert a new record with the updated payment details
        $insertQuery = "INSERT INTO vendor_payments 
                        (bill_id, vendor_name, payment_amount, paid_amount, remaining_balance, payment_status, payment_mode, transaction_id, payment_date) 
                        VALUES 
                        ('$bill_id', '$vendor_name', '$currentPaymentAmount', '$newPaidAmount', '$newRemainingBalance', '$paymentStatus', '$payment_mode', '$transaction_id', NOW())";

        if (mysqli_query($conn, $insertQuery)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Bill not found']);
    }
}
?>
