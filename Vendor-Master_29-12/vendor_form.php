<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../index.php");
    exit;
}

// Your protected page content goes here
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Styled Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include('../navbar.php'); ?>
<div class="container mt-7">
    <h3 class="mb-4">Vendor Form</h3>
    <form action="vendordb.php" method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Name</label>
            <input type="text" id="vendor_name" name="vendor_name" class="styled-input" placeholder="Enter your name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">GSTIN</label>
            <input type="gstin" class="styled-input" id="gstin" name="gstin" placeholder="Enter your gstin" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Contact Person</label>
            <input type="text" id="contact_person" name="contact_person" class="styled-input" placeholder="Enter Contact person name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Documents</label>
            <input type="file" name="supporting_documents" id="supporting_documents" class="styled-input" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Phone Number</label>
            <input type="phone_number" id="phone_number" name="phone_number" class="styled-input" placeholder="Enter your phonenumber" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Email</label>
            <input type="email" id="email" name="email" class="styled-input" placeholder="Enter your email" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Services Provided</label>
            <select id="services_provided" name="services_provided" class="styled-input">
              <option value="Fully Trained Nurse">Fully Trained Nurse</option>
              <option value="Semi-Trained Nurse">Semi-Trained Nurse</option>
              <option value="Caretaker">Caretaker</option>
              <option value="Caretaker">Naanies</option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Type</label>
            <select class="styled-input" name="vendor_type" id="vendor_type">
              <option value="Individual">Individual</option>
              <option value="Company">Company</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Groups</label>
            <select id="vendor_groups" name="vendor_groups" class="styled-input">
              <option value="Nursing Services">Nursing Services</option>
              <option value="Electricity Services">Electricity Services</option>
              <option value="Others">Others</option>
            </select>
          </div>
        </div>

        <!-- New address fields -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Pincode</label>
            <input 
              type="text" 
              name="pincode" 
              class="styled-input" 
              placeholder="6 digits [0-9] PIN code" 
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
            <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter bank name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Account Number</label>
            <input type="text" class="styled-input" id="account_number" name="account_number" placeholder="Enter account number" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">IFSC</label>
            <input type="text" id="ifsc" name="ifsc" class="styled-input" placeholder="Enter IFSC code" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Branch</label>
            <input type="text" id="branch" name="branch" class="styled-input" placeholder="Enter Branch" />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
