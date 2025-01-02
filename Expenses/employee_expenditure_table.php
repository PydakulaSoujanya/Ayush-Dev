<?php
include('../config.php'); 
$query = "CALL GetExpense()";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Expense Claims</title>
  
</head>
<body>
<?php
include('../navbar.php');?>

<div class="container mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Expense Claims</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex align-items-center">
          <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
          <a href="expenses_claim_form.php" class="btn btn-primary ms-auto">Add Employee Claims</a>
        </div>
        <!-- Table -->
        <div class="table-responsive">
        <table class="table table-bordered">
    <thead class="thead-dark">
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
    <tbody id="tableBody">
        <?php
        if (mysqli_num_rows($result) > 0) {
            $sno=0;
            while ($row = mysqli_fetch_assoc($result)) {
                $sno++;
                // Prepare the Transaction Details column
                $transactionDetails = '';
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
        } else {
            echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

      </div>
    </div>
  </div>

</body>
</html>