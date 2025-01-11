<?php
include('../config.php'); 
// $query = "CALL GetEmployeeAdvancePayments(); ";


// $result = mysqli_query($conn, $query);
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
  <title>Account Payables</title>
</head>
<body>
<?php include('../navbar.php'); ?>

<?php
// Check if the search term exists (currently unused but kept for possible future use)
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Construct the SQL query
$sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount_to_pay FROM expenses";

// Execute the query
$result = $conn->query($sql);

// Check if the query returned a result
if ($result) {
    $row = $result->fetch_assoc();
    $totalAmountToPay = $row['total_amount_to_pay'] ?? 0; // Fallback to 0 if null
} else {
    $totalAmountToPay = 0; // Fallback if the query fails
}
$result->free();
?>


<div class="container mt-7">

<h3 class="text-center">
            Total amount to pay is: Rs. <?php echo number_format($totalAmountToPay, 2); ?>
        </h3>
  <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 table-title">Account Payables </h5>
      <!-- <a href="emp_advance.php"  class="add_button"><strong class="add_button_plus">+</strong> Add Employee Advance </a> -->
    </div>

    <div class="table-responsive mt-3 p-4">
      <table id="employeeTable" class="display table table-striped" style="width:100%">
        <thead>
          <tr>
    <th>S.No</th>
    <th>Entity Name</th>
    <th>Description</th>
    <th>Amount</th>
    <th>Date Incurred</th>
    <th>Status</th>
    <th>Payment Status</th>
    <th>Created At</th>
    <th>Updated At</th>
</tr>
        </thead>
        <tbody>
          <?php
        $query = "CALL GetAllExpenses();
";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
          
            $sno = 0; // Serial number counter
$santhoshTotal = 0; // To store the total amount for "Santhosh Sir"
$santhoshRow = null; // To store other details of "Santhosh Sir"

// Loop through the result set
while ($row = mysqli_fetch_assoc($result)) {
    // Check if the bank_account is "Santhosh Sir"
    if ($row['bank_account_no'] === "Santhosh Sir") {
        // Accumulate the total amount for "Santhosh Sir"
        $santhoshTotal += $row['amount']; // Make sure 'amount' is the correct field name
        $santhoshRow = $row; // Store the row details for later use
        continue; // Skip rendering this row now, we'll render Santhosh Sir's row later
    }

    // Increment the serial number for visible rows only
    $sno++;

    // Render other rows normally
    echo "<tr>
            <td>$sno</td>
            <td>{$row['entity_name']}</td>
            <td>{$row['description']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['date_incurred']}</td>
            <td>{$row['status']}</td>
            <td>{$row['payment_status']}</td>
            <td>{$row['created_at']}</td>
            <td>{$row['updated_at']}</td>
        </tr>";
}

// After the loop, render "Santhosh Sir" row if it exists
if ($santhoshRow) {
    $sno++; // Increment the serial number for Santhosh Sir's row
    echo "<tr>
            <td>$sno</td>
            <td>{$santhoshRow['bank_account_no']}</td>
            <td>{$santhoshRow['description']}</td>
            <td>$santhoshTotal</td>
            <td>{$santhoshRow['date_incurred']}</td>
            <td>{$santhoshRow['status']}</td>
            <td>{$santhoshRow['payment_status']}</td>
            <td>{$santhoshRow['created_at']}</td>
            <td>{$santhoshRow['updated_at']}</td>
        </tr>";
}

            
        } else {
            echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
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
