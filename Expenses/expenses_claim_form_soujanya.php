<?php

include('../config.php'); // Ensure this includes the database connection logic

// Fetch employee data for the dropdown
$employee_query = "SELECT id, name, phone FROM emp_info";
$employee_result = mysqli_query($conn, $employee_query);

$sql = "SELECT account_name FROM account_config"; // Adjust the table and column names
$result = $conn->query($sql);
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
  
</head>

<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Employee Expenses Claim</h3>
  <form action="employee_claims_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
    
    <!-- Employee Name -->
    <div class="col-md-4">
  <div class="input-field-container">
    <label class="input-label">Select Employee</label>
   <!-- Dropdown for selecting an employee -->
<select class="styled-input" id="employee_name_dropdown" name="employee_name_dropdown" style="width: 100%;" onchange="updateEmployeeFields()" required>
  <option value="" disabled selected>Select Employee</option>
  <?php
  while ($row = mysqli_fetch_assoc($employee_result)) {
      // Embed both ID and Name in the option's data attributes
      echo "<option value='{$row['id']}' data-name='{$row['name']}'>{$row['id']} {$row['name']} ({$row['phone']})</option>";
  }      
  ?>
</select>
<!-- <input type="text" id="bank_account" name="bank_account" value="Santhosh Sir" style="width: 100%; margin-top: 10px;" readonly required> -->


    
<input type="text" id="entity_id" name="entity_id" placeholder="Employee ID" style="width: 100%; margin-top: 10px;" hidden required>


<input type="text" id="entity_name" name="entity_name" placeholder="Employee Name" style="width: 100%; margin-top: 10px;" hidden required>

<!-- JavaScript to auto-fill both text inputs -->
<script>
function updateEmployeeFields() {
  // Get the dropdown element
  const dropdown = document.getElementById("employee_name_dropdown");
  
  // Get the selected option
  const selectedOption = dropdown.options[dropdown.selectedIndex];
  
  // Get the employee ID and name from the selected option
  const employeeId = selectedOption.value; // ID is the option value
  const employeeName = selectedOption.getAttribute("data-name"); // Name is in a data attribute
  
  // Set the values in the respective text input fields
  document.getElementById("entity_id").value = employeeId;
  document.getElementById("entity_name").value = employeeName;
}
</script>

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

<div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Paying Account</label>
    <!-- <label class="input-label">Select Account</label> -->
    <select class="styled-input" id="bank_account" name="bank_account" style="width: 100%; margin-top: 10px;" required>
    <option value="" disabled selected>Select Account</option>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['account_name']) . "'>" . htmlspecialchars($row['account_name']) . "</option>";
        }
    } else {
        echo "<option value='' disabled>No accounts available</option>";
    }
    ?>
</select>

</div>
      </div>
    
    <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <input class="styled-input" name="description" placeholder="Describe the expense" required></input>

          <!-- <textarea class="styled-input" name="description" placeholder="Describe the expense" required></textarea> -->
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
       


    <input type="hidden" name="expense_type" value="Employee Expense Claim">
  

 

    </div>

    

    <div class="row form-submit emp-submit mt-2">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn w-100">Submit</button>
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
