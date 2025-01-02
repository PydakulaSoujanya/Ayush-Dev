<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../index.php");
    exit;
}

// Your protected page content goes here
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
      <div class="card-header">Vendor Table</div>
      <div class="card-body">
      <div class="dataTable_search mb-3 d-flex justify-content-between align-items-center">
  <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search..." style="max-width: 200px;">
  <button class="btn btn-success" onclick="addVendor()">+ Add Vendor</button>
</div>

        <div class="table-responsive">
          <table id="vendorTable" class="table table-striped">
            <thead>
              <tr>
                <th>S.no</th>
                <th>Vendor Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              <!-- Rows will be dynamically generated -->
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

  <!-- Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Vendor Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul id="vendorDetails" class="list-group">
            <!-- Vendor details will be dynamically inserted here -->
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
     function addVendor() {
    window.location.href = "vendor_form.php";
  }
    let pageIndex = 1;
    let pageSize = 5;
    const tableBody = document.getElementById("tableBody");
    const pageInfo = document.getElementById("pageInfo");
    const globalSearch = document.getElementById("globalSearch");

    function fetchVendors() {
      const searchQuery = globalSearch.value || "";
      fetch(`fetch_vendors.php?page=${pageIndex}&size=${pageSize}&search=${searchQuery}`)
        .then((response) => response.json())
        .then((data) => {
          tableBody.innerHTML = data.rows
            .map(
              (row, index) => `
              <tr class="dataTable_row">
                <td>${(pageIndex - 1) * pageSize + index + 1}</td>
                <td>${row.vendor_name}</td>
                <td>${row.phone_number}</td>
                <td>${row.email}</td>
                <td>${row.address}</td>
                <td class="action-icons">
                  <i class="fas fa-eye" onclick="viewVendor(${row.id})" title="View"></i>
                  <i class="fas fa-edit" onclick="editVendor(${row.id})" title="Edit"></i>
                  <i class="fas fa-trash" onclick="deleteVendor(${row.id})" title="Delete"></i>
                </td>
              </tr>
            `
            )
            .join("");

          pageInfo.textContent = `${pageIndex} of ${data.totalPages}`;
          document.getElementById("previousPage").disabled = pageIndex <= 1;
          document.getElementById("nextPage").disabled = pageIndex >= data.totalPages;
        });
    }

    function viewVendor(id) {
      fetch(`fetch_vendor_details.php?id=${id}`)
        .then((response) => response.json())
        .then((data) => {
          const detailsList = Object.entries(data)
            .map(([key, value]) => `<li class="list-group-item"><strong>${key}:</strong> ${value || "N/A"}</li>`)
            .join("");
          document.getElementById("vendorDetails").innerHTML = detailsList;
          new bootstrap.Modal(document.getElementById("viewModal")).show();
        });
    }

    function editVendor(id) {
      alert(`Edit vendor with ID: ${id}`);
    }

    function deleteVendor(id) {
      if (confirm(`Are you sure you want to delete vendor ID: ${id}?`)) {
        alert(`Vendor with ID ${id} deleted`);
        // You can add actual deletion logic here
      }
    }

    document.getElementById("previousPage").addEventListener("click", () => {
      if (pageIndex > 1) {
        pageIndex--;
        fetchVendors();
      }
    });

    document.getElementById("nextPage").addEventListener("click", () => {
      pageIndex++;
      fetchVendors();
    });

    document.getElementById("pageSize").addEventListener("change", (e) => {
      pageSize = parseInt(e.target.value, 10);
      pageIndex = 1;
      fetchVendors();
    });

    globalSearch.addEventListener("input", () => {
      pageIndex = 1;
      fetchVendors();
    });

    fetchVendors();
    function deleteVendor(id) {
  if (confirm(`Are you sure you want to delete vendor ID: ${id}?`)) {
    fetch(`delete_vendor.php?id=${id}`, { method: "DELETE" })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.message);
          fetchVendors(); // Refresh the table after deletion
        } else {
          alert(data.message);
        }
      })
      .catch((error) => {
        console.error("Error deleting vendor:", error);
        alert("An error occurred while deleting the vendor.");
      });
  }
}
function editVendor(id) {
  // Redirect to the update_vendor.php page with the vendor ID
  window.location.href = `update_vendor.php?id=${id}`;
}

  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
