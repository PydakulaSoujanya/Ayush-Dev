
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<?php include('../navbar.php'); ?>
<div class="container mt-7">
    <h3 class="mb-4">Vendor Form</h3>
    <form action="vendordb.php" method="POST" enctype="multipart/form-data">
        <!-- Vendor Details -->
        <div class="row form-section form-first-row">
            <h2 class="section-title1">Vendor Details</h2>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="styled-input" placeholder="Enter Vendor Name" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">GSTIN</label>
                        <input type="text" name="gstin" class="styled-input" placeholder="Enter GSTIN" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Contact Person</label>
                        <input type="text" name="contact_person" class="styled-input" placeholder="Enter Contact Person" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Phone Number</label>
                        <input type="text" name="phone_number" class="styled-input" placeholder="Enter Phone Number" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Email</label>
                        <input type="email" name="email" class="styled-input" placeholder="Enter Email" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Documents</label>
                        <input type="file" name="supporting_documents" class="styled-input" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Provided -->
        <div class="row form-section form-second-row-full-vendor mt-3">
            <h2 class="section-title2">Services Provided</h2>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Services Provided</label>
                        <select name="services_provided" class="styled-input">
                            <option value="Fully Trained Nurse">Fully Trained Nurse</option>
                            <option value="Semi-Trained Nurse">Semi-Trained Nurse</option>
                            <option value="Caretaker">Caretaker</option>
                            <option value="Nanny">Nanny</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Vendor Type</label>
                        <select name="vendor_type" class="styled-input">
                            <option value="Individual">Individual</option>
                            <option value="Company">Company</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Vendor Groups</label>
                        <select name="vendor_groups" class="styled-input">
                            <option value="Nursing Services">Nursing Services</option>
                            <option value="Electricity Services">Electricity Services</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Details -->
        <div class="row form-section form-first-row mt-3">
            <h2 class="section-title3">Address Details</h2>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Flat, House No., Building</label>
                        <input type="text" name="address_line1" class="styled-input" placeholder="Enter Address Line 1" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Area, Street, Sector</label>
                        <input type="text" name="address_line2" class="styled-input" placeholder="Enter Address Line 2" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Pincode</label>
                        <input type="text" name="pincode" class="styled-input" placeholder="Enter Pincode" maxlength="6" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Landmark</label>
                        <input type="text" name="landmark" class="styled-input" placeholder="Enter Landmark" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">City</label>
                        <input type="text" name="city" class="styled-input" placeholder="Enter City" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">State</label>
                        <select name="state" class="styled-input">
                            <option value="" disabled selected>Choose a State</option>
                           
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
        </div>

        <!-- Bank Details -->
        <div class="row form-section form-fouth-row-bank-details">
            <h2 class="section-title4">Bank Details</h2>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Bank Name</label>
                        <input type="text" name="bank_name" class="styled-input" placeholder="Enter Bank Name" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Account Number</label>
                        <input type="text" name="account_number" class="styled-input" placeholder="Enter Account Number" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">IFSC Code</label>
                        <input type="text" name="ifsc" class="styled-input" placeholder="Enter IFSC Code" />
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="input-field-container">
                        <label class="input-label">Branch</label>
                        <input type="text" name="branch" class="styled-input" placeholder="Enter Branch" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row form-submit emp-submit mt-2">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn w-100">Submit</button>
            </div>
        </div>
    </form>
</div>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
