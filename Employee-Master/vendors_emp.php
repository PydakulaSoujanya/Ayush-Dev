<?php
include('../config.php');

// Fetch Data
$query = "SELECT * FROM emp_info WHERE reference = 'vendors' ORDER BY id DESC"; // Filter for ayush employees
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Store fetched data in an array
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Data Table</title>
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-7">
  <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 table-title">Employees Info</h5>
      <a href="emp-form.php" class="add_button"><strong class="add_button_plus">+</strong> Add Employee</a>
    </div>

    <div class="table-responsive mt-3 p-4">
      <table id="employeeTable" class="display table table-striped" style="width:100%">
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
          <?php $i = 1; foreach ($data as $row): ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['phone']); ?></td>
            <td><?= ucfirst(htmlspecialchars($row['role'])); ?></td>
            <td>
                <a href="javascript:void(0)" class="btn btn-sm view-btn" style="color: black;" data-id="<?= $row['id']; ?>" title="View">
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
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="employeeDetailsModal" tabindex="-1" aria-labelledby="employeeDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeDetailsModalLabel">Employee Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <tbody id="employeeDetails"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
  $('#employeeTable').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    language: { search: "Search Employees:" }
  });
});

$(document).on('click', '.view-btn', function(e) {
            e.preventDefault();
            const employeeId = $(this).data('id');

            $.ajax({
                url: 'fetch_employee_data.php',
                type: 'POST',
                data: { id: employeeId },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        let detailsHtml = '';
                        for (let key in response) {
                            // Check for document fields and create links
                            if (key === 'police_verification_document' || key === 'adhar_upload_doc' || key === 'other_doc_name') {
                                if (response[key]) {
                                    detailsHtml += `<tr><th>${key.replace(/_/g, ' ').toUpperCase()}</th><td><a href="${response[key]}" target="_blank">View Document</a></td></tr>`;
                                }
                            } else {
                                detailsHtml += `<tr><th>${key.replace(/_/g, ' ').toUpperCase()}</th><td>${response[key]}</td></tr>`;
                            }
                        }

                        $('#employeeDetails').html(detailsHtml);
                        $('#employeeDetailsModal').modal('show');
                    }
                },
                error: function() {
                    alert('Failed to fetch employee details.');
                }
            });
        });

function confirmDeletion(id) {
  if (confirm("Are you sure you want to delete this employee?")) {
    window.location.href = `emp_delete.php?id=${id}`;
  }
}
</script>

</body>
</html>
