
<?php
include('../config.php'); // Include database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch vendor payment data
$query = "
SELECT 
    vp.purchase_invoice_number,
    vp.bill_id,
    vp.vendor_name,
    vp.invoice_amount,
    vp.created_at,
    COALESCE(SUM(v.paid_amount), 0) AS total_paid_amount,
    vp.invoice_amount - COALESCE(SUM(v.paid_amount), 0) AS remaining_balance,
    CASE 
        WHEN vp.invoice_amount - COALESCE(SUM(v.paid_amount), 0) = 0 THEN 'Paid'
        WHEN COALESCE(SUM(v.paid_amount), 0) = 0 THEN 'Pending'
        ELSE 'Partially Paid'
    END AS payment_status
FROM 
    vendor_payments_new vp
LEFT JOIN 
    vouchers_new v 
ON 
    vp.purchase_invoice_number = v.purchase_invoice_number
GROUP BY 
    vp.purchase_invoice_number, vp.bill_id, vp.vendor_name, vp.invoice_amount, vp.created_at
ORDER BY 
    vp.created_at DESC";


$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Fetch the latest voucher number from the vouchers_new table
$voucherQuery = "SELECT voucher_number FROM vouchers_new ORDER BY id DESC LIMIT 1";
$voucherResult = mysqli_query($conn, $voucherQuery);

if ($voucherResult && mysqli_num_rows($voucherResult) > 0) {
    $voucherRow = mysqli_fetch_assoc($voucherResult);
    $lastVoucherNumber = $voucherRow['voucher_number']; // Example: "VOU01"

    // Extract numeric part and increment
    $number = intval(substr($lastVoucherNumber, 3)); // Remove "VOU"
    $nextVoucherNumber = 'VOU' . str_pad($number + 1, 2, '0', STR_PAD_LEFT); // Increment and format
} else {
    $nextVoucherNumber = 'VOU01'; // Default if no vouchers exist
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
        <table class="table table-bordered table-responsive table-striped">
              <thead class="thead-dark">
                  <tr>
                      <th>S.No</th>
                      <th>Invoice No</th>
                      <th>Invoice Date</th>
                      <th>Bill ID</th>
                      <th>Vendor Name</th>
                      <th>Invoice Amount</th>
                      <th>Paid Amount</th>
                      <th>Remaining Balance</th>
                      <th>Payment Status</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              <?php
if (mysqli_num_rows($result) > 0) {
    $serialNumber = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $formattedDate = date("d-m-Y", strtotime($row['created_at']));
        $paidAmount = $row['total_paid_amount']; // From vouchers_new
        $remainingBalance = $row['remaining_balance']; // From vouchers_new
        $paymentStatus = $row['payment_status']; // Dynamically determined

        echo "<tr>";
        echo "<td>{$serialNumber}</td>";
        echo "<td>{$row['purchase_invoice_number']}</td>";
        echo "<td>{$formattedDate}</td>";
        echo "<td>{$row['bill_id']}</td>";
        echo "<td>{$row['vendor_name']}</td>";
        echo "<td>{$row['invoice_amount']}</td>";
        echo "<td>{$paidAmount}</td>";
        echo "<td>{$remainingBalance}</td>";
        echo "<td>{$paymentStatus}</td>";
        echo "<td>
                <form class='voucher-form'>
                    <input type='hidden' name='purchase_invoice_number' value='{$row['purchase_invoice_number']}'>
                    <button type='button' 
                            class='btn btn-success btn-sm open-voucher-page'
                            data-invoice-number='{$row['purchase_invoice_number']}'>
                        <i class='fas fa-file-invoice'></i> Create Voucher
                    </button>
                </form>
              </td>";
        echo "</tr>";

        $serialNumber++;
    }
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
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".open-voucher-page").forEach(button => {
        button.addEventListener("click", function () {
            const purchaseInvoiceNumber = this.getAttribute("data-invoice-number");
            // Redirect to the vouchers page with the invoice number as a parameter
            window.location.href = `view_vouchers.php?purchase_invoice_number=${purchaseInvoiceNumber}`;
        });
    });
});
</script>


</body>
</html>
