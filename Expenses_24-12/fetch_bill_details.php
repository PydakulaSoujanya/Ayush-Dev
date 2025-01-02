<?php
include('../config.php');

if (isset($_GET['payment_id'])) {
    $paymentId = mysqli_real_escape_string($conn, $_GET['payment_id']);

    // Fetch payment details
    $query = "SELECT vp.*, v.bank_name 
              FROM vendor_payments vp
              LEFT JOIN vendors v ON vp.vendor_name = v.vendor_name
              WHERE vp.id = '$paymentId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Prepare the response data
        $response = [
            'bill_id' => $data['bill_id'],
            'vendor_name' => $data['vendor_name'],
            'payment_amount' => $data['payment_amount'],
            'paid_amount' => $data['paid_amount'],
            'remaining_balance' => $data['remaining_balance'],
            'payment_status' => $data['payment_status'],
            'payment_date' => $data['payment_date'],
            'payment_mode' => $data['payment_mode'],
            'transaction_id' => $data['transaction_id'] ?? '',
            'card_reference_number' => $data['card_reference_number'] ?? '',
            'bank_name' => $data['bank_name'] ?? 'N/A', // Default bank name if not available
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Payment details not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
