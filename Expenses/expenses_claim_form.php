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

<style>
  .suggestions-box {
  position: absolute;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  width: 95%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.suggestion-item {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.suggestion-item:last-child {
  border-bottom: none;
}

.suggestion-item:hover {
  background: #f1f1f1;
  cursor: pointer;
}
</style>

<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  <div class="card custom-card">
    <div class="card-header custom-card-header">Employee Expenses Claim</div>
    <div class="card-body">
      <form action="employee_claims_db.php" method="POST" enctype="multipart/form-data">
        <div class="row mt-3">
          <!-- Employee Name -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Search Employee</label>
              <input
                type="text"
                id="employee_search"
                name="employee_search"
                class="form-control"
                placeholder="Search by Name or Mobile Number"
                onkeyup="searchEmployee(this.value)"
                autocomplete="off"
                required
              />
              <div id="employee_suggestions" class="suggestions-box" style="display: none;"></div>
              <!-- Hidden fields for selected employee details -->
              <input type="hidden" id="entity_id" name="entity_id" placeholder="Employee ID" readonly required />
              <input type="hidden" id="entity_name" name="entity_name" placeholder="Employee Name" readonly required />
            </div>
          </div>

          <!-- Expense Date -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Expense Date</label>
              <input type="date" class="form-control" name="expense_date" id="expense_date" required />
            </div>
          </div>

          <!-- Paying Account -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Paying Account</label>
              <select class="form-control" id="bank_account" name="bank_account" required>
                <option value="" disabled selected>Select Account</option>
                <?php
                if ($result->num_rows > 0) {
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
        </div>

        <div class="row mt-3">
          <!-- Description -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Description</label>
              <input class="form-control" name="description" placeholder="Describe the expense" required />
            </div>
          </div>

          <!-- Amount Claimed -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Amount Claimed</label>
              <input type="number" class="form-control" name="amount_claimed" placeholder="Enter Amount Claimed" required />
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-4 col-lg-4">
            <div class="form-group">
              <label class="input-label">Status</label>
              <input type="text" class="form-control" name="status" value="Paid" readonly required />
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
        </div>
        <input type="hidden" name="expense_type" value="Employee Expense Claim" />
      </form>
    </div>
  </div>
</div>

<script>
  function updateEmployeeFields() {
    const dropdown = document.getElementById("employee_name_dropdown");
    const selectedOption = dropdown.options[dropdown.selectedIndex];
    const employeeId = selectedOption.value;
    const employeeName = selectedOption.getAttribute("data-name");
    document.getElementById("entity_id").value = employeeId;
    document.getElementById("entity_name").value = employeeName;
  }
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    // Hide all date fields initially
    $('input[name="submitted_date"]').closest('.col-md-4').hide();
    $('input[name="approved_date"]').closest('.col-md-4').hide();
    $('input[name="payment_date"]').closest('.col-md-4').hide();

    // Monitor changes to the status dropdown
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
<!-- <script>
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
</script> -->
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

<script>
  // Set the current date as default
  document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split("T")[0]; // Get today's date in YYYY-MM-DD format
    document.getElementById("expense_date").value = today;
  });
</script>

</body>
</html>