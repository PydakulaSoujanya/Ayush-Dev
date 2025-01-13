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
<div class="card-header custom-card-header">Refunds</div>
<div class="card-body">
  <form action="expenses_db.php" method="POST" enctype="multipart/form-data">
  <div class="row">
            
            <div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-6 mt-3">
    <div class="input-field-container">
  <label class="input-label">Select Customer</label>
  <input
    type="text"
    id="customer_search"
    name="customer_search"
    class="form-control"
    placeholder="Search by Name or Mobile Number"
    onkeyup="searchCustomer(this.value)"
    autocomplete="off"
    required
  />
  <!-- Suggestions box -->
  <div id="customer_suggestions" class="suggestions-box" style="display: none;"></div>

  <input type="hidden" id="entity_id" name="entity_id" required />
  <input type="hidden" id="entity_name" name="entity_name" required />
</div>


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
      <div class="col-12 col-sm-6 col-md-4 col-lg-6 mt-3">
        <div class="form-group">
          <label class="input-label">Expense Date</label>
          <input type="date" class="form-control" name="expense_date" required />
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Amount to be Paid -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-3">
        <div class="form-group">
          <label class="input-label">Amount to be Paid</label>
          <input type="number" class="form-control" name="amount_to_be_paid" placeholder="Enter Amount to be Paid" required />
        </div>
      </div>

      

      <!-- Status -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-3">
        <div class="form-group">
          <label class="input-label">Status</label>
          <select class="form-control" name="status" required>
            <option value="" disabled selected>Select Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
           
          </select>
        </div>
      </div>
       

    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
        <div class="form-group">
          <label class="input-label">Description</label>
          <textarea class="form-control" name="description" placeholder="Describe the expense" required></textarea>
        </div>
      </div>

    <input type="hidden" name="expense_type" value="Refunds">
  

 

    </div>

  
    
    <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
          </div>
    </div>
  </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>



<script>
function searchCustomer(query) {
    if (query.length === 0) {
        document.getElementById('customer_suggestions').style.display = 'none';
        return;
    }

    // Make an AJAX request
    $.ajax({
        url: 'search_customer.php',
        method: 'GET',
        data: { query: query },
        dataType: 'json',
        success: function (data) {
            const suggestionsBox = document.getElementById('customer_suggestions');
            suggestionsBox.innerHTML = '';

            if (data.length > 0) {
                data.forEach((customer) => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('suggestion-item');
                    
                    // Display ID in the dropdown, but not in the input field
                    suggestionItem.textContent = `${customer.patient_name} (${customer.emergency_contact_number}) [ID: ${customer.id}]`;
                    suggestionItem.onclick = () => selectCustomer(customer);

                    suggestionsBox.appendChild(suggestionItem);
                });

                suggestionsBox.style.display = 'block';
            } else {
                suggestionsBox.style.display = 'none';
            }
        },
        error: function () {
            console.error('An error occurred while fetching customer data.');
        }
    });
}

function selectCustomer(customer) {
    // Populate the input field with only the patient name and phone number
    document.getElementById('customer_search').value = `${customer.patient_name} (${customer.emergency_contact_number})`;

    // Populate the hidden fields with the ID and patient name
    document.getElementById('entity_id').value = customer.id;
    document.getElementById('entity_name').value = customer.patient_name;

    // Hide suggestions box
    document.getElementById('customer_suggestions').style.display = 'none';
}

</script>


</body>
</html>
