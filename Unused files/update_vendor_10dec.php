<?php
// Include the database configuration file
include("config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    
    // Fetch vendor data
    $sql = "SELECT * FROM vendors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendor = $result->fetch_assoc();

    if (!$vendor) {
        die("Vendor not found.");
    }

    // Handle form submission for updating vendor
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect form data
        $vendor_name = $_POST['vendor_name'];
        $gstin = $_POST['gstin'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $vendor_type = $_POST['vendor_type'];
        $services_provided = $_POST['services_provided'];
        $additional_notes = $_POST['additional_notes'];
        $bank_name = $_POST['bank_name'];
        $account_number = $_POST['account_number'];
        $ifsc = $_POST['ifsc'];
        $atatus = $_POST['status'];

        // Update vendor data in the database
        $update_sql = "UPDATE vendors SET 
                            vendor_name = ?, 
                            gstin = ?, 
                            phone_number = ?, 
                            email = ?, 
                            address = ?, 
                            vendor_type = ?, 
                            services_provided = ?, 
                            additional_notes = ?, 
                            bank_name = ?, 
                            account_number = ?, 
                            ifsc = ?, 
                            status = ? 
                        WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "ssssssssssssi",
            $vendor_name,
            $gstin,
            $phone_number,
            $email,
            $address,
            $vendor_type,
            $services_provided,
            $additional_notes,
            $bank_name,
            $account_number,
            $ifsc,
            $status,
            $id
        );

        // Execute the correct prepared statement
        if ($update_stmt->execute()) {
            // If the update is successful, use a script to show a popup and redirect
            echo "<script>
                alert('Vendor updated successfully!');
                window.location.href = 'vendors.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to update vendor.');
                window.history.back();
            </script>";
        }

        $update_stmt->close();
    }
} else {
    echo "Invalid request.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Vendor</title>
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
  </style>
</head>
<body>
  <div class="container mt-5">
    <h3 class="mb-4">Update Vendor</h3>
    <form action="update_vendor.php?id=<?php echo $id; ?>" method="POST">
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Name</label>
            <input type="text" id="vendor_name" name="vendor_name" class="styled-input" value="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">GSTIN</label>
            <input type="text" class="styled-input" id="gstin" name="gstin" value="<?php echo htmlspecialchars($vendor['gstin']); ?>" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="styled-input" value="<?php echo htmlspecialchars($vendor['phone_number']); ?>" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Email</label>
            <input type="email" id="email" name="email" class="styled-input" value="<?php echo htmlspecialchars($vendor['email']); ?>" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Services Provided</label>
            <input type="text" id="services_provided" name="services_provided" class="styled-input" value="<?php echo htmlspecialchars($vendor['services_provided']); ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Type</label>
            <select class="styled-input" name="vendor_type" id="vendor_type">
              <option value="Individual" <?php echo ($vendor['vendor_type'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
              <option value="Company" <?php echo ($vendor['vendor_type'] == 'Company') ? 'selected' : ''; ?>>Company</option>
              <option value="Other" <?php echo ($vendor['vendor_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Address</label>
            <textarea class="styled-input" name="address" id="address"><?php echo htmlspecialchars($vendor['address']); ?></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Additional Notes</label>
            <textarea class="styled-input" name="additional_notes" id="additional_notes"><?php echo htmlspecialchars($vendor['additional_notes']); ?></textarea>
          </div>
        </div>
      </div>
      <h3 class="mb-4">Bank Details</h3>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" class="styled-input" value="<?php echo htmlspecialchars($vendor['bank_name']); ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Account Number</label>
            <input type="text" class="styled-input" id="account_number" name="account_number" value="<?php echo htmlspecialchars($vendor['account_number']); ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">IFSC</label>
            <input type="text" id="ifsc" name="ifsc" class="styled-input" value="<?php echo htmlspecialchars($vendor['ifsc']); ?>" />
          </div>
        </div>
        <div class="col-md-6">
        <div class="input-field-container">
  <label class="input-label">Status</label>
  <select id="status" name="status" class="styled-input">
    <option value="Active" <?php echo ($vendor['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
    <option value="In Active" <?php echo ($vendor['status'] == 'In Active') ? 'selected' : ''; ?>>In Active</option>
  </select>
</div>
</div>
        
      </div>
      <button type="submit" class="btn btn-primary">Update Vendor</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
