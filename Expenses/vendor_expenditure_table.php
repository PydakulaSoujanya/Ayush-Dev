<?php
include('../config.php'); // Include database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch vendor payment data without pagination
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
    <title>Vendor Payment Records</title>
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-7">
    <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 table-title">Vendor Payment Records</h5>
    <a href="vendor_billing_and_payouts.php" class="add_button"><strong class="add_button_plus">+</strong>Add Expense</a>
</div>


            <!-- Table -->
            <div class="table-responsive mt-3 p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark mt-4">
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
                        $serialNumber = 1; // Start from 1 since there's no pagination
                        while ($row = mysqli_fetch_assoc($result)) {
                            $formattedDate = date("d-m-Y", strtotime($row['created_at']));
                            echo "<tr>
                                    <td>{$serialNumber}</td>
                                    <td>{$row['purchase_invoice_number']}</td>
                                    <td>{$formattedDate}</td>
                                    <td>{$row['bill_id']}</td>
                                    <td>{$row['vendor_name']}</td>
                                    <td>{$row['invoice_amount']}</td>
                                    <td>{$row['total_paid_amount']}</td>
                                    <td>{$row['remaining_balance']}</td>
                                    <td>{$row['payment_status']}</td>
                                    <td>
                                        <a href='view_vouchers.php?purchase_invoice_number={$row['purchase_invoice_number']}'
                                           class='btn btn-sm btn-success create-voucher-btn d-flex align-items-center justify-content-center'>
                                            <i class='fas fa-file-invoice'></i>
                                        </a>
                                    </td>
                                </tr>";
                            $serialNumber++;
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
          search: "Search vendor Expenses:", // Customize the search label
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
