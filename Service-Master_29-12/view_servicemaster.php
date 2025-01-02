<?php
// Connect to the database
include '../config.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages if any
if (isset($_GET['msg']) && !empty($_GET['msg'])) {
    // Sanitize and store the message
    $message = htmlspecialchars($_GET['msg']);
    // Output the JavaScript alert to display the success message
    echo "<script>alert('$message');</script>";
}

// Pagination Variables
$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 0;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch data with pagination, search, and ordering by the created_at column
$start = $pageIndex * $pageSize;
$sql = "SELECT `id`, `service_name`, `status`, `daily_rate_8_hours`, `daily_rate_12_hours`, `daily_rate_24_hours`, `description`, `created_at` FROM `service_master` WHERE `service_name` LIKE '%$searchTerm%' ORDER BY `created_at` DESC LIMIT $start, $pageSize";
$result = $conn->query($sql);

// Get total number of records for pagination
$countSql = "SELECT COUNT(*) as total FROM `service_master` WHERE `service_name` LIKE '%$searchTerm%'";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];

// Fetching all the results as an array
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Service Master Table</title>
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
      background-color:  #A26D2B;
      color: white;
      font-weight: bold;
    }
    .action-icons i {
      color: black;
      cursor: pointer;
      margin-right: 10px;
    }
  </style> -->
</head>
<body>
  <?php
   include '../navbar.php';
  ?>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Manage Service Master</div>
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex justify-content-between">
          <form method="GET" action="" class="d-flex w-75">
            <input type="text" class="form-control" id="globalSearch" placeholder="Search..." value="<?php echo $searchTerm; ?>">
          </form>
          <a href="servicemaster.php" class="btn btn-success">+ Add Service Master</a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr class="dataTable_headerRow">
                <th>S.no</th>
                <th>Service Name</th>
                <th>Status</th>
                <th>8-Hours Rate</th>
                <th>12-Hours Rate</th>
                <th>24-Hours Rate</th>
                <th>Description</th>
               
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              <!-- Data will be injected dynamically here -->
            </tbody>
          </table>
        </div>

        <!-- Pagination Controls -->
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
              <option value="5" <?php echo ($pageSize == 5) ? 'selected' : ''; ?>>Show 5</option>
              <option value="10" <?php echo ($pageSize == 10) ? 'selected' : ''; ?>>Show 10</option>
              <option value="20" <?php echo ($pageSize == 20) ? 'selected' : ''; ?>>Show 20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const data = <?php echo json_encode($data); ?>; // Pass PHP data to JavaScript

    let pageIndex = <?php echo $pageIndex; ?>;
    let pageSize = <?php echo $pageSize; ?>;

    // Elements
    const tableBody = document.getElementById("tableBody");
    const pageInfo = document.getElementById("pageInfo");
    const previousPage = document.getElementById("previousPage");
    const nextPage = document.getElementById("nextPage");
    const pageSizeSelect = document.getElementById("pageSize");
    const globalSearch = document.getElementById("globalSearch");

    // Function to Render Table
    function renderTable() {
      const start = pageIndex * pageSize;
      const filteredData = data.filter((item) =>
        item.service_name.toLowerCase().includes(globalSearch.value.toLowerCase())
      );
      const pageData = filteredData.slice(start, start + pageSize);

      tableBody.innerHTML = pageData
        .map(
          (row, index) =>
            `<tr class="dataTable_row">
              <td>${start + index + 1}</td>
              <td>${row.service_name}</td>
              <td>${row.status}</td>
              <td>${row.daily_rate_8_hours}</td>
              <td>${row.daily_rate_12_hours}</td>
              <td>${row.daily_rate_24_hours}</td>
              <td>${row.description}</td>
              
              <td class="action-icons">
               
                <a href="update_service_master.php?id=${row.id}"><i class="btn btn-sm fas fa-edit"></i></a>
                <a href="delete_servicemaster.php?id=${row.id}" onclick="return confirm('Are you sure you want to delete?')"><i class="btn btn-sm fas fa-trash"></i></a>
              </td>
            </tr>`
        )
        .join("");

      pageInfo.textContent = `${pageIndex + 1} of ${Math.ceil(filteredData.length / pageSize)}`;
      previousPage.disabled = pageIndex === 0;
      nextPage.disabled = pageIndex >= Math.ceil(filteredData.length / pageSize) - 1;
    }

    // Event Listeners
    previousPage.addEventListener("click", () => {
      if (pageIndex > 0) {
        pageIndex--;
        renderTable();
      }
    });

    nextPage.addEventListener("click", () => {
      pageIndex++;
      renderTable();
    });

    pageSizeSelect.addEventListener("change", (e) => {
      pageSize = Number(e.target.value);
      pageIndex = 0;
      renderTable();
    });

    globalSearch.addEventListener("input", () => {
      pageIndex = 0;
      renderTable();
    });

    // Initial Render
    renderTable();

    // Function to View Details
    function viewDetails(data) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = `
        <table class="table table-bordered">
          <tr><th>Service Name</th><td>${data.service_name}</td></tr>
          <tr><th>Status</th><td>${data.status}</td></tr>
          <tr><th>8-Hours Rate</th><td>${data.daily_rate_8_hours}</td></tr>
          <tr><th>12-Hours Rate</th><td>${data.daily_rate_12_hours}</td></tr>
          <tr><th>24-Hours Rate</th><td>${data.daily_rate_24_hours}</td></tr>
          <tr><th>Description</th><td>${data.description}</td></tr>
          <tr><th>Created At</th><td>${data.created_at}</td></tr>
        </table>
      `;
    }
  </script>
</body>
</html>
