<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Form</title>
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
  <?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <div class="card">
      <div class="card-header">Add New Vendor</div>
      <div class="card-body">
        <form action="vendordb.php" method="POST" enctype="multipart/form-data">
          <div class="row mt-3">
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="vendor_name">Vendor Name</label>
                <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Enter Vendor Name">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" name="gstin" id="gstin" class="form-control" placeholder="Enter GSTIN">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control" placeholder="Enter Contact Person">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter Phone Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="supporting_documents">Documents</label>
                <input type="file" name="supporting_documents" id="supporting_documents" class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="services_provided">Services Provided</label>
                <select name="services_provided" id="services_provided" class="form-control">
                  <option value="" disabled selected>Choose a Service</option>
                  <option value="Fully Trained Nurse">Fully Trained Nurse</option>
                  <option value="Semi-Trained Nurse">Semi-Trained Nurse</option>
                  <option value="Caretaker">Caretaker</option>
                  <option value="Nanny">Nanny</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="vendor_type">Vendor Type</label>
                <select name="vendor_type" id="vendor_type" class="form-control">
                  <option value="" disabled selected>Choose a Vendor Type</option>
                  <option value="Individual">Individual</option>
                  <option value="Company">Company</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="address_line1">Flat, House No., Building</label>
                <input type="text" name="address_line1" id="address_line1" class="form-control" placeholder="Enter Address Line 1">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="address_line2">Area, Street, Sector</label>
                <input type="text" name="address_line2" id="address_line2" class="form-control" placeholder="Enter Address Line 2">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Enter Pincode" maxlength="6">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="landmark">Landmark</label>
                <input type="text" name="landmark" id="landmark" class="form-control" placeholder="Enter Landmark">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" class="form-control" placeholder="Enter City">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="state">State</label>
                <select name="state" id="state" class="form-control">
                  <option value="" disabled selected>Choose a State</option>
                  <option value="Andhra Pradesh">Andhra Pradesh</option>
                  <!-- Add other states -->
                </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="bank_name">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter Bank Name">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter Account Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="ifsc">IFSC Code</label>
                <input type="text" name="ifsc" id="ifsc" class="form-control" placeholder="Enter IFSC Code">
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" name="branch" id="branch" class="form-control" placeholder="Enter Branch">
              </div>
            </div>
          </div>
          <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
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
