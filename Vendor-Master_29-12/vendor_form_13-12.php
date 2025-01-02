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
  
  <!-- <style>
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
      font-weight: bold; /* Makes the label bold */
      color: #A26D2B;
    }

    .styled-input {
      width: 100%;
      padding: 10px;
      font-size: 12px;
      outline: none;
      box-sizing: border-box;
      border: 1px solid #A26D2B;
      border-radius: 5px; /* Adds rounded corners to input fields */
    }

    .styled-input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    h1, h2, h3, h4 {
      color: #A26D2B;
    }
  </style> -->
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
  <!-- <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Documents</label>
        <input type="file" name="supporting_documents" id="supporting_documents" class="styled-input" />
      </div>
    </div> -->
  <!-- <div class="col-md-6">
    <div class="input-field-container">
      <label for="supporting_documents" class="input-label">Supporting Documents</label>
      <input type="file" id="supporting_documents" name="supporting_documents[]" class="styled-input" multiple />
    </div>
  </div> -->
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
                            <label for="address" class="input-label">Address</label>
                            <textarea class="styled-input" name="address" id="address" placeholder="Enter address"></textarea>
                        </div>
         
        </div>
   
        <div class="col-md-6">
        <div class="input-field-container">
                            <label for="address" class="input-label">Additional Notes</label>
                            <textarea class="styled-input" name="additional_notes" id="additional_notes" placeholder="Add any Notes"></textarea>
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
  <label class="input-label">Status</label>
  <select id="status" name="status" class="styled-input">
    <option value="Active">Active</option>
    <option value="In Active">In Active</option>
  </select>
</div>
</div>

        <!-- <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Payment Terms</label>
            <input type="text" class="styled-input" id="payment_terms" name="payment_terms" placeholder="Enter payment terms" />
          </div>
        </div> -->
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
