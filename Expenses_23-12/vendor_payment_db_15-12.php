<?php
include('../config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bill_id = $_POST['bill_id'];
    $vendor_name = $_POST['vendor_name'];
    $payment_amount = $_POST['payment_amount'];
    $paid_amount = $_POST['paid_amount'];
    $remaining_balance = $_POST['remaining_balance'];
    $payment_status = $_POST['payment_status'];
    $payment_date = $_POST['payment_date'];
    $transaction_id = $_POST['transaction_id'];
    $payment_mode = $_POST['payment_mode'];
    $card_reference_number = $_POST['card_reference_number'];
    $bank_name = $_POST['bank_name'];

    // Insert into database
    $query = "INSERT INTO vendor_payments 
              (bill_id, vendor_name, payment_amount, paid_amount, remaining_balance, payment_status, payment_date, transaction_id, payment_mode, card_reference_number, bank_name) 
              VALUES 
              ('$bill_id', '$vendor_name', '$payment_amount', '$paid_amount', '$remaining_balance', '$payment_status', '$payment_date', '$transaction_id', '$payment_mode', '$card_reference_number', '$bank_name')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Vendor payment record submitted successfully!'); window.location.href='vendor_expenditure_table.php';</script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>alert('Error: $error_message'); window.location.href='vendor_billing_and_payouts.php';</script>";
    }
}
?>
