<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration and navbar
include 'config.php';
include 'navbar.php';

// Pagination variables
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;
$pageIndex = isset($_GET['pageIndex']) ? intval($_GET['pageIndex']) : 0;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';



$start = $pageIndex * $pageSize;

// Query to fetch data with pagination, search, and ordering
$sql = "SELECT id, patient_name, relationship, customer_name, emergency_contact_number, email, gender, blood_group, patient_age, mobility_status, created_at
        FROM customer_master_new
        WHERE patient_name LIKE ?
        ORDER BY created_at DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$searchTermWildcard = '%' . $searchTerm . '%';
$stmt->bind_param('sii', $searchTermWildcard, $start, $pageSize);
$stmt->execute();
$result = $stmt->get_result();

// Query to get the total number of records
$countSql = "SELECT COUNT(*) as total
             FROM customer_master_new
             WHERE patient_name LIKE ?";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param('s', $searchTermWildcard);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];

// Close the statement and connection
$stmt->close();
$countStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <title>Customer Master Table</title>
  <style>
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
      color: black;
      cursor: pointer;
      margin-right: 10px;
    }
   
  </style>
</head>
<body>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Customer Master Table</div>
      <div class="card-body">
        <div class="mb-3 d-flex justify-content-between">
          <form method="GET" action="" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
          <a href="customer_form1.php" class="btn btn-success">+ Add Customer</a>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Patient Name</th>
                <th>Emergency Contact</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Blood Group</th>
                <th>Age</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                  $serial = $start + 1;
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>
                              <td>{$serial}</td>
                              <td>{$row['patient_name']}</td>
                              <td>{$row['emergency_contact_number']}</td>
                              <td>{$row['email']}</td>
                              <td>{$row['gender']}</td>
                              <td>{$row['blood_group']}</td>
                              <td>{$row['patient_age']}</td>
                              <td class='action-icons'>
                                <a href='customer_view.php?id={$row['id']}'><i class='fas fa-eye'></i></a>
                                <a href='customer_edit.php?id={$row['id']}'><i class='fas fa-edit'></i></a>
                                <a href='delete_customer.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fas fa-trash'></i></a>
                              </td>
                            </tr>";
                      $serial++;
                  }
              } else {
                  echo "<tr><td colspan='8'>No records found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <button onclick="changePage(-1)" class="btn btn-sm btn-primary me-2" <?php echo $pageIndex <= 0 ? 'disabled' : ''; ?>>Previous</button>
            <button onclick="changePage(1)" class="btn btn-sm btn-primary" <?php echo (($pageIndex + 1) * $pageSize) >= $totalRecords ? 'disabled' : ''; ?>>Next</button>
          </div>
          <div>
            Page <strong><?php echo ($pageIndex + 1); ?> of <?php echo ceil($totalRecords / $pageSize); ?></strong>
          </div>
          <div>
            <select id="pageSize" class="form-select form-select-sm" onchange="updatePageSize(this.value)">
              <option value="5" <?php echo $pageSize == 5 ? 'selected' : ''; ?>>Show 5</option>
              <option value="10" <?php echo $pageSize == 10 ? 'selected' : ''; ?>>Show 10</option>
              <option value="20" <?php echo $pageSize == 20 ? 'selected' : ''; ?>>Show 20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function changePage(direction) {
      const urlParams = new URLSearchParams(window.location.search);
      let pageIndex = parseInt(urlParams.get('pageIndex') || 0);
      pageIndex += direction;
      urlParams.set('pageIndex', pageIndex);
      window.location.search = urlParams.toString();
    }
    function updatePageSize(size) {
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set('pageSize', size);
      urlParams.set('pageIndex', 0); // Reset to the first page
      window.location.search = urlParams.toString();
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
