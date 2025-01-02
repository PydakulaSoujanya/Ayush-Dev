<?php
include('../config.php');

// Fetch Data
$query = "SELECT * FROM emp_info ORDER BY id DESC"; // Order by id descending
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
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
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Employees Info</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex align-items-center">
  <input type="text" class="form-control me-2" id="globalSearch" placeholder="Search...">
  <a href="emp-form.php" class="btn btn-primary ms-auto">Add Employee</a>
</div>

        <!-- Table -->
        <div class="table-responsive">
         <table class="table table-bordered">
            <thead>
              <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= htmlspecialchars($row['name']); ?></td>
                  <td><?= htmlspecialchars($row['email']); ?></td>
                  <td><?= htmlspecialchars($row['phone']); ?></td>
                  <td><?= ucfirst(htmlspecialchars($row['role'])); ?></td>
                  <td>
                    <a href="#" class="btn btn-sm view-btn" style="color: black;" data-id="<?php echo $row['id']; ?>" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="emp-edit.php?id=<?= $row['id']; ?>" class="btn btn-sm" style="color: black;" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="confirmDeletion(<?= $row['id']; ?>)" class="btn btn-sm" style="color: black;" title="Delete">
                        <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
       <!-- Modal for Employee Details -->
       <div class="modal fade" id="employeeDetailsModal" tabindex="-1" role="dialog" aria-labelledby="employeeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeDetailsModalLabel">Employee Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody id="employeeDetails">
                        <!-- Fetched employee details will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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

  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).on('click', '.view-btn', function(e) {
        e.preventDefault();
        const employeeId = $(this).data('id');

        // Fetch employee data using AJAX
        $.ajax({
            url: 'fetch_employee_data.php', // Backend script to fetch data
            type: 'POST',
            data: { id: employeeId },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    let detailsHtml = '';

                    // Display general employee fields
                    for (let key in response) {
                        if (!key.includes('file')) {
                            detailsHtml += `
                                <tr>
                                    <th>${key.replace(/_/g, ' ').toUpperCase()}</th>
                                    <td>${response[key]}</td>
                                </tr>
                            `;
                        }
                    }

                    // Display documents with download links
                    const documentTypes = {
                        'resume': 'Resume',
                        'offer_letter': 'Offer Letter',
                        'joining_letter': 'Joining Letter',
                        'police_verification_form': 'Police Verification Form'
                    };

                    for (let docType in documentTypes) {
                        if (response[docType]) {
                            detailsHtml += `
                                <tr>
                                    <th>${documentTypes[docType]}</th>
                                    <td><a href="uploads/${response[docType]}" target="_blank" download>Download ${documentTypes[docType]}</a></td>
                                </tr>
                            `;
                        }
                    }

                    // Check for other documents dynamically if needed
                    const otherDocs = Object.keys(response).filter(key => key.includes('file_'));
                    otherDocs.forEach(docKey => {
                        detailsHtml += `
                            <tr>
                                <th>${docKey.replace(/_/g, ' ').toUpperCase()}</th>
                                <td><a href="uploads/${response[docKey]}" target="_blank" download>Download ${docKey.replace(/_/g, ' ')}</a></td>
                            </tr>
                        `;
                    });

                    $('#employeeDetails').html(detailsHtml);
                    $('#employeeDetailsModal').modal('show'); // Show the modal
                }
            },
            error: function() {
                alert('Failed to fetch employee details.');
            }
        });
    });
</script>




  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
