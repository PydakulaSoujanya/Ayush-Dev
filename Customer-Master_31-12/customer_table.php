<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration and navbar
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Customer Master Table</title>
</head>
<body>
  <?php include '../navbar.php'; ?>
  
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 table-title">Customer Master Table</h5>
        <a href="customer_form.php" class="add_button"><strong class="add_button_plus">+</strong> Add Customer</a>
      </div>
      <div class="table-responsive dataTable_wrapper">
        <table id="employeeTable" class="display table table-striped" style="width:100%">
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
          // Call the stored procedure and display data
          $sql = "CALL GetCustomerData()";
          if ($conn->multi_query($sql)) {
              $serial = 1;
              do {
                  if ($result = $conn->store_result()) {
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
                                      <a href='#' onclick='viewDetails({$row['id']})'>
                                        <i class='fas fa-eye' style='color: black;'></i>
                                      </a>
                                      <a href='customer-edit.php?id={$row['id']}'>
                                        <i class='fas fa-edit' style='color: black;'></i>
                                      </a>
                                      <a href='delete_customer.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'>
                                        <i class='fas fa-trash' style='color: black;'></i>
                                      </a>
                                  </td>
                                </tr>";
                          $serial++;
                      }
                      $result->free();
                  }
              } while ($conn->next_result());
          } else {
              echo "<tr><td colspan='8'>No records found</td></tr>";
          }
          $conn->close();
          ?>
          </tbody>
        </table>
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
          <!-- Content will be loaded dynamically -->
        </div>
      </div>
    </div>
  </div>

  <!-- JS Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <script>
    // Initialize DataTables
    $(document).ready(function() {
      $('#employeeTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthMenu: [5, 10, 20, 50],
        pageLength: 5,
        language: {
          search: "Search Customer:",
        }
      });
    });

    // Load customer details in the modal
    function viewDetails(id) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = "<p>Loading...</p>";

      fetch(`customer_view_modal.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
          modalContent.innerHTML = data;
          const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
          viewModal.show();
        })
        .catch(error => {
          console.error('Error:', error);
          modalContent.innerHTML = "<p>Failed to load details.</p>";
        });
    }
  </script>
</body>
</html>
