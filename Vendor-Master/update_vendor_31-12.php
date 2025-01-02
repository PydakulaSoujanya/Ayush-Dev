<?php
// Include the database configuration file
include("../config.php");

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
        
        $vendor_type = $_POST['vendor_type'];
        $services_provided = $_POST['services_provided'];
        $vendor_groups = $_POST['vendor_groups'];
        $address_line1 = $_POST['address_line1'];
        $address_line2 = $_POST['address_line2'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $landmark = $_POST['landmark'];
        $pincode = $_POST['pincode'];
        $bank_name = $_POST['bank_name'];
        $account_number = $_POST['account_number'];
        $ifsc = $_POST['ifsc'];
        $branch = $_POST['branch'];

        // Call the stored procedure to update vendor data
        $update_stmt = $conn->prepare("CALL UpdateVendor(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $update_stmt->bind_param(
            "issssssssssssssssi",
            $id,
            $vendor_name,
            $gstin,
            $phone_number,
            $email,
            
            $vendor_type,
            $services_provided,
            $vendor_groups,
            $address_line1,
            $address_line2,
            $city,
            $state,
            $landmark,
            $pincode,
            $bank_name,
            $account_number,
            $ifsc,
            $branch
        );

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
    <label class="input-label">Vendor Groups</label>
    <select id="vendor_groups" name="vendor_groups" class="styled-input">
      <option value="Nursing Services" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Nursing Services' ? 'selected' : ''; ?>>Nursing Services</option>
      <option value="Electricity Services" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Electricity Services' ? 'selected' : ''; ?>>Electricity Services</option>
      <option value="Others" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Others' ? 'selected' : ''; ?>>Others</option>
    </select>
  </div>
</div>

      <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Pincode</label>
            <input 
              type="text" 
              name="pincode" 
              class="styled-input" 
              placeholder="6 digits [0-9] PIN code" 
              value="<?php echo htmlspecialchars($vendor['pincode']); ?>" 
              required 
              pattern="\d{6}" 
              maxlength="6" />
          </div>
        </div>
      <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Flat, House No., Building, Company, Apartment</label>
            <input 
              type="text" 
              name="address_line1" 
              class="styled-input" 
              value="<?php echo htmlspecialchars($vendor['address_line1']); ?>" 
              placeholder="Enter Flat, House No., Building, etc." 
              required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Area, Street, Sector, Village</label>
            <input 
              type="text" 
              name="address_line2" 
              class="styled-input" 
              value="<?php echo htmlspecialchars($vendor['address_line2']); ?>" 
              placeholder="Enter Area, Street, Sector, Village" />
          </div>
        </div>
      </div>

      <div class="row">
      

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Landmark</label>
            <input 
              type="text" 
              name="landmark" 
              class="styled-input" 
              value="<?php echo htmlspecialchars($vendor['landmark']); ?>" 
              placeholder="E.g. near Apollo Hospital" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Town/City</label>
            <input 
              type="text" 
              name="city" 
              class="styled-input" 
              placeholder="Enter Town/City" 
              value="<?php echo htmlspecialchars($vendor['city']); ?>" 
              required />
          </div>
        </div>
      </div>

      <div class="row">
       

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">State</label>
            <select 
              name="state" 
              class="styled-input"
              value="<?php echo htmlspecialchars($vendor['state']); ?>"  
              required>
              <option value="" disabled selected>Choose a state</option>
              <option value="Andhra Pradesh">Andhra Pradesh</option>
              <option value="Arunachal Pradesh">Arunachal Pradesh</option>
              <option value="Assam">Assam</option>
              <option value="Bihar">Bihar</option>
              <option value="Chhattisgarh">Chhattisgarh</option>
              <option value="Goa">Goa</option>
              <option value="Gujarat">Gujarat</option>
              <option value="Haryana">Haryana</option>
              <option value="Himachal Pradesh">Himachal Pradesh</option>
              <option value="Jharkhand">Jharkhand</option>
              <option value="Karnataka">Karnataka</option>
              <option value="Kerala">Kerala</option>
              <option value="Madhya Pradesh">Madhya Pradesh</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Manipur">Manipur</option>
              <option value="Meghalaya">Meghalaya</option>
              <option value="Mizoram">Mizoram</option>
              <option value="Nagaland">Nagaland</option>
              <option value="Odisha">Odisha</option>
              <option value="Punjab">Punjab</option>
              <option value="Rajasthan">Rajasthan</option>
              <option value="Sikkim">Sikkim</option>
              <option value="Tamil Nadu">Tamil Nadu</option>
              <option value="Telangana">Telangana</option>
              <option value="Tripura">Tripura</option>
              <option value="Uttar Pradesh">Uttar Pradesh</option>
              <option value="Uttarakhand">Uttarakhand</option>
              <option value="West Bengal">West Bengal</option>
              <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
              <option value="Chandigarh">Chandigarh</option>
              <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
              <option value="Delhi">Delhi</option>
              <option value="Jammu and Kashmir">Jammu and Kashmir</option>
              <option value="Ladakh">Ladakh</option>
              <option value="Lakshadweep">Lakshadweep</option>
              <option value="Puducherry">Puducherry</option>
            </select>
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
            <label class="input-label">Branch</label>
            <input type="text" id="branch" name="branch" class="styled-input" placeholder="Enter Branch" value="<?php echo htmlspecialchars($vendor['branch']); ?>"  />
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
