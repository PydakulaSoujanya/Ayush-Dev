<?php

include('../config.php'); // Ensure this includes the database connection logic


$vendor_query = "SELECT `id`, `vendor_name`, `phone_number`, `email`, `vendor_type` FROM `vendors`";
$vendor_result = mysqli_query($conn, $vendor_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Utility Expenses Claim</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dywxE7Dbauy0ZdO9IMIAgFbKk8c0Lx0nvW0Uj+ks9qqRhj2uP/zLwsiXccCD9dQrcxJjpHZB5Q72n11KH4cOZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="../assets/css/style.css">
  
</head>

<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Utility Expenses Claim</h3>
  <form action="expenses_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
    
    <div class="col-md-4">
  <div class="input-field-container">
    <label class="input-label">Select Vendor</label>
    <!-- Dropdown for selecting a vendor -->
    <select class="styled-input" id="vendor_name_dropdown" name="vendor_name_dropdown" style="width: 100%;" onchange="updateVendorFields()" required>
      <option value="" disabled selected>Select Vendor</option>
      <?php
      while ($row = mysqli_fetch_assoc($vendor_result)) {
          // Embed both ID and Name in the option's data attributes
          echo "<option value='{$row['id']}' data-name='{$row['vendor_name']}' data-phone='{$row['phone_number']}'>{$row['vendor_name']} ({$row['phone_number']})</option>";
      }
      ?>
    </select>

    <!-- Text input field for Vendor ID -->
    <input type="text" id="entity_id" name="entity_id" placeholder="Vendor ID" style="width: 100%; margin-top: 10px;" hidden required>

    <!-- Text input field for Vendor Name -->
    <input type="text" id="entity_name" name="entity_name" placeholder="Vendor Name" style="width: 100%; margin-top: 10px;" hidden required>

    
  </div>
</div>

<!-- JavaScript to auto-fill both text inputs -->
<script>
function updateVendorFields() {
  // Get the dropdown element
  const dropdown = document.getElementById("vendor_name_dropdown");
  
  // Get the selected option
  const selectedOption = dropdown.options[dropdown.selectedIndex];
  
  // Get the vendor ID, name, and phone from the selected option
  const vendorId = selectedOption.value; // ID is the option value
  const vendorName = selectedOption.getAttribute("data-name"); // Name is in a data attribute
 
  
  // Set the values in the respective text input fields
  document.getElementById("entity_id").value = vendorId;
  document.getElementById("entity_name").value = vendorName;
 
}
</script>
  



      

      <!-- Expense Date -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Expense Date</label>
          <input type="date" class="styled-input" name="expense_date" required />
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Amount to be Paid -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Amount to be Paid</label>
          <input type="number" class="styled-input" name="amount_to_be_paid" placeholder="Enter Amount to be Paid" required />
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
            <option value="Rejected">Rejected</option>
           
          </select>
        </div>
      </div>
       
<div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <textarea class="styled-input" name="description" placeholder="Describe the expense" required></textarea>
        </div>
      </div>
    </div>

    <input type="hidden" name="expense_type" value="Employee Expense Claim">
  

 

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
