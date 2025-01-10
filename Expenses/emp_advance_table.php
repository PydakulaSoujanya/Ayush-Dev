<?php
include('../config.php'); 
$query = "CALL GetEmployeeAdvancePayments(); ";


$result = mysqli_query($conn, $query);
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
      <h5 class="mb-0 table-title">Employee Advance Info </h5>
      <a href="emp_advance.php"  class="add_button"><strong class="add_button_plus">+</strong> Add Employee Advance </a>
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
          </tr>
        </thead>
        <tbody>
          <?php
        if (mysqli_num_rows($result) > 0) {
            $sno=0;
            while ($row = mysqli_fetch_assoc($result)) {
                $sno++;
                 $dateIncurred = date('d/m/Y', strtotime($row['date_incurred']));

                // Prepare the Transaction Details column
                $transactionDetails = '';
                echo "<tr>
               <td>$sno</td>";
              
               
               echo " <td>{$row['entity_name']}</td><td>{$row['description']}</td>
                <td>{$row['amount']}</td>
                <td>$dateIncurred</td>
                <td>Paid</td>
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
