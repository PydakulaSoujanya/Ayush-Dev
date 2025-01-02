<?php
// Database connection
include "../config.php";

// Fetch customers
$customers = $conn->query("SELECT customer_name FROM customer_master");

// Fetch invoices (duplicating entries)
$invoices = $conn->query("SELECT invoice_id FROM invoice UNION SELECT invoice_id FROM invoice");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = $_POST['customer'];
    $invoice = $_POST['invoice'];
    $receipt = $_POST['receipt'];
    $amount = $_POST['amount'];
    $tran_id = $_POST['tran_id']; // Get tran_id from the form

    // Insert into matched table
    $stmt = $conn->prepare("INSERT INTO matched (customer, invoice, receipt, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $customer, $invoice, $receipt, $amount);

    if ($stmt->execute()) {
        // Update the status in the deposits table
        $updateStmt = $conn->prepare("UPDATE deposits SET status = 'Matched' WHERE tran_id = ?");
        $updateStmt->bind_param("s", $tran_id); // Use tran_id to update the status
        $updateStmt->execute();
        $updateStmt->close();

        echo "<script>alert('Data submitted successfully!'); window.location.href = 'deposit.php';</script>";
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
    <title>Deposits Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Deposits Data</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <th>Remarks</th>
                <th>Deposit Amount</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch deposits data
            $sql = "SELECT * FROM deposits";
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
                    echo "<td>" . htmlspecialchars(number_format($row['deposit_amt'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['balance'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    if (htmlspecialchars($row['status']) === 'Matched') {
                        echo "<button class='btn btn-secondary btn-sm' disabled>Matched</button>";
                    } else {
                        echo "<button class='btn btn-primary btn-sm match-btn' data-bs-toggle='modal' data-bs-target='#matchModal' data-id='" . $row['id'] . "' data-tran-id='" . $row['tran_id'] . "'>Match</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>No data available</td></tr>";
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
                            <label for="customerSelect" class="form-label">Select Customer</label>
                            <select id="customerSelect" name="customer" class="form-select" required>
                                <?php
                                if ($customers->num_rows > 0) {
                                    while ($row = $customers->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['customer_name']) . "'>" . htmlspecialchars($row['customer_name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No customers available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="invoiceSelect" class="form-label">Select Invoice Number</label>
                            <select id="invoiceSelect" name="invoice" class="form-select" required>
                                <?php
                                if ($invoices->num_rows > 0) {
                                    while ($row = $invoices->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['invoice_id']) . "'>" . htmlspecialchars($row['invoice_id']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No invoices available</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="receiptSelect" class="form-label">Select Receipt</label>
                            <select id="receiptSelect" name="receipt" class="form-select" required>
                                <option value="">Select an invoice first</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amountInput" class="form-label">Amount</label>
                            <input type="number" id="amountInput" name="amount" class="form-control" placeholder="Enter amount" required readonly>
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
// Fetch related receipts dynamically
$('#invoiceSelect').on('change', function () {
    const invoiceId = $(this).val();
    $.ajax({
        url: 'fetch_receipts.php', // A separate PHP file for fetching receipts
        type: 'POST',
        data: { invoice_id: invoiceId },
        success: function (data) {
            $('#receiptSelect').html(data);
        },
        error: function () {
            alert('Failed to fetch receipts. Please try again.');
        }
    });
});

// Fetch paid amount dynamically
$('#receiptSelect').on('change', function () {
    const receiptId = $(this).val();
    $.ajax({
        url: 'fetch_paid_amount.php', // New PHP file to fetch paid amount
        type: 'POST',
        data: { receipt_id: receiptId },
        success: function (data) {
            const result = JSON.parse(data);
            $('#amountInput').val(result.paid_amount); // Set the paid amount in the input field
        },
        error: function () {
            alert('Failed to fetch paid amount. Please try again.');
        }
    });
});

// Set tran_id when the modal opens
$('.match-btn').on('click', function () {
    const tranId = $(this).data('tran-id');
    $('#tranIdInput').val(tranId);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>