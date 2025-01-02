<?php
include "../config.php";

if (isset($_GET['purchase_invoice_number'])) {
    $purchase_invoice_number = $_GET['purchase_invoice_number'];

    // Fetch voucher numbers and the corresponding paid amount for the selected purchase_invoice_number
    $sql = "SELECT voucher_number, paid_amount FROM vouchers_new WHERE purchase_invoice_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $purchase_invoice_number);
    $stmt->execute();
    $result = $stmt->get_result();

    $vouchers = [];
    $amount = 0;

    while ($row = $result->fetch_assoc()) {
        $vouchers[] = $row;
        $amount = $row['paid_amount']; // Assuming all vouchers for a purchase have the same amount
    }

    // Return the vouchers and amount in JSON format
    echo json_encode(['vouchers' => $vouchers, 'amount' => $amount]);

    $stmt->close();
}

$conn->close();
?>
