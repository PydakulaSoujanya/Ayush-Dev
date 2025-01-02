<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit;
}

include "../config.php";


// Fetch Active Patients
$sql = "SELECT id, patient_name FROM customer_master ";
$result = $conn->query($sql);

$patients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Styled Form</title>
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
include '../navbar.php';
?>
  <div class="container mt-7">
    <h3 class="mb-4">Invoicing Details</h3>
    <form action="insert_invoicing_data.php" method="POST">
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Customer Name</label>
            <!-- Patient Section -->
<select id="patient_name" name="patient_name" class="styled-input">
    <option value="">Select Customer</option>
    <?php foreach ($patients as $patient): ?>
        <option value="<?= htmlspecialchars($patient['id']); ?>">
            <?= htmlspecialchars($patient['patient_name']); ?>
        </option>
    <?php endforeach; ?>
</select>

            <input type="hidden" id="patient_id" name="patient_id" />
          </div>
        </div>
      <!-- </div> -->
        <!-- <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Customer Name</label>
            <input type="text" name="customer_name" class="styled-input" placeholder="Enter Customer Name" />
          </div>
        </div> -->
        <!-- <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Service ID</label>
            <input type="text" class="styled-input" placeholder="Enter Service ID" />
          </div>
        </div> -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Service Type</label>
            <select class="styled-input" name="service_type">
              <option value="" disabled selected>Select Service Type</option>
              <option value="fully_trained_nurse">Fully Trained Nurse</option>
              <option value="semi_trained_nurse">Semi Trained Nurse</option>
              <option value="care_taker">Care Taker</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
       
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">From Date</label>
            <input type="date" class="styled-input" name="from_date" id="fromDate" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">End Date</label>
            <input type="date" class="styled-input" name="end_date" id="endDate" />
          </div>
        </div>
      </div>
      <div class="row">
        
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Duration (Days)</label>
            <input type="number" class="styled-input" id="durationDays" name="duration" placeholder="Auto-calculated" readonly />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Base Charges</label>
            <input type="number" class="styled-input" name="base_charges" placeholder="Enter Base Charges" />
          </div>
        </div>
      </div>
      <div class="row">
       
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Total Amount</label>
            <input type="number" class="styled-input" name="total_amount" placeholder="Enter Total Amount" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Status</label>
            <select class="styled-input" name="status">
              <option value="" disabled selected>Select Status</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
              <option value="partially_paid">Partially Paid</option>
            </select>
          </div>
        </div>
      </div>
      
      <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
  </div>

  <script>
    // Auto-calculate the duration
    const fromDate = document.getElementById('fromDate');
    const endDate = document.getElementById('endDate');
    const durationDays = document.getElementById('durationDays');

    function calculateDuration() {
      if (fromDate.value && endDate.value) {
        const start = new Date(fromDate.value);
        const end = new Date(endDate.value);
        const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        durationDays.value = diff > 0 ? diff : 0;
      }
    }

    fromDate.addEventListener('change', calculateDuration);
    endDate.addEventListener('change', calculateDuration);
  </script>
</body>
</html>
