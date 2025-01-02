<?php

include('../config.php'); // Include database connection

// Fetch expenses claim data
$query = "SELECT `id`, `employee_name`, `expense_category`, `expense_date`, `amount_claimed`, 
          `attachment`, `status`, `rejection_reason`, `submitted_date`, `approved_date`, 
          `payment_status`, `payment_date`, `transaction_id`, `payment_mode`, `card_reference_number`, `bank_name`, `description`, `created_at` 
          FROM `expenses_claim` ORDER BY `created_at` DESC";
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
      <div class="card-header">Employee Expense Claims</div>

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
            <th>ID</th>
            <th>Employee Name</th>
            <th>Expense Category</th>
            <th>Expense Date</th>
            <th>Amount Claimed</th>
            <th>Status</th>
            <th>Payment Mode</th>
            <th>Transaction Details</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Prepare the Transaction Details column
                $transactionDetails = '';

                if ($row['payment_mode'] === 'UPI') {
                    $transactionDetails = "Transaction ID: {$row['transaction_id']}";
                } elseif ($row['payment_mode'] === 'Card') {
                    $transactionDetails = "Reference No: {$row['card_reference_number']}";
                } elseif ($row['payment_mode'] === 'Bank Transfer') {
                    $transactionDetails = "Transaction ID: {$row['transaction_id']}, Bank Name: {$row['bank_name']}";
                } else {
                    $transactionDetails = "N/A";
                }

                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['employee_name']}</td>
                    <td>{$row['expense_category']}</td>
                    <td>{$row['expense_date']}</td>
                    <td>{$row['amount_claimed']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['payment_mode']}</td>
                    <td>{$transactionDetails}</td>
                    <td>{$row['created_at']}</td>
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