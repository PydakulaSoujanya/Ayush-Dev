<?php
// Include database connection
include '../config.php';

// Check if service ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $serviceId = $_GET['id'];

    // Check if the connection is established
    if (!isset($conn)) {
        die("Error: Database connection not established.");
    }

    // Query to fetch the service data by ID
    $sql = "SELECT `id`, `service_name`, `status`, `daily_rate_8_hours`, `daily_rate_12_hours`, `daily_rate_24_hours`, `description` FROM `service_master` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $service = $result->fetch_assoc();
        } else {
            die("Error: No service found with the provided ID.");
        }
    } else {
        die("Error: Failed to prepare the SQL statement.");
    }
} else {
    die("Error: Service ID is missing in the URL.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Service Master</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .card {
      box-shadow: 0 10px 15px rgba(0, 1, 4, 0.7);
      margin-bottom: 20px;
      border-radius: 10px;
    }
    .card-header {
      background-color: #6c757d !important;
      font-size: 1.5rem;
      font-weight: bold;
      text-align: left;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      color: white !important;
    }
    .btn {
      background-color: #007bff;
      color: white;
    }
    .btn:hover {
      background-color: #0056b3;
    }
    .form-group {
      margin-bottom: 0.5rem;
    }
    label {
      position: absolute;
      top: -10px;
      left: 10px;
      background-color: white;
      padding: 0 5px;
      font-size: 14px;
      font-weight: 500;
      color: grey;
    }
    .form-control {
      margin-bottom: 0.5rem;
      padding: 0.375rem 0.75rem;
    }

    /* Standardize placeholder styles */
    ::placeholder,
    select.form-control option[disabled][selected] {
      font-size: 14px;
      color: grey;
    }

    :-ms-input-placeholder { /* IE10-11 */
      font-size: 14px;
      color: grey;
    }

    ::-ms-input-placeholder { /* Edge */
      font-size: 14px;
      color: grey;
    }

    .row .col-md-6,
    .row .col-lg-3 {
      padding-bottom: 30px;
    }
  </style>
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <?php
include '../navbar.php';
?>
  <div class="container mt-7">
  <div class="card">
      <div class="card-header">Update Services</div>
      <div class="card-body">
   
    <form action="update_service_masterdb.php" method="POST">
      <input type="hidden" name="id" value="<?php echo $service['id']; ?>" />
      
      <!-- Service Name -->
      <div class="row">
      
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="form-group">Service Name</label>
            <select id="service-name" class="form-control" name="service_name">
    <option value="" disabled>Select Service</option>
    <?php
    $sql = "SELECT `service_name` FROM `service_master`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = isset($service['service_name']) && $service['service_name'] === $row['service_name'] ? 'selected' : '';
            echo "<option value='{$row['service_name']}' $selected>{$row['service_name']}</option>";
        }
    }
    ?>
    <option value="other">Other</option>
</select>

          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="form-group">Status</label>
            <select class="form-control" name="status">
              <option value="active" <?php echo ($service['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
              <option value="inactive" <?php echo ($service['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Daily Rates -->
      <div class="row">
        <div class="col-md-4">
          <div class="input-field-container">
            <label class="form-group">Daily Rate (8 Hours)</label>
            <input type="number" class="form-control" name="daily_rate_8_hours" value="<?php echo $service['daily_rate_8_hours']; ?>" placeholder="Enter Rate for 8 Hours" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-field-container">
            <label class="form-group">Daily Rate (12 Hours)</label>
            <input type="number" class="form-control" name="daily_rate_12_hours" value="<?php echo $service['daily_rate_12_hours']; ?>" placeholder="Enter Rate for 12 Hours" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-field-container">
            <label class="form-group">Daily Rate (24 Hours)</label>
            <input type="number" class="form-control" name="daily_rate_24_hours" value="<?php echo $service['daily_rate_24_hours']; ?>" placeholder="Enter Rate for 24 Hours" />
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="input-field-container">
            <label class="form-group">Description</label>
            <textarea class="form-control" rows="3" name="description" placeholder="Enter Service Description"><?php echo $service['description']; ?></textarea>
          </div>
        </div>
      </div>
      <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;" >Update</button>
          </div>
      <!-- <div class="col-md-12 mt-4 text-center">
  <div class="input-field-container">
    <button type="submit" class="btn btn-primary" name="submit" value="Submit" style="background-color: #A26D2B; border-color: #A26D2B; color: #fff;">
      Submit
    </button>
  </div>
</div> -->
    </form>
  </div>
</div>
</div>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <!-- Alert Script -->
  <?php
  if (isset($_GET['status']) && $_GET['status'] == 'success') {
      echo "<script type='text/javascript'>
              alert('Service updated successfully!');
            </script>";
  }
  ?>

</body>
</html>
