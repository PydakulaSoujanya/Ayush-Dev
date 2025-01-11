<?php
include('../config.php'); 

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Employee Payouts</title>
  
</head>
<body>
<?php
include('../navbar.php');?>

<div class="container mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Employee Payouts</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex align-items-center">
          <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
          
        </div>
        <?php
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
        <div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
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
        <tbody id="tableBody">
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

</body>
</html>