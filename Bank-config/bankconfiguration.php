
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bank Account Form</title>
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
      font-weight: bold;
      color: #A26D2B;
    }

    .styled-input, .styled-select {
      width: 100%;
      padding: 10px;
      font-size: 12px;
      outline: none;
      box-sizing: border-box;
      border: 1px solid #A26D2B;
      border-radius: 5px;
    }

    .styled-input:focus, .styled-select:focus {
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
  <h3 class="mb-4">Bank Account Form</h3>
  <form action="account_configdb.php" method="POST">
    <div class="row">
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">Account Name</label>
          <input type="text" id="account_name" name="account_name" class="styled-input" placeholder="Account Name" required />
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">Bank Account No</label>
          <input type="text" id="bank_account_no" name="bank_account_no" class="styled-input" placeholder="Employee's Bank Account Number" required />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">IFSC Code</label>
          <input type="text" id="ifsc_code" name="ifsc_code" class="styled-input" placeholder="IFSC Code of the Bank" required />
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">Bank Name</label>
          <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Name of the Bank" required />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">Account Type</label>
          <select id="account_type" name="account_type" class="styled-select" required>
            <option value="" disabled selected>Select Account Type</option>
            <option value="saving">Saving</option>
            <option value="current">Current</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-field-container">
          <label class="input-label">Status</label>
          <select id="status" name="status" class="styled-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
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
