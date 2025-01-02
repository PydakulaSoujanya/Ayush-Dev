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

// Call the stored procedure
$service_id = isset($_GET['id']) ? (int)$_GET['id'] : NULL;  // Get the 'id' from the URL, if available
$sql = "CALL GetServiceMasterData(?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);  // Bind the 'service_id' parameter
$stmt->execute();

$result = $stmt->get_result();

// Fetch rows into an array
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$stmt->close();
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
  <title>Service Master Table</title>
</head>
<body>
  <?php
   include '../navbar.php';
  ?>
  <div class="container mt-7">
  <div class="dataTable_card card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 table-title">Manage Service Master</h5>
      <a href="servicemaster.php" class="add_button"><strong class="add_button_plus">+</strong>ServiceMaster</a>
    </div>

        <!-- Table -->
        <div class="table-responsive dataTable_wrapper">
        <table id="employeeTable" class="display table table-striped" style="width:100%">
        <thead>
              <tr>
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
            <tbody>
              <?php if (!empty($data)) {
                foreach ($data as $index => $row) { ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['daily_rate_8_hours']; ?></td>
                    <td><?php echo $row['daily_rate_12_hours']; ?></td>
                    <td><?php echo $row['daily_rate_24_hours']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td class="action-icons">
                      <!-- <a href="#" onclick='viewDetails(<?php echo json_encode($row); ?>)' data-toggle="modal" data-target="#detailsModal"><i class="btn btn-sm fas fa-eye"></i></a> -->
                      <a href="update_service_master.php?id=<?php echo $row['id']; ?>"><i class="btn btn-sm fas fa-edit"></i></a>
                      <a href="delete_servicemaster.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="btn btn-sm fas fa-trash"></i></a>
                    </td>
                  </tr>
              <?php } 
              } else { ?>
                <tr>
                  <td colspan="8" class="text-center">No records found</td>
                </tr>
              <?php } ?>
            </tbody>
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
      paging: true, // Enable pagination
      searching: true, // Enable global search
      ordering: true, // Enable column-based ordering
      lengthMenu: [5, 10, 20, 50], // Rows per page options
      pageLength: 5, // Default rows per page
      language: {
        search: "Search Services:", // Customize the search label
      }
    });
  });
</script>
  <script>
    // Function to View Details in Modal
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