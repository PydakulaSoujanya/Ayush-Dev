<?php
include('../config.php'); 
$query = "CALL GetAllExpenses()";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
  <title>Expense Claims</title>
  
</head>
<body>
<?php
include('../navbar.php');?>

<div class="container mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Expenses</div>

      <!-- Card Body -->
      <div class="card-body">
     
        <!-- Table -->
        <div class="table-responsive p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark">
                    <tr>
                            <th>S.No</th>
                            <th>Entity Name</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date Incurred</th>
                            <th>Action</th>
                        </tr>
    </thead>
    <tbody id="tableBody">
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $sno = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $sno++;
                                echo "<tr>
                                    <td>$sno</td>
                                    <td>{$row['entity_name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['amount']}</td>
                                    <td>{$row['date_incurred']}</td>
                                    <td>
                                        <button class='btn btn-sm view-btn' style='color: black;' data-bs-toggle='modal' data-bs-target='#detailsModal' 
                                            data-status='{$row['status']}'
                                            data-payment-status='{$row['payment_status']}'
                                            data-created-at='{$row['created_at']}'
                                            data-updated-at='{$row['updated_at']}'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
</table>

      </div>
    </div>
  </div>
  <!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <p><strong>Payment Status:</strong> <span id="modalPaymentStatus"></span></p>
                <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                <p><strong>Updated At:</strong> <span id="modalUpdatedAt"></span></p>
            </div>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const status = button.getAttribute('data-status');
                const paymentStatus = button.getAttribute('data-payment-status');
                const createdAt = button.getAttribute('data-created-at');
                const updatedAt = button.getAttribute('data-updated-at');

                document.getElementById('modalStatus').textContent = status;
                document.getElementById('modalPaymentStatus').textContent = paymentStatus;
                document.getElementById('modalCreatedAt').textContent = createdAt;
                document.getElementById('modalUpdatedAt').textContent = updatedAt;
            });
        });
    });
</script>
<script>
      $(document).ready(function() {
      // Initialize DataTable
      const table = $('#employeeTable').DataTable({
        paging: true, // Enable pagination
        searching: true, // Enable global search
        ordering: true, // Enable column-based ordering
        lengthMenu: [5, 10, 20, 50], // Rows per page options
        pageLength: 5, // Default rows per page
        language: {
          search: "Search Expenses:", // Customize the search label
        },
      });

      // Global Search
      $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
</script>
</body>
</html>