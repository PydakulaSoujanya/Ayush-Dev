<?php
// Database connection
include "../config.php";

// Fetch withdrawal data
$sql = "SELECT * FROM withdrawals";
$result = $conn->query($sql);

// Fetch vendors
$vendors = $conn->query("SELECT vendor_name FROM vendors");

// Fetch invoices (avoiding duplicating entries)
$purchases = $conn->query("SELECT DISTINCT purchase_invoice_number FROM vouchers_new");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor = $_POST['vendor'];
    $purchase = $_POST['purchase'];
    $voucher_number = $_POST['voucher_number'];
    $amount = $_POST['amount'];
    $tran_id = $_POST['tran_id']; // Get tran_id from the form

    // Insert into matched table
    $stmt = $conn->prepare("INSERT INTO expensesmatched (vendor, purchase, voucher_number, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $vendor, $purchase, $voucher_number, $amount);

    if ($stmt->execute()) {
        // Update the status in the withdrawals table
        $updateStmt = $conn->prepare("UPDATE withdrawals SET status = 'Matched' WHERE tran_id = ?");
        $updateStmt->bind_param("s", $tran_id); // Use tran_id to update the status
        $updateStmt->execute();
        $updateStmt->close();

        echo "<script>alert('Data submitted successfully!'); window.location.href = 'withdraw.php';</script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Withdrawals Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Withdrawals Data</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <th>Transaction Remarks</th>
                <th>Withdrawal Amount</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
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
                    echo "<td>" . htmlspecialchars(number_format($row['balance'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    if (htmlspecialchars($row['status']) === 'Matched') {
                        echo "<button class='btn btn-secondary btn-sm' disabled>Matched</button>";
                    } else {
                        echo "<button class='btn btn-primary btn-sm match-btn' data-bs-toggle='modal' data-bs-target='#matchwithdrawModal' data-id='" . $row['id'] . "' data-tran-id='" . $row['tran_id'] . "'>Match</button>";
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

    <!-- Match Modal -->
    <div class="modal fade" id="matchwithdrawModal" tabindex="-1" aria-labelledby="matchModalLabel" aria-hidden="true">
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
                                <label for="vendorSelect" class="form-label">Select vendor</label>
                                <select id="vendorSelect" name="vendor" class="form-select" required>
                                    <?php
                                    if ($vendors->num_rows > 0) {
                                        while ($row = $vendors->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['vendor_name']) . "'>" . htmlspecialchars($row['vendor_name']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No vendors available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="purchaseSelect" class="form-label">Select Purchase Number</label>
                                <select id="purchaseSelect" name="purchase" class="form-select" required>
                                    <?php
                                    if ($purchases->num_rows > 0) {
                                        while ($row = $purchases->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['purchase_invoice_number']) . "'>" . htmlspecialchars($row['purchase_invoice_number']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No purchases available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="voucherSelect" class="form-label">Select Voucher Number</label>
                                <select id="voucherSelect" name="voucher_number" class="form-select" required>
                                    <option value="">Select a Purchase first</option>
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
$(document).ready(function() {
    // Set tran_id when the modal opens
    $('.match-btn').on('click', function () {
        const tranId = $(this).data('tran-id');
        $('#tranIdInput').val(tranId);
    });

    // When purchase is selected, fetch related voucher numbers and amount
    $('#purchaseSelect').on('change', function () {
        var purchaseInvoiceNumber = $(this).val();
        if (purchaseInvoiceNumber) {
            // Make AJAX request to get voucher numbers and amount for the selected purchase_invoice_number
            $.ajax({
                url: 'fetch_vochure.php', // PHP file to fetch data
                type: 'GET',
                data: { purchase_invoice_number: purchaseInvoiceNumber },
                success: function (response) {
                    var data = JSON.parse(response);
                    var voucherSelect = $('#voucherSelect');
                    voucherSelect.empty();
                    if (data.vouchers.length > 0) {
                        data.vouchers.forEach(function (voucher) {
                            voucherSelect.append('<option value="' + voucher.voucher_number + '">' + voucher.voucher_number + '</option>');
                        });
                    } else {
                        voucherSelect.append('<option value="">No vouchers available</option>');
                    }
                    // Set amount input to the first voucher's paid amount (if available)
                    if (data.vouchers.length > 0) {
                        $('#amountInput').val(data.amount);
                    } else {
                        $('#amountInput').val('');
                    }
                }
            });
        } else {
            $('#voucherSelect').empty().append('<option value="">Select a Purchase first</option>');
            $('#amountInput').val('');
        }
    });

    // When voucher is selected, fetch the corresponding paid_amount and update the amount field
    $('#voucherSelect').on('change', function () {
        var voucherNumber = $(this).val();
        if (voucherNumber) {
            // Make AJAX request to get the paid_amount for the selected voucher_number
            $.ajax({
                url: 'fetch_expenses_paid_amount.php', // PHP file to fetch the paid amount
                type: 'GET',
                data: { voucher_number: voucherNumber },
                success: function (response) {
                    $('#amountInput').val(response); // Update amount input
                }
            });
        } else {
            $('#amountInput').val(''); // Clear the field if no voucher is selected
        }
    });
});

</script>


    <?php $conn->close(); ?>
</body>
</html>
