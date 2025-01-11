<?php
include('../config.php'); 

        $empPayoutRefrenceDetailsQuery = "SELECT 
    e.expense_id,
    e.service_id,
    e.entity_name,
    e.bank_account_no,
    e.description,
    e.amount,
    e.date_incurred,
    e.status,
    e.payment_status,
    e.additional_details,
    e.created_at,
    e.updated_at,
    ei.id AS emp_id,
    ei.bank_name,
    ei.bank_account_no,
    ei.ifsc_code,
    ei.branch,
    ei.reference,
    ei.vendor_name,
    ei.vendor_id,
    ei.vendor_contact
FROM 
    expenses e
JOIN 
    emp_info ei
ON 
    e.entity_id = ei.id
WHERE 
    e.expense_type = 'Employee Payout';";

// Execute the query
$empPayoutRefrenceDetailsResult = $conn->query($empPayoutRefrenceDetailsQuery);

// Check for errors
if (!$empPayoutRefrenceDetailsResult) {
    die("Query failed: " . $conn->error);
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
  <title>Data Table</title>
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-7">
  <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 table-title">Employee Payouts Info </h5>
      <!-- <a href="emp_advance.php"  class="add_button"><strong class="add_button_plus">+</strong> Add Employee Advance </a> -->
    </div>

    <div class="table-responsive mt-3 p-4">
      <table id="employeeTable" class="display table table-striped" style="width:100%">
        <thead>
          <tr>
            <th>S.No</th>
                <th>Entity Name</th>
                <th>Reference</th>
                <th>Vendor Name</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <!-- <th>Action</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
            if (mysqli_num_rows($empPayoutRefrenceDetailsResult) > 0) {
                $sno = 0;
                while ($row = mysqli_fetch_assoc($empPayoutRefrenceDetailsResult)) {
                    $sno++;
                    echo "<tr>
                        <td>$sno</td>
                        <td>{$row['entity_name']}</td>
                        <td>{$row['reference']}</td>
                        <td>{$row['vendor_name']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['payment_status']}</td>
                       
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
  $('#employeeTable').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    language: { search: "Search Employees:" }
  });
});

</script>

</body>
</html>
