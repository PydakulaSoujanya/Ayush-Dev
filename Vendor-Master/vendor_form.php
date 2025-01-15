<?php
// Fetch the data from service_master table
include '../config.php';

$sql = "SELECT `id`, `service_name` FROM `service_master` WHERE `status` = 'active'"; // Assuming 'active' status for services you want to show
$result = $conn->query($sql);

// Prepare dropdown options
$options = "";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id'] . "'>" . $row['service_name'] . "</option>";
    }
} else {
    $options = "<option value='' disabled>No services available</option>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
 
</head>
<body>
  <?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <div class="card custom-card">
      <div class="card-header custom-card-header">Add New Vendor</div>
      <div class="card-body">
        <form action="vendordb.php" method="POST" id="customer_form" enctype="multipart/form-data">
          <div class="row">
            <!-- Vendor Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="vendor_name" class="custom-label">Vendor Name</label>
                <input type="text" name="vendor_name" id="vendor_name" class="form-control custom-input" placeholder="Enter Vendor Name">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="gstin" class="custom-label">GSTIN</label>
                <input type="text" name="gstin" id="gstin" class="form-control custom-input" placeholder="Enter GSTIN">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="contact_person" class="custom-label">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control custom-input" placeholder="Enter Contact Person">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="phone_number" class="custom-label">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control custom-input" placeholder="Enter Phone Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="email" class="custom-label">Email</label>
                <input type="email" name="email" id="email" class="form-control custom-input" placeholder="Enter Email">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="supporting_documents" class="custom-label">Documents</label>
                <input type="file" name="supporting_documents" id="supporting_documents" class="form-control custom-input">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="services_provided" class="custom-label">Services Provided</label>
                <select class="form-control" name="service_type[]" id="service_type" style="appearance: none; padding-right: 2.5rem;">
      <option value="" disabled selected>Select Role</option>
      <?php echo $options; ?>  <!-- Dynamically populated options -->
    </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="vendor_type" class="custom-label">Vendor Type</label>
                <select name="vendor_type" id="vendor_type" class="form-control custom-input">
                  <option value="Individual">Individual</option>
                  <option value="Company">Company</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>

            <!-- Address Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="address_line1" class="custom-label">Flat, House No., Building</label>
                <input type="text" name="address_line1" id="address_line1" class="form-control custom-input" placeholder="Enter Address Line 1">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="address_line2" class="custom-label">Area, Street, Sector</label>
                <input type="text" name="address_line2" id="address_line2" class="form-control custom-input" placeholder="Enter Address Line 2">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="pincode" class="custom-label">Pincode</label>
                <input type="text" name="pincode" id="pincode" class="form-control custom-input" placeholder="Enter Pincode" maxlength="6">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="landmark" class="custom-label">Landmark</label>
                <input type="text" name="landmark" id="landmark" class="form-control custom-input" placeholder="Enter Landmark">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="city" class="custom-label">City</label>
                <input type="text" name="city" id="city" class="form-control custom-input" placeholder="Enter City">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="state" class="custom-label">State</label>
                <select name="state" id="state" class="form-control custom-input">
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

            <!-- Bank Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="bank_name" class="custom-label">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control custom-input" placeholder="Enter Bank Name">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="account_number" class="custom-label">Account Number</label>
                <input type="text" name="account_number" id="account_number" class="form-control custom-input" placeholder="Enter Account Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="ifsc" class="custom-label">IFSC Code</label>
                <input type="text" name="ifsc" id="ifsc" class="form-control custom-input" placeholder="Enter IFSC Code">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="branch" class="custom-label">Branch</label>
                <input type="text" name="branch" id="branch" class="form-control custom-input" placeholder="Enter Branch">
              </div>
            </div>
          </div>
          <!-- Submit Button -->
          <div class="submit-btn-container">
            <button type="submit" class="btn btn-secondary submit-btn">Submit</button>
          </div>
          
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
