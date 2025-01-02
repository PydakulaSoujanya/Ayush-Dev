<?php
include('../config.php');

// Fetch voucher details for the invoice
$purchase_invoice_number = $_GET['purchase_invoice_number'] ?? '';
$vendor_name = $invoice_amount = $voucher_number = $today_date = '';

// Fetch total, paid, and due amounts
$totalQuery = "SELECT 
    COALESCE(SUM(voucher.paid_amount), 0) as paid_amount,
    (SELECT invoice_amount FROM vendor_payments_new WHERE purchase_invoice_number = ?) as total_amount
FROM vouchers_new as voucher
WHERE voucher.purchase_invoice_number = ?";

$stmtTotals = $conn->prepare($totalQuery);
$stmtTotals->bind_param("ss", $purchase_invoice_number, $purchase_invoice_number);
$stmtTotals->execute();
$totals = $stmtTotals->get_result()->fetch_assoc();

$totalAmount = $totals['total_amount'] ?? 0;
$paidAmount = $totals['paid_amount'] ?? 0;
$dueAmount = $totalAmount - $paidAmount;

// Determine whether it's the first payment
$isFirstPayment = ($paidAmount == 0);

// Fetch the next voucher number globally
$voucherQuery = "SELECT MAX(voucher_number) AS last_voucher FROM vouchers_new";
$resultVoucher = $conn->query($voucherQuery);
$lastVoucher = $resultVoucher->fetch_assoc()['last_voucher'] ?? 'VOU00';

// Increment the voucher number
$voucherNumber = "VOU" . str_pad((int)substr($lastVoucher, 3) + 1, 2, "0", STR_PAD_LEFT);

// Fetch vendor details for the given invoice
if ($purchase_invoice_number) {
    $query = "SELECT vendor_name, invoice_amount FROM vendor_payments_new WHERE purchase_invoice_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $purchase_invoice_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $vendor_name = $row['vendor_name'];
        $invoice_amount = $row['invoice_amount'];
        $today_date = date('Y-m-d');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../assets/css/style.css">

  <title>Data Table</title>
  <!-- <style>
    .dataTable_wrapper {
      padding: 20px;
    }

    .dataTable_search input {
      max-width: 200px;
    }

    .dataTable_headerRow th,
    .dataTable_row td {
      border: 1px solid #dee2e6; /* Add borders for columns */
    }

    .dataTable_headerRow {
      background-color: #f8f9fa;
      font-weight: bold;
    }

    .dataTable_row:hover {
      background-color: #f1f1f1;
    }

    .dataTable_card {
      border: 1px solid #ced4da; /* Add card border */
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dataTable_card .card-header {
      background-color:  #A26D2B;
      color: white;
      font-weight: bold;
    }
  </style> -->
</head>
<body>
<?php

include('../navbar.php');
?>
 <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Vouchers</div>
<div class="card-body">
    <div class="dataTable_search mb-3 d-flex align-items-center">
        <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
        <?php
        // Initialize the variable to avoid undefined warnings
        $is_paid = false;

        // Fetch vouchers for the given invoice
        $query = "SELECT * FROM vouchers_new WHERE purchase_invoice_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $purchase_invoice_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check payment statuses
        while ($row = $result->fetch_assoc()) {
            if ($row['payment_status'] === 'Paid') {
                $is_paid = true;
            }
        }

        // Determine visibility of the "+ Voucher" button
        $button_visibility = $is_paid ? 'style="display:none;"' : '';
        ?>

        <!-- "+ Voucher" Button -->
        <a type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#voucherModal" <?= $button_visibility; ?>>
            + Voucher
        </a>
    </div>

    <h3>Vouchers for Invoice Number: <?= htmlspecialchars($purchase_invoice_number) ?></h3>
    <div class="row mb-3" style="text-align: center;">
        <div class="col-md-4">
            <p><strong>Total Amount:</strong> <?= number_format($totalAmount) ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Paid Amount:</strong> <?= number_format($paidAmount) ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Due Amount:</strong> <?= number_format($dueAmount) ?></p>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-responsive table-striped" id="vouchersTable">
            <thead class="thead-dark">
                <tr>
                    <th>Voucher Number</th>
                    <th>Voucher Date</th>
                    <th>Paid Amount</th>
                    <th>Remaining Balance</th>
                    <th>Payment Status</th>
                    <th>Payment Mode</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the result pointer to display the rows
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['voucher_number'] ?></td>
                        <td><?= date("d-m-Y", strtotime($row['voucher_date'])) ?></td>
                        <td><?= number_format($row['paid_amount'], 2) ?></td>
                        <td><?= number_format($row['remaining_balance'], 2) ?></td>
                        <td><?= $row['payment_status'] ?></td>
                        <td><?= $row['payment_mode'] ?></td>
                        <td><?= date("d-m-Y H:i:s", strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


        <!-- Pagination Controls -->
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <button id="previousPage" class="btn btn-sm btn-primary me-2">Previous</button>
            <button id="nextPage" class="btn btn-sm btn-primary">Next</button>
          </div>
          <div class="dataTable_pageInfo">
            Page <strong id="pageInfo">1 of 1</strong>
          </div>
          <div>
            <select id="pageSize" class="form-select form-select-sm">
              <option value="5">Show 5</option>
              <option value="10">Show 10</option>
              <option value="20">Show 20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Modal for Voucher -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="voucherModalLabel">Generate Voucher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="voucherForm">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="voucher_number" class="form-label">Voucher Number</label>
                <input type="text" class="form-control" id="voucher_number" name="voucher_number" value="<?= htmlspecialchars($voucherNumber) ?>" readonly>

              </div>
              <div class="col-md-6 mb-3">
                <label for="voucher_date" class="form-label">Voucher Date</label>
                <input type="date" class="form-control" id="voucher_date" name="voucher_date" value="<?= $today_date ?>" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="purchase_invoice_number" class="form-label">Invoice Number</label>
                <input type="text" class="form-control" id="purchase_invoice_number" name="purchase_invoice_number" value="<?= $purchase_invoice_number ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label for="vendor_name" class="form-label">Vendor Name</label>
                <input type="text" class="form-control" id="vendor_name" name="vendor_name" value="<?= $vendor_name ?>" readonly>
              </div>
            </div>
            <div class="row">
            <div class="col-md-6 mb-3">
    <label for="invoice_amount" class="form-label">
        <?= $isFirstPayment ? "Invoice Amount" : "Due Amount" ?>
    </label>
    <input type="text" class="form-control" id="invoice_amount" name="invoice_amount" 
           value="<?= number_format($isFirstPayment ? $invoice_amount : $dueAmount, 2) ?>" readonly>
</div>
              <div class="col-md-6 mb-3">
                <label for="paid_amount" class="form-label">Paying Amount</label>
                <input type="number" class="form-control" id="paid_amount" name="paid_amount" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="remaining_balance" class="form-label">Remaining Balance</label>
                <input type="text" class="form-control" id="remaining_balance" name="remaining_balance" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label for="payment_status" class="form-label">Payment Status</label>
                <input type="text" class="form-control" id="payment_status" name="payment_status" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="payment_mode" class="form-label">Payment Mode</label>
                <select class="form-control" id="payment_mode" name="payment_mode" required>
                  <option value="" disabled selected>Select Payment Mode</option>
                  <option value="UPI">UPI</option>
                  <option value="Cash">Cash</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Voucher</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
document.getElementById('voucherForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Recalculate payment_status before submitting
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const dueAmount = parseFloat(document.getElementById('invoice_amount').value.replace(/,/g, '')) || 0;
    const remainingBalance = dueAmount - paidAmount;

    let paymentStatus = '';
    if (paidAmount === 0) {
        paymentStatus = 'Pending';
    } else if (remainingBalance <= 0) {
        paymentStatus = 'Paid';
    } else {
        paymentStatus = 'Partially Paid';
    }
    document.getElementById('payment_status').value = paymentStatus;

    // Debug form data before submitting
    const formData = new FormData(this);
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch('save_voucher.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to save voucher.');
        }
    })
    .catch(error => console.error('Error:', error));
});

  

document.getElementById('paid_amount').addEventListener('input', function () {
    const dueAmount = parseFloat(document.getElementById('invoice_amount').value.replace(/,/g, '')) || 0;
    const currentPayingAmount = parseFloat(this.value) || 0;

    // Calculate remaining balance
    const remainingBalance = dueAmount - currentPayingAmount;
    document.getElementById('remaining_balance').value = remainingBalance.toFixed(2);

    // Update payment status
    let paymentStatus = '';
    if (currentPayingAmount === 0) {
        paymentStatus = 'Pending';
    } else if (remainingBalance <= 0) {
        paymentStatus = 'Paid';
    } else {
        paymentStatus = 'Partially Paid';
    }
    document.getElementById('payment_status').value = paymentStatus; // Update the input
});
  </script>
  <script>
// Search functionality
document.getElementById('globalSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase(); // Convert search text to lowercase
    const rows = document.querySelectorAll("#vouchersTable tbody tr"); // Select all table rows

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase(); // Get all text in the row
        if (rowText.includes(query)) {
            row.style.display = ""; // Show row if it matches
        } else {
            row.style.display = "none"; // Hide row if it doesn't match
        }
    });
});
</script>
</body>
</html>
