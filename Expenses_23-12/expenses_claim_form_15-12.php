<?php

include('../config.php'); // Ensure this includes the database connection logic

// Fetch employee data for the dropdown
$employee_query = "SELECT id, name, phone FROM emp_info";
$employee_result = mysqli_query($conn, $employee_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expenses Claim</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dywxE7Dbauy0ZdO9IMIAgFbKk8c0Lx0nvW0Uj+ks9qqRhj2uP/zLwsiXccCD9dQrcxJjpHZB5Q72n11KH4cOZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    h3 {
      color: #A26D2B;
    }
  </style> -->
</head>

<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Employee Expenses Claim</h3>
  <form action="expenses_claim_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
    
    <!-- Employee Name -->
    <div class="col-md-4">
  <div class="input-field-container">
    <label class="input-label">Select Employee</label>
    <select class="styled-input" id="employee_name" name="employee_name" style="width: 100%;" required>
  <option value="" disabled selected>Select Employee</option>
  <?php
  while ($row = mysqli_fetch_assoc($employee_result)) {
      echo "<option value='{$row['name']}'>{$row['name']} ({$row['phone']})</option>";
  }
  ?>
</select>
  </div>
</div>



      <!-- Expense Category -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Expense Category</label>
          <select class="styled-input" name="expense_category" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Travel">Travel</option>
            <option value="Medical">Medical</option>
            <option value="Food">Food</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      <!-- Expense Date -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Expense Date</label>
          <input type="date" class="styled-input" name="expense_date" required />
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Amount Claimed -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Amount Claimed</label>
          <input type="number" class="styled-input" name="amount_claimed" placeholder="Enter Amount Claimed" required />
        </div>
      </div>

      <!-- Attachment -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Attachment</label>
          <input type="file" class="styled-input" name="attachment" accept=".pdf,.jpeg,.jpg,.png" />
        </div>
      </div>

      <!-- Status -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Status</label>
          <select class="styled-input" name="status" required>
            <option value="" disabled selected>Select Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <!-- <option value="Rejected">Rejected</option> -->
            <option value="Paid">Paid</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
   


      <!-- Submitted Date -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Submitted Date</label>
          <input type="date" class="styled-input" name="submitted_date" required />
        </div>
      </div>

      <!-- Approved Date -->
      <div class="col-md-4">
    <div class="input-field-container">
        <label class="input-label">Approved Date</label>
        <input type="date" class="styled-input" name="approved_date" />
    </div>
    </div>
       <!-- Payment Date -->
       <div class="col-md-4">
    <div class="input-field-container">
        <label class="input-label">Payment Date</label>
        <input type="date" class="styled-input" name="payment_date" />
    </div>
</div>
  <!-- Payment Mode -->
<div class="col-md-4">
  <div class="input-field-container">
    <label class="input-label">Payment Mode</label>
    <select class="styled-input" id="payment_mode" name="payment_mode" required>
      <option value="" disabled selected>Select Payment Mode</option>
      <option value="UPI">UPI</option>
      <option value="Cash">Cash</option>
      <option value="Card">Card</option>
      <option value="Bank Transfer">Bank Transfer</option>
    </select>
  </div>
</div>
 <!-- Transaction ID/Number -->
 <div class="col-md-4 hidden-field" id="transaction_id_container">
  <div class="input-field-container">
    <label class="input-label">Transaction ID/Number</label>
    <input type="text" class="styled-input" name="transaction_id" id="transaction_id" placeholder="Enter Transaction ID/Number" />
  </div>
</div>
<div class="col-md-4 hidden-field" id="card_reference_container">
  <div class="input-field-container">
    <label class="input-label">Reference Number</label>
    <input type="text" class="styled-input" name="card_reference_number" id="card_reference_number" placeholder="Enter Reference Number" />
  </div>
</div>
    </div>

    <div class="row">
      
<!-- Bank Name for Bank Transfer -->
<div class="col-md-4 hidden-field" id="bank_details_container">
  <div class="input-field-container">
    <label class="input-label">Bank Name</label>
    <input type="text" class="styled-input" name="bank_name" id="bank_name" placeholder="Enter Bank Name" />
  </div>
</div>
<div class="col-md-8">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <textarea class="styled-input" name="description" placeholder="Describe the expense" required></textarea>
        </div>
      </div>
    </div>
 

    <div class="row">
    
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
      </div>
    </div>
  </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    // Hide all date fields initially
    $('input[name="submitted_date"]').closest('.col-md-4').hide();
    $('input[name="approved_date"]').closest('.col-md-4').hide();
    $('input[name="payment_date"]').closest('.col-md-4').hide();

    // Monitor changes to the `status` dropdown
    $('select[name="status"]').on('change', function () {
      const selectedStatus = $(this).val();

      // Hide all date fields by default
      $('input[name="submitted_date"]').closest('.col-md-4').hide();
      $('input[name="approved_date"]').closest('.col-md-4').hide();
      $('input[name="payment_date"]').closest('.col-md-4').hide();

      // Show relevant date field based on the selected status
      if (selectedStatus === 'Pending') {
        $('input[name="submitted_date"]').closest('.col-md-4').show();
      } else if (selectedStatus === 'Approved') {
        $('input[name="approved_date"]').closest('.col-md-4').show();
      } else if (selectedStatus === 'Paid') {
        $('input[name="payment_date"]').closest('.col-md-4').show();
      }
    });

    // Trigger change on page load to handle pre-selected status
    $('select[name="status"]').trigger('change');
  });
</script>

<script>
  $(document).ready(function() {
    $('#employee_name').select2({
      placeholder: "Select Employee", // Placeholder text
      allowClear: true                // Allow clearing the selected option
    });
  });
</script>
<script>
  $(document).ready(function () {
    $('#employee_name').select2({
      placeholder: "Select Employee",
      allowClear: true,
      ajax: {
        url: 'fetch_employees.php',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term, // The search term
          };
        },
        processResults: function (data) {
          return {
            results: data.map(function (item) {
              return { id: item.name, text: item.name + ' (' + item.phone + ')' };
            }),
          };
        },
        cache: true,
      },
    });
  });
</script>
<script>
  $(document).ready(function () {
  // Monitor changes to the Payment Mode dropdown
  $('#payment_mode').on('change', function () {
    const selectedMode = $(this).val();

    // Hide all conditional fields by default
    $('.hidden-field').hide();
    $('#transaction_id').val('');
    $('#card_reference_number').val('');
    $('#bank_name').val('');

    // Show relevant fields based on the selected payment mode
    if (selectedMode === 'UPI') {
      $('#transaction_id_container').show();
    } else if (selectedMode === 'Card') {
      $('#card_reference_container').show();
    } else if (selectedMode === 'Bank Transfer') {
      $('#transaction_id_container').show();
      $('#bank_details_container').show();
    }
  });

  // Trigger change on page load to handle pre-selected value
  $('#payment_mode').trigger('change');
});
</script>

</body>
</html>
