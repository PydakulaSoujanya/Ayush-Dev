<?php
include 'config.php'; // Include your database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $customer_name = $_POST['customer_name'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $enquiry_date = $_POST['enquiry_date'];
    $enquiry_time = $_POST['enquiry_time'];
    $service_type = $_POST['service_type'];
    $enquiry_source = $_POST['enquiry_source'];
    $priority_level = $_POST['priority_level'];
    $status = $_POST['status'];
    $request_details = $_POST['request_details'];
    $resolution_notes = $_POST['resolution_notes'];
    $comments = $_POST['comments'];

    // Update the record in the database
    $sql = "UPDATE service_requests SET 
            customer_name = ?, contact_no = ?, email = ?, 
            enquiry_date = ?, enquiry_time = ?, service_type = ?, 
            enquiry_source = ?, priority_level = ?, status = ?, 
            request_details = ?, resolution_notes = ?, comments = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssi", 
                      $customer_name, $contact_no, $email, $enquiry_date, 
                      $enquiry_time, $service_type, $enquiry_source, 
                      $priority_level, $status, $request_details, 
                      $resolution_notes, $comments, $id);
                     
    if ($stmt->execute()) {
        // Correctly output the JavaScript
        echo "<script>
                alert('Service updated successfully!');
                window.location.href = 'view_services.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php
include 'navbar.php';
include 'config.php'; // Include your database connection

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input to prevent SQL injection

    // Fetch existing data from the database for this record
    $sql = "SELECT * FROM service_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the record exists, fetch the data
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Redirect if no record is found
        header("Location: view_services.php");
        exit;
    }
} else {
    // Redirect to view services page if no ID is provided
    header("Location: view_services.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Request Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .input-field-container {
      position: relative;
      margin-bottom: 15px;
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
      font-size: 12px;
      outline: none;
      box-sizing: border-box;
      border: 1px solid #A26D2B;
      border-radius: 5px; 
    }

    .styled-input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    h1, h2, h3, h4 {
      color: #A26D2B;
    }

    /* Add responsiveness as you already have it */
    @media (max-width: 768px) {
        .form-section {
            padding: 15px;
            margin: 10px;
        }
        .styled-input {
            font-size: 12px;
            padding: 8px;
        }
        .input-label {
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .form-section {
            padding: 10px;
            margin: 5px;
        }
        .styled-input {
            font-size: 12px;
            padding: 6px;
        }
        .input-label {
            font-size: 11px;
        }
        button {
            font-size: 14px;
            padding: 8px 12px;
        }
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container mt-7">
    <h3 class="mb-4"> Edit Service Request</h3>
    <div class="form-section">
      <form action="" method="POST">
        <div class="row">
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Customer Name</label>
              <input type="text" class="styled-input" name="customer_name" id="customer_name" value="<?= $row['customer_name']; ?>" placeholder="Enter Customer Name">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Contact No</label>
              <input type="text" class="styled-input" name="contact_no" id="contact_no" value="<?= $row['contact_no']; ?>" placeholder="Enter Contact Number">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Email</label>
              <input type="email" class="styled-input" name="email" id="email" value="<?= $row['email']; ?>" placeholder="Enter Email">
            </div>
          </div>

          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Enquiry Date</label>
              <input type="date" class="styled-input date-input" name="enquiry_date" id="enquiry-date" value="<?= $row['enquiry_date']; ?>" />
            </div>
          </div>

          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Enquiry Time</label>
              <input type="time" name="enquiry_time" class="styled-input" value="<?= $row['enquiry_time']; ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Service Type</label>
              <select class="styled-input" name="service_type">
                <option value="bank" <?= ($row['service_type'] == 'bank') ? 'selected' : ''; ?>>Bank Reconciliation</option>
                <option value="account" <?= ($row['service_type'] == 'account') ? 'selected' : ''; ?>>Account Information</option>
                <option value="other" <?= ($row['service_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Enquiry Source</label>
              <select class="styled-input" name="enquiry_source">
                <option value="phone" <?= ($row['enquiry_source'] == 'phone') ? 'selected' : ''; ?>>Phone Call</option>
                <option value="email" <?= ($row['enquiry_source'] == 'email') ? 'selected' : ''; ?>>Email</option>
                <option value="walkin" <?= ($row['enquiry_source'] == 'walkin') ? 'selected' : ''; ?>>Walk-In</option>
                <option value="website" <?= ($row['enquiry_source'] == 'website') ? 'selected' : ''; ?>>Website</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Priority Level</label>
              <select class="styled-input" name="priority_level">
                <option value="low" <?= ($row['priority_level'] == 'low') ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?= ($row['priority_level'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?= ($row['priority_level'] == 'high') ? 'selected' : ''; ?>>High</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Status</label>
              <select class="styled-input" name="status">
                <option value="new" <?= ($row['status'] == 'new') ? 'selected' : ''; ?>>New</option>
                <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="followup" <?= ($row['status'] == 'followup') ? 'selected' : ''; ?>>Follow-Up Required</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Request Details</label>
              <input type="text" class="styled-input" name="request_details" value="<?= $row['request_details']; ?>" placeholder="Enter Request Details">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Resolution Notes</label>
              <textarea class="styled-input" rows="1" name="resolution_notes" placeholder="Enter Resolution Notes"><?= $row['resolution_notes']; ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Comments</label>
              <textarea class="styled-input" rows="1" name="comments" placeholder="Enter Comments"><?= $row['comments']; ?></textarea>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <input type="hidden" name="id" value="<?= $row['id']; ?>"> <!-- Pass the ID for the update query -->
      </form>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>
