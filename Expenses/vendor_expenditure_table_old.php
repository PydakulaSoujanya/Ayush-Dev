

<?php

include('../config.php'); // Include database connection

// Fetch vendor payment data
$query = "SELECT * FROM vendor_payments ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
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
      <!-- Card Header -->
      <div class="card-header">Vendor Payment Records</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex align-items-center">
    <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
    <a href="vendor_billing_and_payouts.php" class="btn btn-primary ms-auto">Add Vendor Expenditure</a>
</div>



        <!-- Table -->
        <div class="table-responsive">
    <table class="table table-bordered table-responsive">
        <thead class="thead-dark">
        <tr>
        <th>S.No</th>
        <th>Bill ID</th>
        <th>Vendor Name</th>
        <th>Payment Amount</th>
        <th>Paid Amount</th>
        <th>Remaining Balance</th>
        <th>Payment Status</th>
        <th>Payment Date</th>
        <th>Payment Mode & Details</th> <!-- Combined column -->
        <th>Created At</th>
        <th>Action</th>
    </tr>
        </thead>
        <tbody>
<?php
if (mysqli_num_rows($result) > 0) {
    $serialNumber = 1; // Initialize serial number

    // Keep track of the latest bill IDs we have encountered
    $latestBillIds = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $transactionDetails = '';

        // Combine transaction details based on payment mode
        if ($row['payment_mode'] === 'UPI') {
            $transactionDetails = "Transaction ID: {$row['transaction_id']}";
        } elseif ($row['payment_mode'] === 'Card') {
            $transactionDetails = "Reference No: {$row['card_reference_number']}";
        } elseif ($row['payment_mode'] === 'Bank Transfer') {
            $transactionDetails = "Transaction ID: {$row['transaction_id']}<br>Bank Name: {$row['bank_name']}";
        } else {
            $transactionDetails = "N/A";
        }

        echo "<tr>
            <td>{$serialNumber}</td>
<td><a href='vouchers_table.php?bill_id={$row['bill_id']}' class='text-decoration-none'>{$row['bill_id']}</a></td>

            <td>{$row['vendor_name']}</td>
            <td>{$row['payment_amount']}</td>
            <td>{$row['paid_amount']}</td>
            <td>{$row['remaining_balance']}</td>
            <td>{$row['payment_status']}</td>
            <td>{$row['payment_date']}</td>
            <td>{$transactionDetails}</td>
            <td>{$row['created_at']}</td>
            <td>
                <div class='btn-group' role='group' aria-label='Action Buttons'>";
        
        // Conditionally render action buttons
        if (!in_array($row['bill_id'], $latestBillIds)) {
            if ($row['payment_status'] === 'Paid') {
                $latestBillIds[] = $row['bill_id'];
            } elseif ($row['payment_status'] === 'Partially Paid' && $row['remaining_balance'] > 0) {
                echo "<button class='btn btn-primary btn-sm pay-btn' data-payment-id='{$row['id']}' title='Pay Remaining'>
                        <i class='fas fa-coins'></i> Pay
                      </button>";
                $latestBillIds[] = $row['bill_id'];
            }
            
            // Pass specific transaction details in data attribute
            echo "<button class='btn btn-success btn-sm create-voucher-btn' 
                     data-bill-id='{$row['bill_id']}'
                     data-vendor-name='{$row['vendor_name']}'
                     data-paid-amount='{$row['paid_amount']}'
                     data-payment-date='{$row['payment_date']}'
                     data-transaction-details='" . htmlspecialchars($transactionDetails) . "'
                     title='Create Voucher'>
                     <i class='fas fa-file-invoice'></i>+Voucher
                  </button>";
        }

        echo "</div>
            </td>
        </tr>";

        $serialNumber++; // Increment serial number
    }
} else {
    echo "<tr><td colspan='12' class='text-center'>No records found</td></tr>";
}
?>
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
<!-- Modal Structure -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pay Remaining Amount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm">
            <div class="modal-body">
    <div class="row">
        <!-- Bill ID -->
        <div class="col-md-6 mb-3">
            <label for="bill_id" class="form-label">Bill ID</label>
            <input type="text" class="form-control" id="bill_id" name="bill_id" readonly>
        </div>

        <!-- Vendor Name -->
        <div class="col-md-6 mb-3">
            <label for="vendor_name" class="form-label">Vendor Name</label>
            <input type="text" class="form-control" id="vendor_name" name="vendor_name" readonly>
        </div>
    </div>

    <div class="row">
        <!-- Paid Amount -->
        <div class="col-md-6 mb-3">
            <label for="paid_amount" class="form-label">Paid Amount</label>
            <input type="text" class="form-control" id="paid_amount" name="paid_amount" readonly>
        </div>

        <!-- Remaining Balance -->
        <div class="col-md-6 mb-3">
            <label for="remaining_balance" class="form-label">Remaining Balance</label>
            <input type="text" class="form-control" id="remaining_balance" name="remaining_balance" readonly>
        </div>
    </div>

    <div class="row">
        <!-- Amount to Pay -->
        <div class="col-md-6 mb-3">
            <label for="amount_to_pay" class="form-label">Amount to Pay</label>
            <input type="number" class="form-control" id="amount_to_pay" name="amount_to_pay" required>
        </div>

        <!-- Payment Mode -->
        <div class="col-md-6 mb-3">
            <label for="payment_mode" class="form-label">Payment Mode</label>
            <select class="form-select" id="payment_mode" name="payment_mode" required>
            <option value="" disabled selected>Select Payment</option>
            <option value="UPI">UPI</option>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>
    </div>

    <!-- Transaction ID (for Bank Transfer) -->
<div class="mb-3" id="transaction_id_container" style="display: none;">
    <label for="transaction_id" class="form-label">Transaction ID</label>
    <input type="text" class="form-control" id="transaction_id" name="transaction_id" placeholder="Enter Transaction ID">
</div>

<!-- Reference Number (for Card) -->
<div class="mb-3" id="card_reference_container" style="display: none;">
    <label for="card_reference_number" class="form-label">Reference Number</label>
    <input type="text" class="form-control" id="card_reference_number" name="card_reference_number" placeholder="Enter Reference Number">
</div>

<!-- Bank Name (for Bank Transfer) -->
<div class="mb-3" id="bank_name_container" style="display: none;">
    <label for="bank_name" class="form-label">Bank Name</label>
    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name">
</div>



    <div class="row">
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
      </div>
    </div>
</div>

            </form>
        </div>
    </div>
</div>
<!-- Voucher Modal -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voucherModalLabel">Create Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="voucherForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voucher_number" class="form-label">Voucher Number</label>
                            <input type="text" class="form-control" id="voucher_number" name="voucher_number" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="voucher_date" class="form-label">Voucher Date</label>
                            <input type="date" class="form-control" id="voucher_date" name="voucher_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voucher_bill_id" class="form-label">Bill ID</label>
                            <input type="text" class="form-control" id="voucher_bill_id" name="bill_id" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="voucher_vendor_name" class="form-label">Vendor Name</label>
                            <input type="text" class="form-control" id="voucher_vendor_name" name="vendor_name" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voucher_paid_amount" class="form-label">Paid Amount</label>
                            <input type="text" class="form-control" id="voucher_paid_amount" name="paid_amount" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="voucher_payment_date" class="form-label">Payment Date</label>
                            <input type="text" class="form-control" id="voucher_payment_date" name="payment_date" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6 mb-3">
                            <label for="voucher_payment_mode" class="form-label">Payment Mode</label>
                            <input type="text" class="form-control" id="voucher_payment_mode" name="payment_mode" readonly>
                        </div> -->
                        <!-- Transaction Details (Dynamic Section) -->
                        <div id="transactionDetailsContainer" class="mb-3">
    <label for="transaction_details" class="form-label">Transaction Details</label>
    <div id="transaction_details" class="form-control" readonly></div>
</div>

                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Voucher</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const tableRows = Array.from(document.querySelectorAll("tbody tr"));
    const searchInput = document.getElementById("globalSearch");
    const pageInfo = document.getElementById("pageInfo");
    const previousPage = document.getElementById("previousPage");
    const nextPage = document.getElementById("nextPage");
    const pageSizeSelect = document.getElementById("pageSize");

    let pageIndex = 0;
    let pageSize = 10; // Default to 10 rows per page
    let filteredRows = tableRows; // Initially, all rows are visible

    // Render the current page
    function renderTable() {
        // Hide all rows
        tableRows.forEach(row => row.style.display = "none");

        // Show rows for the current page
        const start = pageIndex * pageSize;
        const end = start + pageSize;
        filteredRows.slice(start, end).forEach(row => row.style.display = "");

        // Update page info
        const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
        pageInfo.textContent = `Page ${pageIndex + 1} of ${totalPages}`;

        // Enable/Disable pagination buttons
        previousPage.disabled = pageIndex === 0;
        nextPage.disabled = pageIndex >= totalPages - 1;
    }

    // Filter rows based on search input
    function filterRows() {
        const query = searchInput.value.toLowerCase().trim();
        filteredRows = tableRows.filter(row => {
            const rowData = Array.from(row.cells)
                .map(cell => cell.textContent.toLowerCase())
                .join(" ");
            return rowData.includes(query);
        });
        pageIndex = 0; // Reset to the first page after filtering
        renderTable();
    }

    // Event Listeners
    searchInput.addEventListener("input", filterRows);
    pageSizeSelect.addEventListener("change", (e) => {
        pageSize = Number(e.target.value);
        pageIndex = 0; // Reset to the first page
        renderTable();
    });
    previousPage.addEventListener("click", () => {
        if (pageIndex > 0) {
            pageIndex--;
            renderTable();
        }
    });
    nextPage.addEventListener("click", () => {
        if (pageIndex < Math.ceil(filteredRows.length / pageSize) - 1) {
            pageIndex++;
            renderTable();
        }
    });

    // Initial Render
    renderTable();
});

  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const payButtons = document.querySelectorAll(".pay-btn");
        const paymentModal = new bootstrap.Modal(document.getElementById("paymentModal"));

        payButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const paymentId = this.dataset.paymentId; // Fetch the specific payment ID

                // Fetch Payment Details for the specific payment ID
                fetch(`fetch_bill_details.php?payment_id=${paymentId}`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Populate modal with fetched data
                            document.getElementById("bill_id").value = data.bill_id;
                            document.getElementById("vendor_name").value = data.vendor_name;
                            document.getElementById("paid_amount").value = data.paid_amount;
                            document.getElementById("remaining_balance").value = data.remaining_balance;
                            document.getElementById("amount_to_pay").value = ""; // Clear previous input
                        }
                        paymentModal.show(); // Show the modal
                    })
                    .catch((error) => console.error("Error fetching payment details:", error));
            });
        });

        // Display fields conditionally based on Payment Mode
        const paymentMode = document.getElementById("payment_mode");
        const transactionIdContainer = document.getElementById("transaction_id_container");
        const cardReferenceContainer = document.getElementById("card_reference_container");
        const bankNameContainer = document.getElementById("bank_name_container");

        // Initially hide all conditional fields
        transactionIdContainer.style.display = "none";
        cardReferenceContainer.style.display = "none";
        bankNameContainer.style.display = "none";

        paymentMode.addEventListener("change", function () {
            const selectedMode = this.value;

            // Hide all fields initially
            transactionIdContainer.style.display = "none";
            cardReferenceContainer.style.display = "none";
            bankNameContainer.style.display = "none";

            // Show relevant fields based on the selected payment mode
            if (selectedMode === "Card") {
                cardReferenceContainer.style.display = "block";
            } else if (selectedMode === "Bank Transfer") {
                transactionIdContainer.style.display = "block";
                bankNameContainer.style.display = "block";
            } else if (selectedMode === "UPI") {
                transactionIdContainer.style.display = "block";
            }
        });

        // Handle form submission
        document.getElementById("paymentForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("update_payment.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Payment recorded successfully!");
                        window.location.reload();
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    });
</script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const voucherModal = new bootstrap.Modal(document.getElementById("voucherModal"));
    const createVoucherButtons = document.querySelectorAll(".create-voucher-btn");

    createVoucherButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const voucherNumberField = document.getElementById("voucher_number");
            const voucherDateField = document.getElementById("voucher_date");

            // Fetch the next voucher number via AJAX
            fetch("get_next_voucher_number.php")
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        voucherNumberField.value = data.voucher_number;
                    } else {
                        alert("Error generating voucher number: " + data.error);
                    }
                });

            // Set default voucher date to today
            voucherDateField.value = new Date().toISOString().split("T")[0];

            // Populate other fields from the button's data attributes
            document.getElementById("voucher_bill_id").value = this.dataset.billId;
            document.getElementById("voucher_vendor_name").value = this.dataset.vendorName;
            document.getElementById("voucher_paid_amount").value = this.dataset.paidAmount;
            document.getElementById("voucher_payment_date").value = this.dataset.paymentDate;

            // Populate transaction details (exclude payment mode)
            const transactionDetailsField = document.getElementById("transaction_details");
            transactionDetailsField.innerHTML = this.dataset.transactionDetails;

            // Show the modal
            voucherModal.show();
        });
    });

    // Handle form submission
    document.getElementById("voucherForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission behavior

    const formData = new FormData(this);

    fetch("save_voucher.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Display success message
                alert("Voucher created successfully!");
                window.location.reload(); // Reload the page to reflect changes
            } else {
                // Display error message
                alert("Error: " + (data.error || "Unknown error occurred"));
            }
        })
        .catch((error) => {
            // Display network or fetch-related error
            alert("An error occurred while creating the voucher: " + error.message);
        });
});
});
</script>

</body>
</html>
