<?php
// Connect to the database
include '../config.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch employees from the emp_info table
$empSql = "SELECT id, name FROM emp_info";
$empResult = $conn->query($empSql);

// Store employees in an array
$employees = [];
if ($empResult->num_rows > 0) {
    while ($empRow = $empResult->fetch_assoc()) {
        $employees[] = $empRow;
    }
}
// Pagination Variables
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;
$pageIndex = isset($_GET['pageIndex']) ? intval($_GET['pageIndex']) : 0;
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';


// Calculate the starting row
$start = $pageIndex * $pageSize;

// SQL Query for Paginated and Filtered Results
$sql = "SELECT * FROM service_requests 
        WHERE customer_name LIKE '%$searchTerm%' 
        ORDER BY created_at DESC 
        LIMIT $start, $pageSize";
$result = $conn->query($sql);

// Get Total Records for Pagination
$countSql = "SELECT COUNT(*) as total FROM service_requests 
             WHERE customer_name LIKE '%$searchTerm%'";
$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $pageSize);

// Check if the employee is already assigned to a service request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_employee'])) {
  $serviceId = $_POST['service_id'];  // This should be the correct column name
  $empId = $_POST['emp_id'];

  // Fetch employee name based on emp_id
  $empNameSql = "SELECT name FROM emp_info WHERE id = '$empId'";
  $empNameResult = $conn->query($empNameSql);

  if ($empNameResult->num_rows > 0) {
      $empRow = $empNameResult->fetch_assoc();
      $empName = $empRow['name'];

      // Check if the employee is already assigned to a service request
      $checkSql = "SELECT * FROM service_requests WHERE assigned_employee = '$empName'";
      $checkResult = $conn->query($checkSql);

      if ($checkResult->num_rows > 0) {
          // If employee is already assigned to a different service request
          echo "<script>alert('This employee is already assigned to another service!!');
          window.location.href = 'view_services.php';
          </script>";
      } else {
          // Assign the employee name to the service request
          $assignSql = "UPDATE service_requests SET assigned_employee = '$empName' WHERE id = '$serviceId'";
          if ($conn->query($assignSql) === TRUE) {
            echo "<script>
                alert('Employee allocated successfully!');
                window.location.href = 'view_services.php';
            </script>";
        } else {
            echo "<script>
                alert('Error allocating employee: " . $conn->error . "');
                window.location.href = 'view_services.php';
            </script>";
        }
        
      }
  } else {
      echo "<script>alert('Employee not found!');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Include Font Awesome -->
  
  <title>Data Table</title>
  <style>
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
    .action-icons i {
      color: black;
      cursor: pointer;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <?php
include '../navbar.php';
?>
  <div class="container  mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header"> Capturing Services Table</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex justify-content-between">
        <form class="d-flex w-75">
    <input type="text" class="form-control" id="globalSearch" placeholder="Search..." oninput="performSearch()">
</form>

    <a href="services.php" class="btn btn-success">+ Capture Service</a>
</div>


        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr class="dataTable_headerRow">
                <th>S.no</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                 <th>Start Date</th>
                <th>End Date</th>
                <th>Enquiry Source</th>
                <th>Action</th>
                <th>Assign Employee</th>
              </tr>
            </thead>
            <tbody>
            <?php
if ($result->num_rows > 0) {
    $serial = $start + 1;
    while ($row = $result->fetch_assoc()) {
        $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';

        echo "<tr class='dataTable_row'>
                <td>{$serial}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['contact_no']}</td>
                <td>{$row['email']}</td>
                 <td>" . date('d-m-Y', strtotime($row['from_date'])) . "</td>
        <td>" . date('d-m-Y', strtotime($row['end_date'])) . "</td>
                <td>{$row['enquiry_source']}</td>
                <td class='action-icons'>
                    <i class='fas fa-eye' style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#viewModal' onclick='viewDetails(".json_encode($row).")'></i>
                    <a href='#' data-bs-toggle='modal' data-bs-target='updateModal' onclick='populateUpdateForm(".json_encode($row).")'>
    <i class='fas fa-edit'></i>
</a>

                    <a href='delete_service.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fas fa-trash'></i></a>
                </td>
                <td>";

        // Check if the employee is assigned
        if (!empty($row['assigned_employee'])) {
    // Show the assigned employee's name
    echo "<span class='text-success'>" . htmlspecialchars($row['assigned_employee']) . "</span>";
} else {
    // Query to fetch unassigned employees for the given service duration
    $query = "
        SELECT e.id, e.name 
FROM emp_info e 
WHERE e.id NOT IN (
    SELECT a.employee_id 
    FROM allotment a
    WHERE 
        a.service_type = e.role AND
        (
            (a.start_date <= ? AND a.end_date >= ?) OR
            (a.start_date <= ? AND a.end_date >= ?) OR
            (a.start_date >= ? AND a.end_date <= ?)
        )
);"

        ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssss",
        $row['from_date'], $row['from_date'],
        $row['end_date'], $row['end_date'],
        $row['from_date'], $row['end_date']
    );
    $stmt->execute();
    $result = $stmt->get_result();

    // Dropdown for assigning an employee
    echo "<form method='POST' action='assign_employee.php'>
            <select name='emp_id' required>
                <option value=''>Select Employee</option>";
    
    // Populate the dropdown with unassigned employees
    while ($employee = $result->fetch_assoc()) {
        echo "<option value='" . $employee['id'] . "'>" . htmlspecialchars($employee['name']) . "</option>";
    }

    echo "    </select>
            <input type='hidden' name='service_id' value='{$row['id']}'>
            <button type='submit' name='assign_employee' style='border: black; cursor: pointer;' title='Allocate'>
                Assign Employee
            </button>
          </form>";
}

//<i class='fas fa-save text-primary'>Assign</i>
        echo "    </td>
              </tr>";
        $serial++;
    }
} else {
    echo "<tr><td colspan='7'>No data available</td></tr>";
}
?>
</tbody>
          </table>
        </div>
          <!-- Modal -->
   <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Service Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modalContent">
            <!-- Details will be populated dynamically -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
 <!-- Modal for Viewing Details -->
 <!-- <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Service Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <table class="table">
          <tbody>
         
          <p><strong>Customer Name:</strong> <span id="customer_name"></span></p>
          <p><strong>Contact No:</strong> <span id="contact_no"></span></p>
          <p><strong>Email:</strong> <span id="email"></span></p>
          <p><strong>Enquiry Date:</strong> <span id="enquiry_date"></span></p>
          <p><strong>Enquiry Time:</strong> <span id="enquiry_time"></span></p>
          <p><strong>Service Type:</strong> <span id="service_type"></span></p>
          <p><strong>Enquiry Source:</strong> <span id="enquiry_source"></span></p>
          <p><strong>Priority Level:</strong> <span id="priority_level"></span></p>
          <p><strong>Status:</strong> <span id="status"></span></p>
          <p><strong>Request Details:</strong> <span id="request_details"></span></p>
          <p><strong>Resolution Notes:</strong> <span id="resolution_notes"></span></p>
          <p><strong>Comments:</strong> <span id="comments"></span></p>
          <p><strong>Created At:</strong> <span id="created_at"></span></p>
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> -->

        <!-- Pagination Controls -->
         <!-- Pagination -->
         <div class="d-flex justify-content-between align-items-center">
          <div>
            Showing <?= $start + 1 ?> to <?= min($start + $pageSize, $totalRecords) ?> of <?= $totalRecords ?> records
          </div>
          <div>
            <a href="?pageIndex=<?= max(0, $pageIndex - 1) ?>&pageSize=<?= $pageSize ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="btn btn-primary btn-sm <?= $pageIndex == 0 ? 'disabled' : '' ?>">Previous</a>
            <a href="?pageIndex=<?= min($totalPages - 1, $pageIndex + 1) ?>&pageSize=<?= $pageSize ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="btn btn-primary btn-sm <?= $pageIndex >= $totalPages - 1 ? 'disabled' : '' ?>">Next</a>
          </div>
          <div>
            <select onchange="window.location.href='?pageIndex=0&pageSize=' + this.value + '&search=<?= htmlspecialchars($searchTerm) ?>'" class="form-select form-select-sm">
              <option value="5" <?= $pageSize == 5 ? 'selected' : '' ?>>5</option>
              <option value="10" <?= $pageSize == 10 ? 'selected' : '' ?>>10</option>
              <option value="20" <?= $pageSize == 20 ? 'selected' : '' ?>>20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateInvoiceForm" action="update_invoice.php" method="POST" onsubmit="handleFormSubmit(event)">
                <div class="modal-body">
                    <input type="hidden" name="id" id="invoiceId" />
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobileNumber" name="mobile_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customerEmail" name="customer_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="totalAmount" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" id="totalAmount" name="total_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  function populateUpdateForm(data) {
    document.getElementById('invoiceId').value = data.id;
    document.getElementById('customerName').value = data.customer_name;
    document.getElementById('mobileNumber').value = data.mobile_number;
    document.getElementById('customerEmail').value = data.customer_email;
    document.getElementById('totalAmount').value = data.total_amount;
    document.getElementById('dueDate').value = data.due_date;
    document.getElementById('status').value = data.status;
}
function handleFormSubmit(event) {
    event.preventDefault();
    const form = document.getElementById('updateInvoiceForm');
    const formData = new FormData(form);

    fetch('update_invoice.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Invoice updated successfully!');
                window.location.href = 'view_invoice.php';
            } else {
                alert('Error updating invoice: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
}

  
     function viewDetails(data) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = `
        <table class="table table-bordered">
          <tr><th>Customer Name</th><td>${data.customer_name}</td></tr>
          <tr><th>Contact Number</th><td>${data.contact_no}</td></tr>
          <tr><th>Email</th><td>${data.email}</td></tr>
          <tr><th>Enquiry Date</th><td>${data.enquiry_date}</td></tr>
          <tr><th>Enquiry Time</th><td>${data.enquiry_time}</td></tr>
          <tr><th>Service Type</th><td>${data.service_type}</td></tr>
          <tr><th>Enquiry Source</th><td>${data.enquiry_source}</td></tr>
          <tr><th>Priority Level</th><td>${data.priority_level}</td></tr>
           <tr><th>Status</th><td>${data.status}</td></tr>
            <tr><th>Request Details</th><td>${data.request_details}</td></tr>
             <tr><th>Resolution Notes</th><td>${data.resolution_notes}</td></tr>
              <tr><th>Comments</th><td>${data.comments}</td></tr>
          <tr><th>Created At</th><td>${data.created_at}</td></tr>
        </table>
      `;
    }
      
    // Sample Data
    const data = Array.from({ length: 50 }, (_, i) => ({
      id: i + 1,
      name: `Person ${i + 1}`,
      age: Math.floor(Math.random() * 40) + 20,
      city: `City ${Math.floor(Math.random() * 10) + 1}`,
    }));

    // Pagination Variables
    let pageIndex = 0;
    let pageSize = 5;

    // Elements
    const tableBody = document.getElementById("tableBody");
    const pageInfo = document.getElementById("pageInfo");
    const previousPage = document.getElementById("previousPage");
    const nextPage = document.getElementById("nextPage");
    const pageSizeSelect = document.getElementById("pageSize");
    const globalSearch = document.getElementById("globalSearch");

    // Functions to Render Table
    function renderTable() {
      const start = pageIndex * pageSize;
      const filteredData = data.filter((item) =>
        item.name.toLowerCase().includes(globalSearch.value.toLowerCase())
      );
      const pageData = filteredData.slice(start, start + pageSize);

      tableBody.innerHTML = pageData
        .map(
          (row) =>
            `<tr class="dataTable_row">
              <td>${row.id}</td>
              <td>${row.name}</td>
              <td>${row.age}</td>
              <td>${row.city}</td>
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
    
  </script>
  <script>
  function performSearch() {
    const searchTerm = document.getElementById('globalSearch').value;

    // Send AJAX request
    fetch(`view_services.php?search=${encodeURIComponent(searchTerm)}`)
      .then(response => response.text())
      .then(data => {
        // Update the table with the fetched data
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const newTableBody = doc.querySelector('tbody');

        if (newTableBody) {
          document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
        }
      })
      .catch(error => console.error('Error fetching data:', error));
  }
</script>

</body>
</html>
