<?php

// Database connection
include('../config.php');

// Fetch account configurations
$query = "SELECT * FROM account_config";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Vendor Table</title>
  <!-- <style>
    .dataTable_wrapper {
      padding: 20px;
    }

    .dataTable_search input {
      max-width: 200px;
    }

    .dataTable_headerRow th,
    .dataTable_row td {
      border: 1px solid #dee2e6;
    }

    .dataTable_headerRow {
      background-color: #f8f9fa;
      font-weight: bold;
    }

    .dataTable_row:hover {
      background-color: #f1f1f1;
    }

    .dataTable_card {
      border: 1px solid #ced4da;
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dataTable_card .card-header {
      background-color: #A26D2B;
      color: white;
      font-weight: bold;
    }

    .action-icons i {
      margin: 0 5px;
      cursor: pointer;
    }

    .action-icons i:hover {
      color: #007bff;
    }
  </style> -->
</head>
<body>
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Bank Account Details</div>
      <div class="card-body">
      <div class="dataTable_search mb-3 d-flex justify-content-between align-items-center">
  <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search..." style="max-width: 200px;">
  <a href="bankconfiguration.php" class="btn btn-primary ms-auto">+Add Bank Account</a>
</div>

        <div class="table-responsive">
        <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Account Name</th>
        <th>Bank Account No</th>
        <th>IFSC Code</th>
        <th>Bank Name</th>
        <th>Account Type</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['account_name']); ?></td>
            <td><?php echo htmlspecialchars($row['bank_account_no']); ?></td>
            <td><?php echo htmlspecialchars($row['ifsc_code']); ?></td>
            <td><?php echo htmlspecialchars($row['bank_name']); ?></td>
            <td><?php echo htmlspecialchars($row['account_type']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
            <a href="edit_accountconfig.php?id=<?= $row['id']; ?>" class="btn btn-sm" style="color: black;" title="Edit">
    <i class="fas fa-edit"></i>
</a>
<a href="delete_accountconfig.php?id=<?= $row['id']; ?>" class="btn btn-sm" style="color: black;" title="Delete" onclick="return confirm('Are you sure you want to delete this record?');">
    <i class="fas fa-trash-alt"></i>
</a>

            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center">No records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

        </div>
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

 
  
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
