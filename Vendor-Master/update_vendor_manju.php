<div?php
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

        // Update vendor data in the database
        $update_sql = "UPDATE vendors SET 
                            vendor_name = ?, 
                            gstin = ?, 
                            phone_number = ?, 
                            email = ?, 
                           
                            vendor_type = ?, 
                            services_provided = ?, 
                            vendor_groups = ?,
                            address_line1 = ?,
                            address_line2 = ?,
                            city = ?,
                            state = ?,
                            landmark = ?,
                            pincode = ?, 
                            bank_name = ?, 
                            account_number = ?, 
                            ifsc = ?, 
                            branch = ? 
                        WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "sssssssssssssssssi",
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
            $branch,
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
  <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
<?php
  include '../navbar.php';
  ?>
  <div class="container mt-7">
  <div class="card custom-card">
    <div class="card-header custom-card-header">Update Vendor</div>
    <div class="card-body">
    <form action="update_vendor.php?id=<?php echo $id; ?>" method="POST">
    < class="row form-section form-first-row">
            <div class="row">
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Vendor Name</label>
            <input type="text" id="vendor_name" name="vendor_name" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" required />
          </div>
        </div>
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">GSTIN</label>
            <input type="text" class="form-control custom-input" id="gstin" name="gstin" value="<?php echo htmlspecialchars($vendor['gstin']); ?>" required />
          </div>
        </div>
        <div class="col-md-6 col-lg-3 custom-padding">
                    <div class="form-group custom-form-group">
                        <label class="custom-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['contact_person']); ?>" placeholder="Enter Contact Person" />
                    </div>
                </div>
 
        <div class="col-md-6 col-lg-3 custom-padding">
        <div class="form-group custom-form-group">
            <label class="custom-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['phone_number']); ?>" required />
          </div>
        </div>

        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Email</label>
            <input type="email" id="email" name="email" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['email']); ?>" required />
          </div>
        </div>

        <div class="col-md-6 col-lg-3 custom-padding">
                    <div class="form-group custom-form-group">
                        <label class="custom-label">Documents</label>
                        <input type="file" name="supporting_documents" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['supporting_documents']); ?>"/>
                    </div>
                </div>
              
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Services Provided</label>
            <input type="text" id="services_provided" name="services_provided" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['services_provided']); ?>" />
          </div>
        </div>
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Vendor Type</label>
            <select class="form-control custom-input" name="vendor_type" id="vendor_type">
              <option value="Individual" <?php echo ($vendor['vendor_type'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
              <option value="Company" <?php echo ($vendor['vendor_type'] == 'Company') ? 'selected' : ''; ?>>Company</option>
              <option value="Other" <?php echo ($vendor['vendor_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
          </div>
        </div>


    <div class="col-md-6 col-lg-3 custom-padding">
       <div class="form-group custom-form-group">
    <label class="custom-label">Vendor Groups</label>
    <select id="vendor_groups" name="vendor_groups" class="form-control custom-input">
      <option value="Nursing Services" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Nursing Services' ? 'selected' : ''; ?>>Nursing Services</option>
      <option value="Electricity Services" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Electricity Services' ? 'selected' : ''; ?>>Electricity Services</option>
      <option value="Others" <?php echo isset($vendor['vendor_groups']) && $vendor['vendor_groups'] == 'Others' ? 'selected' : ''; ?>>Others</option>
    </select>
  </div>
</div>

                <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Pincode</label>
            <input 
              type="text" 
              name="pincode" 
              class="form-control custom-input" 
              placeholder="6 digits [0-9] PIN code" 
              value="<?php echo htmlspecialchars($vendor['pincode']); ?>" 
              required 
              pattern="\d{6}" 
              maxlength="6" />
          </div>
        </div>
      <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Flat, House No., Building, Company, Apartment</label>
            <input 
              type="text" 
              name="address_line1" 
              class="form-control custom-input" 
              value="<?php echo htmlspecialchars($vendor['address_line1']); ?>" 
              placeholder="Enter Flat, House No., Building, etc." 
              required />
          </div>
        </div>

        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Area, Street, Sector, Village</label>
            <input 
              type="text" 
              name="address_line2" 
              class="form-control custom-input" 
              value="<?php echo htmlspecialchars($vendor['address_line2']); ?>" 
              placeholder="Enter Area, Street, Sector, Village" />
          </div>
        </div>
     
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Landmark</label>
            <input 
              type="text" 
              name="landmark" 
              class="form-control custom-input" 
              value="<?php echo htmlspecialchars($vendor['landmark']); ?>" 
              placeholder="E.g. near Apollo Hospital" />
          </div>
        </div>
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Town/City</label>
            <input 
              type="text" 
              name="city" 
              class="form-control custom-input" 
              placeholder="Enter Town/City" 
              value="<?php echo htmlspecialchars($vendor['city']); ?>" 
              required />
          </div>
        </div>
      

     
    <?php
    include('states_dropdown.php'); // Assuming this file defines an array $states with all state names
    $selectedState = $vendor['state']; // Fetch the current state from the vendor data
    ?>
    <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
        <label class="custom-label">State</label>
        <select name="state" class="form-control custom-input" required> <!-- Use "state" instead of "state[]" -->
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state); ?>" <?= $state === $selectedState ? 'selected' : '' ?>>
                    <?= htmlspecialchars($state); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
   </div>




        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['bank_name']); ?>" />
          </div>
        </div>
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Account Number</label>
            <input type="text" class="form-control custom-input" id="account_number" name="account_number" value="<?php echo htmlspecialchars($vendor['account_number']); ?>" />
          </div>
        </div>
     
     
        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">IFSC</label>
            <input type="text" id="ifsc" name="ifsc" class="form-control custom-input" value="<?php echo htmlspecialchars($vendor['ifsc']); ?>" />
          </div>
        </div>

        <div class="col-md-6 col-lg-3 custom-padding">
          <div class="form-group custom-form-group">
            <label class="custom-label">Branch</label>
            <input type="text" id="branch" name="branch" class="form-control custom-input" placeholder="Enter Branch" value="<?php echo htmlspecialchars($vendor['branch']); ?>"  />
          </div>
        </div>
        </div>
      

        <div class="submit-btn-container">
      <button type="submit" class="btn btn-secondary submit-btn">Update Vendor</button>
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