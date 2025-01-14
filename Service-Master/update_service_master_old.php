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
  <!-- <style>
    /* General Styles */
    .input-field-container {
      position: relative;
      margin-bottom: 20px;
    }

    .input-label {
      position: absolute;
      top: -10px;
      left: 10px;
      background-color: white;
      padding: 0 5px;
      font-size: 14px;
      font-weight: bold;
      color: #A26D2B;
    }

    .styled-input {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      outline: none;
      box-sizing: border-box;
      border: 1px solid #A26D2B;
      border-radius: 5px;
    }

    .styled-input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    h3 {
      color: #A26D2B;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .btn {
        width: 100%; 
      }
    }
  </style> -->
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <?php
include '../navbar.php';
?>
  <div class="container mt-7">
    <h3 class="mb-4">Update Service Master</h3>
    <form action="update_service_masterdb.php" method="POST">
      <input type="hidden" name="id" value="<?php echo $service['id']; ?>" />
      
      <!-- Service Name -->
      <div class="row form-section form-first-row">
      
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Service Name</label>
            <select id="service-name" class="styled-input" name="service_name">
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
            <label class="input-label">Status</label>
            <select class="styled-input" name="status">
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
            <label class="input-label">Daily Rate (8 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_8_hours" value="<?php echo $service['daily_rate_8_hours']; ?>" placeholder="Enter Rate for 8 Hours" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-field-container">
            <label class="input-label">Daily Rate (12 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_12_hours" value="<?php echo $service['daily_rate_12_hours']; ?>" placeholder="Enter Rate for 12 Hours" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-field-container">
            <label class="input-label">Daily Rate (24 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_24_hours" value="<?php echo $service['daily_rate_24_hours']; ?>" placeholder="Enter Rate for 24 Hours" />
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="row">
        <div class="col-md-12">
          <div class="input-field-container">
            <label class="input-label">Description</label>
            <textarea class="styled-input" rows="3" name="description" placeholder="Enter Service Description"><?php echo $service['description']; ?></textarea>
          </div>
        </div>
      </div>
      
      <div class="col-md-12 mt-4 text-center">
  <div class="input-field-container">
    <button type="submit" class="btn btn-primary" name="submit" value="Submit" style="background-color: #A26D2B; border-color: #A26D2B; color: #fff;">
      Submit
    </button>
  </div>
</div>
    </form>
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
