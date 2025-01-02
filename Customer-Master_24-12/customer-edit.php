<?php
// Include database connection
include('../config.php');

// Check if we are editing an existing record
$editMode = isset($_GET['id']); // or use $_POST if you're passing data in another way
$customerId = $editMode ? $_GET['id'] : null; // Retrieve ID if editing

// Initialize form variables with empty strings
$customerData = [
    'id' => '',
    'patient_status' => '',
    'patient_name' => '',
    'relationship' => '',
    'full_name' => '',
    'emergency_contact_number' => '',
    'date_of_joining' => '',
    'blood_group' => '',
    'medical_conditions' => '',
    'email' => '',
    'patient_age' => '',
    'gender' => '',
    'care_requirements' => '',
    'care_aadhar' => '',
    'mobility_status' => '',
    'discharge' => '',
    'address' => ''
];

// Fetch existing data for edit if customer ID is provided
if ($editMode) {
    $query = "SELECT * FROM customer_master WHERE id = $customerId";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $customerData = mysqli_fetch_assoc($result);
    } else {
        echo "Customer not found!";
        exit;
    }
}
?>
<?php
// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $patientStatus = $_POST['patient_status'];
    $patientName = $_POST['patient_name'];
    $relationship = $_POST['relationship'];
    $fullName = $_POST['full_name'];
    $emergencyContactNumber = $_POST['emergency_contact_number'];

    // Additional fields here...

    // Update query
    $updateQuery = "UPDATE customer_master SET 
                    patient_status = '$patientStatus',
                    patient_name = '$patientName',
                    relationship = '$relationship',
                    full_name = '$fullName',
                    emergency_contact_number = '$emergencyContactNumber'
                    WHERE id = $id";

if (mysqli_query($conn, $updateQuery)) {
    // Display alert and redirect
    echo "<script>
            alert('Customer details updated successfully!');
            window.location.href = 'customer_table.php'; // Redirect to the customer_table.php
          </script>";
} else {
    // Display error alert and redirect
    echo "<script>
            alert('Error updating customer details: " . mysqli_error($conn) . "');
            window.location.href = 'customer_table.php'; // Redirect to the customer_table.php
          </script>";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Details Form</title>
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

    .hidden {
      display: none;
    }

    h3 {
      color: #A26D2B;
    }

    .dropdowncolmns {
      margin-left: 0px;
      width: 163%;
    }
  </style> -->
</head>
<body>

<?php include('../navbar.php'); ?>


<div class="container mt-7">
  <h3 class="mb-4"> Edit Customer Details Form</h3>
  
  <form action="customer_db.php" method="POST" enctype="multipart/form-data">
  <!-- <div class="row"> -->
  <div class="row equal-width">
  <!-- Are you a patient? -->
  <div class="col-md-4">
    <div class="input-field-container">
      <label class="input-label">Are you a patient?</label>
      <select class="styled-input" id="patientStatus" name="patient_status" required>
        <option value="" disabled selected>Select an option</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
      </select>
    </div>
  </div>

  <!-- Patient Name -->
  <div class="col-md-4 hidden" id="patientNameField">
    <div class="input-field-container">
      <label class="input-label">Patient Name</label>
      <input type="text" class="styled-input" name="patient_name" placeholder="Enter patient name" />
    </div>
  </div>

  <!-- Relationship with Patient -->
  <div class="col-md-4 hidden" id="relationshipField">
    <div class="input-field-container">
      <label class="input-label" for="relationship">Relationship with Patient</label>
      <select class="styled-input" id="relationship" name="relationship">
        <option value="" disabled selected>Select relationship</option>
        <option value="parent">Parent</option>
        <option value="sibling">Sibling</option>
        <option value="spouse">Spouse</option>
        <option value="child">Child</option>
        <option value="friend">Friend</option>
        <option value="guardian">Guardian</option>
        <option value="grandchild">Grand child</option>
        <option value="other">Other</option>
      </select>
    </div>
  </div>
</div>

<!-- </div> -->

    <div class="row">
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Customer Name</label>
          <input type="text" class="styled-input" name="full_name" placeholder="Enter your name" required />
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Contact Number</label>
          <input type="text" class="styled-input" name="emergency_contact_number" placeholder="Enter your emergency contact number" required />
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Blood Group</label>
          <select class="styled-input" name="blood_group" required>
            <option value="" disabled selected>Select blood group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
     

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Known Medical Conditions</label>
          <input type="text" class="styled-input" name="medical_conditions" placeholder="Enter known medical conditions" required />
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Email</label>
          <input type="email" class="styled-input" name="email" placeholder="Enter your email" required />
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Patient Age</label>
          <input type="number" class="styled-input" name="patient_age" placeholder="Enter patient age" />
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Gender</label>
          <select class="styled-input" name="gender">
            <option value="" disabled selected>Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>

      <!-- <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Service Types</label>
          <select class="styled-input" name="care_requirements" required>
            <option value="" disabled selected>Select Service Type</option>
            <option value="fully-trained-nurse">Fully Trained Nurse</option>
            <option value="semi-trained-nurse">Semi-Trained Nurse</option>
            <option value="caretaker">Caretaker</option>
            <option value="caretaker">Nanees</option>
          </select>
        </div>
      </div> -->
<!-- 
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Care Aadhar Upload</label>
          <input type="file" class="styled-input" name="care_aadhar" accept=".pdf,.jpeg,.jpg,.png" required />
        </div>
      </div> -->

      <!-- <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Created At</label>
          <input type="date" class="styled-input" name="created_at" required />
        </div>
      </div> -->

      <!-- <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Updated At</label>
          <input type="date" class="styled-input" name="updated_at" required />
        </div>
      </div> -->

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Mobility Status</label>
          <select class="styled-input" name="mobility_status" required>
            <option value="" disabled selected>Select Mobility Status</option>
            <option value="Walking">Walking</option>
            <option value="Wheelchair">Wheelchair</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Discharge Summary Sheet</label>
          <input type="file" class="styled-input" name="discharge" accept=".pdf,.doc,.docx,.txt" />
        </div>
      </div>

      <!-- <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Upload Form</label>
          <input type="file" class="styled-input" name="upload_form" accept=".pdf,.doc,.docx,.txt" />
        </div>
      </div> -->

      <div class="col-md-4">
    <div class="input-field-container">
        <label class="input-label">Address</label>
        <textarea class="styled-input" name="address" placeholder="Enter address"></textarea>
    </div>
</div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?= $editMode ? 'Update' : 'Submit' ?></button>
        </div>
    </div>
  </form>
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


