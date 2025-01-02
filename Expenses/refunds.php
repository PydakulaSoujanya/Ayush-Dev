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

<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  
  <h3 class="mb-4">Refunds</h3>
  <form action="expenses_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
    
    <div class="col-md-4">
    <div class="input-field-container">
    <label class="input-label">Select Customer</label>
   
    <select class="styled-input" id="customerSelect" onchange="updateCustomerFields()">
        <option value="">Select Customer</option>
        <?php while ($row = mysqli_fetch_assoc($customer_result)) { ?>
            <option value="<?php echo $row['id']; ?>" data-name="<?php echo $row['customer_name']; ?>" data-phone="<?php echo $row['contact_no']; ?>">
                <?php echo $row['customer_name']; ?> - <?php echo $row['contact_no']; ?>
            </option>
        <?php } ?>
    </select>
    
    <input type="text" id="entity_id" name="entity_id" placeholder="Customer ID" style="width: 100%; margin-top: 10px;" required>
    
    <input type="text" id="entity_name" name="entity_name" placeholder="Customer Name" style="width: 100%; margin-top: 10px;" required>
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

    
    
  
  <!-- <div class="col-md-4">
  <div class="input-field-container">
    <label class="input-label">Percentage</label>
    <select class="styled-input" id="percentage_dropdown" name="percentage_dropdown" style="width: 100%;" required>
      <option value="" disabled selected>Select Percentage</option>
      <option value="5">5%</option>
      <option value="10">10%</option>
      <option value="15">15%</option>
      <option value="20">20%</option>
      <option value="25">25%</option>
    </select>
  </div>
</div> -->
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

    <input type="hidden" name="expense_type" value="Refunds">
  

 

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






</body>
</html>
