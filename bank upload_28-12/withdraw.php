<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ayush_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor = $_POST['vendor'];
    $purchase_number = $_POST['purchase_number'];
    $voucher_number = $_POST['voucher_number'];
    $amount = $_POST['amount'];
    $tran_id = $_POST['tran_id']; 

    $stmt = $conn->prepare("INSERT INTO expensesmatch (vendor, purchase_number, voucher_number, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $vendor, $purchase_number, $voucher_number, $amount);

    if ($stmt->execute()) {
        $updateStmt = $conn->prepare("UPDATE withdrawals SET status = 'Matched' WHERE tran_id = ?");
        $updateStmt->bind_param("s", $tran_id); 
        $updateStmt->execute();
        $updateStmt->close();

        echo "<script>alert('Data submitted successfully!'); window.location.href = 'withdraw_new.php';</script>";
    } else {
        echo "<script>alert('Failed to submit data. Please try again.'); window.history.back();</script>";
    }
    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Withdrawal Data</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <th>Transaction Remarks</th>
                <th>Withdrawal Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM withdrawals";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tran_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['value_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_posted_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_remarks']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['withdrawal_amt'], 2)) . "</td>";
                    echo "<td class='status-cell'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    if (htmlspecialchars($row['status']) === 'Matched') {
                        echo "<button class='btn btn-secondary btn-sm' disabled>Matched</button>";
                    } else {
                        echo "<button class='btn btn-primary btn-sm match-btn' data-bs-toggle='modal' data-bs-target='#matchModal' data-id='" . $row['id'] . "' data-tran-id='" . $row['tran_id'] . "' data-amount='" . $row['withdrawal_amt'] . "'>Match</button>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Match Modal -->
<div class="modal fade" id="matchModal" tabindex="-1" aria-labelledby="matchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="matchModalLabel">Match Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<form method="POST" action="">
    <input type="hidden" id="tranIdInput" name="tran_id" value="">
    <div class="row">
    <div class="col-md-6">
        <label for="vendorInput" class="form-label">Vendor</label>
        <input type="text" id="vendorInput" name="vendor" class="form-control" readonly required>
    </div>
    <div class="col-md-6">
        <label for="purchaseNumberInput" class="form-label">Purchase Number</label>
        <input type="text" id="purchaseNumberInput" name="purchase_number" class="form-control" readonly required>
    </div>
        </div>
        <div class="row">
    <div class="col-md-6">
        <label for="voucherNumberInput" class="form-label">Voucher Number</label>
        <input type="text" id="voucherNumberInput" name="voucher_number" class="form-control" readonly required>
    </div>
    <div class="col-md-6">
        <label for="amountInput" class="form-label">Amount</label>
        <input type="text" id="amountInput" name="amount" class="form-control" readonly>
    </div>
        </div>
    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const matchButtons = document.querySelectorAll('.match-btn');
    const tranIdInput = document.getElementById('tranIdInput');
    const vendorInput = document.getElementById('vendorInput');
    const purchaseNumberInput = document.getElementById('purchaseNumberInput');
    const voucherNumberInput = document.getElementById('voucherNumberInput');
    const amountInput = document.getElementById('amountInput');

    matchButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tranId = button.getAttribute('data-tran-id');
            const amount = button.getAttribute('data-amount');

            tranIdInput.value = tranId;
            amountInput.value = amount;

            fetch(`fetch_voucher_data.php?amount=${amount}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        purchaseNumberInput.value = data.purchase_invoice_number;
                        voucherNumberInput.value = data.voucher_number;
                        vendorInput.value = data.vendor_name;
                    } else {
                        purchaseNumberInput.value = '';
                        voucherNumberInput.value = '';
                        vendorInput.value = '';
                        alert('No matching records found.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error fetching data.');
                });
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
