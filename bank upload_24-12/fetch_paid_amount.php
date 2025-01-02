<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiptId = $_POST['receipt_id'];
    $result = $conn->query("SELECT paid_amount FROM invoice WHERE receipt_id = '$receiptId'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['paid_amount' => $row['paid_amount']]);
    } else {
        echo json_encode(['paid_amount' => 0]);
    }
}
?>