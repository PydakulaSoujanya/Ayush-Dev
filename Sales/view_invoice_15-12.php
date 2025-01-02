<?php


error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the screen
ini_set('display_startup_errors', 1); // Display errors during PHP's startup sequence

include '../config.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
$sql = "SELECT * FROM invoice WHERE customer_name LIKE '%$searchTerm%' ORDER BY created_at DESC LIMIT $start, $pageSize";
$result = $conn->query($sql);

// Get total number of records for pagination
$countSql = "SELECT COUNT(*) as total FROM service_requests WHERE customer_name LIKE '%$searchTerm%'";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Include Font Awesome -->
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
  <div class="container  mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Manage Invoice</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex justify-content-between">
    <form method="GET" action="" class="d-flex w-75">
        <input type="text" class="form-control" id="globalSearch" placeholder="Search...">
    </form>
    <a href="invoicegeneration.php" class="btn btn-success">+ Add invoice</a>
</div>


        <!-- Table -->
        <div class="table-responsive">
         <table class="table table-striped">
    <thead>
        <tr class="dataTable_headerRow">
            <th>S.no</th>
            <th>Invoice ID</th>
            <th>Customer Name</th>
            <th>Mobile Number</th>
            <th>Total Amount</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $serial = $start + 1; // Assuming `$start` is defined elsewhere
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='dataTable_row'>
                        <td>{$serial}</td>
                        <td>{$row['invoice_id']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['mobile_number']}</td>
                        
                        <td>{$row['total_amount']}</td>
                        <td>{$row['due_date']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['created_at']}</td>
                        
                        <td class='action-icons'>
                            <i class='btn btn-sm fas fa-eye' style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#viewModal' onclick='viewDetails(".json_encode($row).")'></i>
                            <a href='#' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='populateUpdateForm(".json_encode($row).")'>
    <i class='btn btn-sm fas fa-edit'></i>
</a>

                            <a href='delete_invoice.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='btn btn-sm fas fa-trash'></i></a>
                        </td>
                    </tr>";
                $serial++;
            }
        } else {
            echo "<tr><td colspan='12'>No data available</td></tr>";
        }
        ?>
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
              <option value="5">Show 5</option>
              <option value="10">Show 10</option>
              <option value="20">Show 20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

   <!-- Modal -->
   <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Invoice Details</h5>
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

      function viewDetails(data) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <table class="table table-bordered">
            <tr><th>Invoice ID</th><td>${data.invoice_id}</td></tr>
            <tr><th>Customer Name</th><td>${data.customer_name}</td></tr>
            <tr><th>Mobile Number</th><td>${data.mobile_number}</td></tr>
            <tr><th>Email</th><td>${data.customer_email}</td></tr>
            <tr><th>Service ID</th><td>${data.service_id}</td></tr>
            <tr><th>Total Amount</th><td>${data.total_amount}</td></tr>
            <tr><th>Due Date</th><td>${data.due_date}</td></tr>
            <tr><th>Status</th><td>${data.status}</td></tr>
            <tr><th>Created At</th><td>${data.created_at}</td></tr>
            <tr><th>Updated At</th><td>${data.updated_at}</td></tr>
        </table>
    `;
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
  
</body>
</html>
