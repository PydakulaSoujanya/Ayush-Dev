<?php
include '../config.php';

$result = mysqli_query($conn, "SELECT MAX(id) AS last_id FROM vouchers");
$row = mysqli_fetch_assoc($result);
$nextId = isset($row['last_id']) ? $row['last_id'] + 1 : 1;
$voucherNumber = "VOU" . str_pad($nextId, 4, "0", STR_PAD_LEFT);

echo json_encode(['success' => true, 'voucher_number' => $voucherNumber]);
?>
