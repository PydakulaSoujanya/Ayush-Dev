<?php
// Include database connection
include('config.php');

// Fetch customer details based on the ID passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $customerId = $_GET['id'];
    $query = "SELECT * FROM customer_master WHERE id = $customerId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $customerData = mysqli_fetch_assoc($result);
    } else {
        die("Customer not found!");
    }
} else {
    die("Invalid customer ID!");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Details Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .input-field-container { position: relative; margin-bottom: 15px; }
    .input-label { position: absolute; top: -10px; left: 10px; background-color: white; padding: 0 5px; font-size: 14px; font-weight: bold; color: #A26D2B; }
    .styled-input { width: 100%; padding: 10px; font-size: 12px; outline: none; box-sizing: border-box; border: 1px solid #A26D2B; border-radius: 5px; }
    .styled-input:focus { border-color: #007bff; box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
    .hidden { display: none; }
    h3 { color: #A26D2B; }
  </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-4">
    <h2 class="mb-4">Customer Details</h2>
    <table class="table table-bordered">
       
        <tr><th>Patient Name</th><td><?= $customerData['patient_name'] ?></td></tr>
        <tr><th>Relationship</th><td><?= $customerData['relationship'] ?></td></tr>
        <tr><th>Customer Name</th><td><?= $customerData['customer_name'] ?></td></tr>
        <tr><th>Emergency Contact Number</th><td><?= $customerData['emergency_contact_number'] ?></td></tr>
        
        <tr><th>Blood Group</th><td><?= $customerData['blood_group'] ?></td></tr>
        <tr><th>Medical Conditions</th><td><?= $customerData['medical_conditions'] ?></td></tr>
        <tr><th>Email</th><td><?= $customerData['email'] ?></td></tr>
        <tr><th>Patient Age</th><td><?= $customerData['patient_age'] ?></td></tr>
        <tr><th>Gender</th><td><?= $customerData['gender'] ?></td></tr>
        <tr><th>Care Requirements</th><td><?= $customerData['care_requirements'] ?></td></tr>
       
        <tr><th>Mobility Status</th><td><?= $customerData['mobility_status'] ?></td></tr>
        <tr><th>Address</th><td><?= $customerData['address'] ?></td></tr>
       
           
       
        <tr><th>Discharge</th>
            <td>
                <a href="uploads/<?= $customerData['discharge_summary_sheet'] ?>" target="_blank">View Discharge</a>
            </td>
        </tr>
    </table>
    <a href="customer_table.php" class="btn btn-secondary">Back to List</a>
</div>
<script>
  document.getElementById('patientStatus').addEventListener('change', function () {
    var patientNameField = document.getElementById('patientNameField');
    var relationshipField = document.getElementById('relationshipField');
    if (this.value === 'no') {
      patientNameField.classList.remove('hidden');
      relationshipField.classList.remove('hidden');
    } else {
      patientNameField.classList.add('hidden');
      relationshipField.classList.add('hidden');
    }
  });
</script>
</body>
</html>
