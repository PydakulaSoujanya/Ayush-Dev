<?php
// Include your database configuration file
include('../config.php');

$query = "
    SELECT sr.id, sr.customer_name, sr.contact_no, cm.id,cm.patient_name
    FROM service_requests sr
    JOIN customer_master_new cm ON sr.customer_id = cm.id
";

$customer_result = $conn->query($query);

if (!$customer_result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Refunds</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dywxE7Dbauy0ZdO9IMIAgFbKk8c0Lx0nvW0Uj+ks9qqRhj2uP/zLwsiXccCD9dQrcxJjpHZB5Q72n11KH4cOZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="../assets/css/style.css">
  
</head>

<style>
.suggestions-box {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    background: #fff;
    z-index: 1000;
    width: 100%;
    padding: 5px;
    margin-top: 5px;
}

.suggestion-item {
    padding: 10px;
    cursor: pointer;
}

.suggestion-item:hover {
    background: #f0f0f0;
}

  </style>
<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Refunds</h3>
  <form action="expenses_db.php" method="POST" enctype="multipart/form-data">
  <div class="row form-section form-first-row">
            <h2 class="section-title1">Customer Refunds</h2>
            <div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
    <div class="input-field-container">
    <label class="input-label">Select Customer</label>
    <input
        type="text"
        id="customer_search"
        name="customer_search"
        class="styled-input"
        placeholder="Search by Name or Mobile Number"
        onkeyup="searchEmployee(this.value)"
        autocomplete="off"
        required
    />
    <div id="employee_suggestions" class="suggestions-box" style="display: none;"></div>
</div>

<!-- Hidden fields to hold the selected customer's ID and Name -->
<input type="hidden" id="entity_id" name="entity_id" placeholder="Customer ID" style="width: 100%; margin-top: 10px;" readonly required>
<input type="hidden" id="entity_name" name="entity_name" placeholder="Customer Name" style="width: 100%; margin-top: 10px;" readonly required>

<script>
function updateCustomerFields() {
    // Get the dropdown element
    const dropdown = document.getElementById("customerSelect");
    
    // Get the selected option
    const selectedOption = dropdown.options[dropdown.selectedIndex];
    
    // Get the customer ID and name from the selected option
    const customerId = selectedOption.value; // ID is the option value
    const customerName = selectedOption.getAttribute("data-name"); // Name is in a data attribute
    
    // Set the values in the respective text input fields
    document.getElementById("entity_id").value = customerId;
    document.getElementById("entity_name").value = customerName;
}
</script>


</div>

      <!-- Expense Date -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="input-field-container">
          <label class="input-label">Expense Date</label>
          <input type="date" class="styled-input" name="expense_date" required />
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="input-field-container">
          <label class="input-label">Amount to be Paid</label>
          <input type="number" class="styled-input" name="amount_to_be_paid" placeholder="Enter Amount to be Paid" required />
        </div>
      </div>
    </div>

   
      <!-- Amount to be Paid -->
    

      
      <div class="row">
      <!-- Status -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
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
       
       <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <textarea class="styled-input" name="description" placeholder="Describe the expense" required></textarea>
        </div>
      </div>
    </div>

    <input type="hidden" name="expense_type" value="Refunds">
  

 

    </div>

    

    <div class="row emp-submit mt-2">
    
      <div class="col-md-12 text-center">
        <button type="submit" class="btn" name="submit" value="Submit">Submit</button>
      </div>
    </div>
  </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
function searchEmployee(query) {
    if (query.length > 2) { // Start searching after 3 characters
        $.ajax({
            url: 'search_customer.php',
            type: 'GET',
            data: { query: query },
            success: function(data) {
                const suggestionsBox = document.getElementById('employee_suggestions');
                const results = JSON.parse(data);

                if (results.length > 0) {
                    let suggestionsHTML = '';
                    results.forEach(customer => {
                        suggestionsHTML += `
                            <div 
                                class="suggestion-item" 
                                onclick="selectCustomer(${customer.id}, '${customer.patient_name}', '${customer.emergency_contact_number}')"
                            >
                                ${customer.patient_name} (${customer.id}) - ${customer.emergency_contact_number}
                            </div>`;
                    });
                    suggestionsBox.innerHTML = suggestionsHTML;
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.innerHTML = '<div class="suggestion-item">No results found</div>';
                    suggestionsBox.style.display = 'block';
                }
            },
            error: function() {
                console.error('Error fetching customer data');
            }
        });
    } else {
        document.getElementById('employee_suggestions').style.display = 'none';
    }
}

function selectCustomer(id, name, phone) {
    // Update the fields with selected customer data
    document.getElementById('entity_id').value = id;
    document.getElementById('entity_name').value = name;
    document.getElementById('customer_search').value = `${name} - ${phone}`; // Optional: show in search input
    document.getElementById('employee_suggestions').style.display = 'none';
}
</script>


</body>
</html>
