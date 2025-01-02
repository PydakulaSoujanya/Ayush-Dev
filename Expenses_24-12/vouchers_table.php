<?php
include('../config.php'); // Include database connection

if (isset($_GET['bill_id'])) {
    $billId = $_GET['bill_id'];

    // Fetch bill details
    $billQuery = "SELECT * FROM vendor_payments WHERE bill_id = '$billId'";
    $billResult = mysqli_query($conn, $billQuery);
    $billDetails = mysqli_fetch_assoc($billResult);

    // Fetch related vouchers
    $voucherQuery = "SELECT * FROM vouchers WHERE bill_id = '$billId' ORDER BY created_at DESC";
    $voucherResult = mysqli_query($conn, $voucherQuery);
} else {
    echo "Invalid Bill ID";
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
      <div class="card-header">Vouchers</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
   

<h3>Bill Details</h3>
    <table class="table table-bordered">
        <tr><th>Bill ID</th><td><?= $billDetails['bill_id'] ?></td></tr>
        <tr><th>Vendor Name</th><td><?= $billDetails['vendor_name'] ?></td></tr>
        <tr><th>Payment Amount</th><td><?= $billDetails['payment_amount'] ?></td></tr>
        <tr><th>Paid Amount</th><td><?= $billDetails['paid_amount'] ?></td></tr>
        <tr><th>Remaining Balance</th><td><?= $billDetails['remaining_balance'] ?></td></tr>
        <tr><th>Payment Date</th><td><?= $billDetails['payment_date'] ?></td></tr>
    </table>
    <div class="dataTable_search mb-3 d-flex align-items-center">
    <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
    
</div>
        <!-- Table -->
        <div class="table-responsive">
    <table class="table table-bordered table-responsive">
        <thead class="thead-dark">
        <tr>
                <th>Voucher Number</th>
                <th>Voucher Date</th>
                <th>Vendor Name</th>
                <th>Paid Amount</th>
                <th>Payment Date</th>
                <th>Payment Mode</th>
                <th>Created At</th>
            </tr>
            <tbody>
        <?php
        if (mysqli_num_rows($voucherResult) > 0) {
            while ($voucher = mysqli_fetch_assoc($voucherResult)) {
                echo "<tr>
                    <td>{$voucher['voucher_number']}</td>
                    <td>{$voucher['voucher_date']}</td>
                    <td>{$voucher['vendor_name']}</td>
                    <td>{$voucher['paid_amount']}</td>
                    <td>{$voucher['payment_date']}</td>
                    <td>{$voucher['payment_mode']}</td>
                    <td>{$voucher['created_at']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No vouchers found</td></tr>";
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
</body>
</html>