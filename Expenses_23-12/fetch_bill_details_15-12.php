<?php
include('../config.php');

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch the specific payment record using payment_id
    $query = "SELECT id, bill_id, vendor_name, paid_amount, remaining_balance 
              FROM vendor_payments 
              WHERE id = '$payment_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo json_encode(mysqli_fetch_assoc($result));
    } else {
        echo json_encode(['error' => 'Payment record not found']);
    }
}
?>