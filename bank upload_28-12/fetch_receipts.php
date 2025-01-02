<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceId = $_POST['invoice_id'];
    $result = $conn->query("SELECT receipt_id FROM invoice WHERE invoice_id = '$invoiceId'");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['receipt_id']) . "'>" . htmlspecialchars($row['receipt_id']) . "</option>";
        }
    } else {
        echo "<option value=''>No receipts available</option>";
    }
}
?>
``