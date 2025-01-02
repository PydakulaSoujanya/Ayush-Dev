<?php
include "../config.php";

if (isset($_GET['voucher_number'])) {
    $voucherNumber = $_GET['voucher_number'];

    // Fetch the paid_amount for the selected voucher_number
    $stmt = $conn->prepare("SELECT paid_amount FROM vouchers_new WHERE voucher_number = ?");
    $stmt->bind_param("s", $voucherNumber);
    $stmt->execute();
    $stmt->bind_result($paidAmount);
    $stmt->fetch();

    echo $paidAmount; // Return the paid_amount directly

    $stmt->close();
}
$conn->close();
?>
