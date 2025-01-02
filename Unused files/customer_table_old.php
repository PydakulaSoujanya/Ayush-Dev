<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
   
// Include database configuration and navbar
include 'config.php';


// Pagination variables
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;
$pageIndex = isset($_GET['pageIndex']) ? intval($_GET['pageIndex']) : 0;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Calculate the starting row for the query
$start = $pageIndex * $pageSize;

// Query to fetch data with pagination, search, and ordering
$sql = "SELECT id,  patient_name, relationship, customer_name, emergency_contact_number, email, gender,  blood_group, medical_conditions, patient_age,  mobility_status, address,  discharge_summary_sheet, created_at 
        FROM customer_master 
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
             FROM customer_master 
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
  <?php include 'navbar.php'; ?>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Customer Master Table</div>
      <div class="card-body">
        <div class="mb-3 d-flex justify-content-between">
          <form method="GET" action="" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
          <a href="customer_form.php" class="btn btn-success">+ Add Customer</a>
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
                                <a href='customer-edit.php?id={$row['id']}'><i class='fas fa-edit'></i></a>
                                <a href='delete_customer.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fas fa-trash'></i></a>
                              </td>
                            </tr>";
                      $serial++;
                  }
              } else {
                  echo "<tr><td colspan='9'>No records found</td></tr>";
              }
              ?>
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
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Customer Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalContent">
          <!-- Content populated dynamically -->
        </div>
      </div>
    </div>
  </div>

  
  <script>
    function viewDetails(data) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = `
        <table class="table table-bordered">
            <tr><th>Patient Status</th><td>${data.patient_status}</td></tr>
            <tr><th>Patient Name</th><td>${data.patient_name}</td></tr>
            <tr><th>Relationship</th><td>${data.relationship}</td></tr>
            <tr><th>Full Name</th><td>${data.customer_name}</td></tr>
            <tr><th>Phone Number</th><td>${data.emergency_contact_number}</td></tr>
            // <tr><th>Date of Joining</th><td>${data.date_of_joining}</td></tr>
            <tr><th>Blood Group</th><td>${data.blood_group}</td></tr>
            <tr><th>Medical Conditions</th><td>${data.medical_conditions}</td></tr>
            <tr><th>Email</th><td>${data.email}</td></tr>
            <tr><th>Patient Age</th><td>${data.patient_age}</td></tr>
            <tr><th>Gender</th><td>${data.gender}</td></tr>
            <tr><th>Care Requirements</th><td>${data.care_requirements}</td></tr>
            <tr><th>Created At</th><td>${data.created_at}</td></tr>
            <tr><th>Updated At</th><td>${data.updated_at}</td></tr>
            <tr><th>Mobility Status</th><td>${data.mobility_status}</td></tr>
            <tr><th>Address</th><td>${data.address}</td></tr>
            <tr><th>Aadhar</th><td><a href='uploads/${data.care_aadhar}' target='_blank'>View Aadhar</a></td></tr>
            <tr><th>Discharge</th><td><a href='uploads/${data.discharge}' target='_blank'>View Discharge</a></td></tr>
        </table>
      `;
    }

    function changePage(direction) {
      const urlParams = new URLSearchParams(window.location.search);
      let pageIndex = parseInt(urlParams.get('pageIndex') || 0);
      let pageSize = parseInt(urlParams.get('pageSize') || 5);
      pageIndex += direction;
      urlParams.set('pageIndex', pageIndex);
      window.location.search = urlParams.toString();
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>