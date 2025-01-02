<?php
// Database connection
// $servername = "localhost";
// $username = "root"; // Default XAMPP username
// $password = ""; // Default XAMPP password
// $dbname = "transaction";

// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

include "../config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = $_POST['customer'];
    $invoice = $_POST['invoice'];
    $receipt = $_POST['receipt'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO matched (customer, invoice, receipt, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $customer, $invoice, $receipt, $amount);

    if ($stmt->execute()) {
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
                    echo "<td><button class='btn btn-primary btn-sm match-btn' data-bs-toggle='modal' data-bs-target='#matchModal' data-id='" . $row['id'] . "'>Match</button></td>";
                    echo "</tr>";
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
                    <div class="row">
                        <div class="col-md-6">
                            <label for="customerSelect" class="form-label">Select Customer</label>
                            <select id="customerSelect" name="customer" class="form-select" required>
                                <option value="Customer 1">Customer 1</option>
                                <option value="Customer 2">Customer 2</option>
                                <option value="Customer 3">Customer 3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="invoiceSelect" class="form-label">Select Invoice Number</label>
                            <select id="invoiceSelect" name="invoice" class="form-select" required>
                                <option value="INV-001">INV-001</option>
                                <option value="INV-002">INV-002</option>
                                <option value="INV-003">INV-003</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="receiptSelect" class="form-label">Select Receipt</label>
                            <select id="receiptSelect" name="receipt" class="form-select" required>
                                <option value="REC-001">REC-001</option>
                                <option value="REC-002">REC-002</option>
                                <option value="REC-003">REC-003</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amountInput" class="form-label">Amount</label>
                            <input type="number" id="amountInput" name="amount" class="form-control" placeholder="Enter amount" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
