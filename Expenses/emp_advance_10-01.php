<?php

include('../config.php'); 


$employee_query = "SELECT id, name, phone FROM emp_info";
$employee_result = mysqli_query($conn, $employee_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Advance</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dywxE7Dbauy0ZdO9IMIAgFbKk8c0Lx0nvW0Uj+ks9qqRhj2uP/zLwsiXccCD9dQrcxJjpHZB5Q72n11KH4cOZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="../assets/css/style.css">
  
</head>

<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Employee Advance Paid</h3>
  <form action="expenses_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
    
    <!-- Employee Name -->
    <div class="col-md-4">
  <!-- <div class="input-field-container">
    <label class="input-label">Select Employee</label>
   
<select class="styled-input" id="employee_name_dropdown" name="employee_name_dropdown" style="width: 100%;" onchange="updateEmployeeFields()" required>
  <option value="" disabled selected>Select Employee</option>
  <?php
  while ($row = mysqli_fetch_assoc($employee_result)) {
      
      echo "<option value='{$row['id']}' data-name='{$row['name']}'>{$row['id']} {$row['name']} ({$row['phone']})</option>";
  }
  ?>
</select> -->

<div class="input-field-container">
    <label class="input-label">Search Employee</label>
    <input
      type="text"
      id="employee_search"
      name="employee_search"
      class="styled-input"
      placeholder="Search by Name or Mobile Number"
      onkeyup="searchEmployee(this.value)"
      autocomplete="off"
      required
    />
    <div id="employee_suggestions" class="suggestions-box" style="display: none;"></div>
<input type="hidden" id="bank_account" name="bank_account" value="Santhosh Sir" style="width: 100%; margin-top: 10px;" readonly required>

<input type="hidden" id="entity_id" name="entity_id" placeholder="Employee ID" style="width: 100%; margin-top: 10px;" readonly required>
<input type="hidden" id="entity_name" name="entity_name" placeholder="Employee Name" style="width: 100%; margin-top: 10px;" readonly required>
  </div>
</div>
    
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Advance Paid Date</label>
          <input type="date" class="styled-input" name="expense_date" required />
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Amount Paid -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Amount Paid</label>
          <input type="number" class="styled-input" name="amount_claimed" placeholder="Enter Amount Paid" required />
        </div>
      </div>
      
      <input type="hidden" name="expense_type" value="Employee Advance Payment">
      <input type="hidden" name="payment_status" value="Paid">
      <input type="hidden" name="status" value="Approved">
      
<div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <textarea class="styled-input" name="description" placeholder="Describe the advance paid" required></textarea>
        </div>
      </div>
    </div>

    <input type="hidden" name="advance_paid_type" value="Employee advance paid Claim">
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


  function searchEmployee(searchTerm) {
  const employee_search = document.getElementById('employee_search');
  const suggestionsBox = document.getElementById('employee_suggestions');
  const entityIdField = document.getElementById('entity_id');
  const entityNameField = document.getElementById('entity_name');

  // Clear previous suggestions
  suggestionsBox.innerHTML = '';
  suggestionsBox.style.display = 'none';

  // Reset hidden fields
  entityIdField.value = '';
  entityNameField.value = '';

  if (searchTerm.trim() === '') return; // Exit if the search term is empty

  // Fetch matching employees from the backend
  fetch(`fetch_employee.php?search=${encodeURIComponent(searchTerm)}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success && data.data.length > 0) {
        data.data.forEach((employee) => {
          const suggestionItem = document.createElement('div');
          suggestionItem.className = 'suggestion-item';
          suggestionItem.textContent = `${employee.name} (${employee.phone})`;

          // Set click handler to populate fields
          suggestionItem.addEventListener('click', () => {
            entityIdField.value = employee.id;
            employee_search.value = `${employee.name}-${employee.phone}`;
            entityNameField.value = employee.name;
            suggestionsBox.style.display = 'none';
          });

          suggestionsBox.appendChild(suggestionItem);
        });
        suggestionsBox.style.display = 'block';
      } else {
        // Display "No results found" if no matches
        const noResults = document.createElement('div');
        noResults.className = 'suggestion-item';
        noResults.textContent = 'No results found';
        suggestionsBox.appendChild(noResults);
        suggestionsBox.style.display = 'block';
      }
    })
    .catch((error) => {
      console.error('Fetch error:', error);
    });
}
</script>

</body>
</html>