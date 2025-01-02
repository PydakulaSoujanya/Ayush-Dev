<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "ayush_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request for modal data
if (isset($_GET['tran_id'])) {
    $tran_id = $_GET['tran_id'];

    // Fetch deposit amount from deposits table
    $sql = "SELECT deposit_amt FROM deposits WHERE tran_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tran_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $depositData = $result->fetch_assoc();

    // Fetch receipt ID, invoice ID, and customer ID from the invoice table
    $sql = "SELECT receipt_id, invoice_id, customer_id FROM invoice WHERE paid_amount = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $depositData['deposit_amt']);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoiceData = $result->fetch_assoc();

    // Fetch customer name from the customer_master table
    $customerName = null;
    if (!empty($invoiceData['customer_id'])) {
        $sql = "SELECT customer_name FROM customer_master WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $invoiceData['customer_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $customerData = $result->fetch_assoc();
        $customerName = $customerData['customer_name'] ?? null;
    }

    // Return JSON response
    echo json_encode([
        'amount' => $depositData['deposit_amt'],
        'receipt_id' => $invoiceData['receipt_id'] ?? null,
        'invoice_id' => $invoiceData['invoice_id'] ?? null,
        'customer_name' => $customerName,
    ]);
    exit;
}

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
        $updateStmt->bind_param("s", $tran_id);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Deposits Data</title>
</head>
<body>
<?php 
include '../navbar.php';
  ?>
 <div class="container mt-7">
    <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 table-title">Deposits Data</h5>
    </div>
    <div class="table-responsive mt-3 p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark mt-4">
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <th>Remarks</th>
                <th>Deposit Amount</th>
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
                    echo "<tr data-row-id='" . htmlspecialchars($row['id']) . "'>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tran_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['value_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_posted_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_remarks']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['deposit_amt'], 2)) . "</td>";
                    echo "<td class='status-cell'>" . htmlspecialchars($row['status']) . "</td>";
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
                            <label for="customerNameInput" class="form-label">Customer Name</label>
                            <input type="text" id="customerNameInput" name="customer" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="invoiceSelect" class="form-label">Invoice</label>
                            <select id="invoiceSelect" name="invoice" class="form-select" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="receiptSelect" class="form-label">Receipt</label>
                            <select id="receiptSelect" name="receipt" class="form-select" required>
                                <option value="">Loading...</option>
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
    document.addEventListener('DOMContentLoaded', () => {
        const matchButtons = document.querySelectorAll('.match-btn');
        const tranIdInput = document.getElementById('tranIdInput');
        const customerNameInput = document.getElementById('customerNameInput');
        const amountInput = document.getElementById('amountInput');
        const invoiceSelect = document.getElementById('invoiceSelect');
        const receiptSelect = document.getElementById('receiptSelect');

        matchButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tranId = button.getAttribute('data-tran-id');
                tranIdInput.value = tranId;

                // Fetch data for the modal
                fetch(`deposit.php?tran_id=${tranId}`)
                    .then(response => response.json())
                    .then(data => {
                        customerNameInput.value = data.customer_name || '';
                        amountInput.value = data.amount || '';

                        // Populate the invoice dropdown
                        invoiceSelect.innerHTML = '';
                        if (data.invoice_id) {
                            const invoiceOption = document.createElement('option');
                            invoiceOption.value = data.invoice_id;
                            invoiceOption.textContent = data.invoice_id;
                            invoiceSelect.appendChild(invoiceOption);
                        } else {
                            const invoiceOption = document.createElement('option');
                            invoiceOption.value = '';
                            invoiceOption.textContent = 'No Invoice Found';
                            invoiceSelect.appendChild(invoiceOption);
                        }

                        // Populate the receipt dropdown
                        receiptSelect.innerHTML = '';
                        if (data.receipt_id) {
                            const receiptOption = document.createElement('option');
                            receiptOption.value = data.receipt_id;
                            receiptOption.textContent = data.receipt_id;
                            receiptSelect.appendChild(receiptOption);
                        } else {
                            const receiptOption = document.createElement('option');
                            receiptOption.value = '';
                            receiptOption.textContent = 'No Receipt Found';
                            receiptSelect.appendChild(receiptOption);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching modal data:', error);
                    });
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
      $(document).ready(function() {
      // Initialize DataTable
      const table = $('#employeeTable').DataTable({
        paging: true, // Enable pagination
        searching: true, // Enable global search
        ordering: true, // Enable column-based ordering
        lengthMenu: [5, 10, 20, 50], // Rows per page options
        pageLength: 5, // Default rows per page
        language: {
          search: "Search Deposits:", // Customize the search label
        },
      });

      // Global Search
      $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
      });
      
    });
</script>
</body>
</html>

