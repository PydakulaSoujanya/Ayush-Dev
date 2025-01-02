<?php
include('../config.php');

$bill_id = $_GET['bill_id'];

// Fetch voucher details
$query = "SELECT * FROM vouchers WHERE bill_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $bill_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$total_query = "SELECT 
                    SUM(amount) as total_amount,
                    (SELECT SUM(amount) FROM vouchers WHERE bill_id = ?) as paid_amount
                FROM vouchers WHERE bill_id = ?";
$stmt = $conn->prepare($total_query);
$stmt->bind_param("ss", $bill_id, $bill_id);
$stmt->execute();
$totals = $stmt->get_result()->fetch_assoc();
$due_amount = $totals['total_amount'] - $totals['paid_amount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vouchers for Bill ID: <?= htmlspecialchars($bill_id) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Vouchers for Bill ID: <?= htmlspecialchars($bill_id) ?></h3>
    <p><strong>Total Amount:</strong> <?= number_format($totals['total_amount'], 2) ?></p>
    <p><strong>Paid Amount:</strong> <?= number_format($totals['paid_amount'], 2) ?></p>
    <p><strong>Due Amount:</strong> <?= number_format($due_amount, 2) ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Voucher Number</th>
                <th>Voucher Date</th>
                <th>Amount</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['voucher_number']) ?></td>
                    <td><?= htmlspecialchars($row['voucher_date']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
