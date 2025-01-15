<?php
// Include database configuration
include('../config.php');

// Check if we are in edit mode
$editMode = isset($_GET['id']);
$customerId = $editMode ? intval($_GET['id']) : null;

// Initialize form variables for customer and address
$customerData = [
    'id' => '',
    'patient_name' => '',
    'relationship' => '',
    'customer_name' => '',
    'emergency_contact_number' => '',
    'blood_group' => '',
    'medical_conditions' => '',
    'email' => '',
    'patient_age' => '',
    'gender' => '',
    'mobility_status' => '',
    'discharge_summary_sheet' => '',
];

$addressData = [
    'pincode' => '',
    'address_line1' => '',
    'address_line2' => '',
    'landmark' => '',
    'city' => '',
    'state' => '',
];

// Fetch data for edit
if ($editMode) {
    $query = "SELECT cm.*, 
                     ca.pincode, 
                     ca.address_line1, 
                     ca.address_line2, 
                     ca.landmark, 
                     ca.city, 
                     ca.state
              FROM customer_master_new cm
              LEFT JOIN customer_addresses ca ON cm.id = ca.customer_id
              WHERE cm.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Merge address data with customer data if address exists
        $addressData = [
            'pincode' => $data['pincode'] ?? '',
            'address_line1' => $data['address_line1'] ?? '',
            'address_line2' => $data['address_line2'] ?? '',
            'landmark' => $data['landmark'] ?? '',
            'city' => $data['city'] ?? '',
            'state' => $data['state'] ?? '',
        ];
        $customerData = array_merge($customerData, $data);
    } else {
        echo "<script>alert('Customer not found!'); window.location.href = 'customer_table.php';</script>";
        exit;
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch customer and address values from POST request
    $id = intval($_POST['id']);
    $patientName = $_POST['patient_name'] ?? '';
    $relationship = $_POST['relationship'] ?? '';
    $customerName = $_POST['customer_name'] ?? '';
    $emergencyContactNumber = $_POST['emergency_contact_number'] ?? '';
    $bloodGroup = $_POST['blood_group'] ?? '';
    $medicalConditions = $_POST['medical_conditions'] ?? '';
    $email = $_POST['email'] ?? '';
    $patientAge = intval($_POST['patient_age'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    $mobilityStatus = $_POST['mobility_status'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $addressLine1 = $_POST['address_line1'] ?? '';
    $addressLine2 = $_POST['address_line2'] ?? '';
    $landmark = $_POST['landmark'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';

    // Handle file upload
    $dischargeSummarySheet = $_FILES['discharge_summary_sheet']['name'] ?? '';
    if (!empty($dischargeSummarySheet)) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($dischargeSummarySheet);
        move_uploaded_file($_FILES["discharge_summary_sheet"]["tmp_name"], $targetFile);
    } else {
        $dischargeSummarySheet = $customerData['discharge_summary_sheet'];
    }

    // Update customer data
    $updateCustomerQuery = "UPDATE customer_master_new SET 
        patient_name = ?, 
        relationship = ?, 
        customer_name = ?, 
        emergency_contact_number = ?, 
        blood_group = ?, 
        medical_conditions = ?, 
        email = ?, 
        patient_age = ?, 
        gender = ?, 
        mobility_status = ?, 
        discharge_summary_sheet = ?
        WHERE id = ?";
    $stmt = $conn->prepare($updateCustomerQuery);
    $stmt->bind_param(
        'ssssssssssi',
        $patientName,
        $relationship,
        $customerName,
        $emergencyContactNumber,
        $bloodGroup,
        $medicalConditions,
        $email,
        $patientAge,
        $gender,
        $mobilityStatus,
        $dischargeSummarySheet,
        $id
    );

    if ($stmt->execute()) {
        // Check if address exists
        $checkAddressQuery = "SELECT id FROM customer_addresses WHERE customer_id = ?";
        $checkStmt = $conn->prepare($checkAddressQuery);
        $checkStmt->bind_param('i', $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Update existing address
            $updateAddressQuery = "UPDATE customer_addresses SET 
                pincode = ?, 
                address_line1 = ?, 
                address_line2 = ?, 
                landmark = ?, 
                city = ?, 
                state = ?
                WHERE customer_id = ?";
            $addressStmt = $conn->prepare($updateAddressQuery);
            $addressStmt->bind_param(
                'ssssssi',
                $pincode,
                $addressLine1,
                $addressLine2,
                $landmark,
                $city,
                $state,
                $id
            );
        } else {
            // Insert new address
            $insertAddressQuery = "INSERT INTO customer_addresses 
                (customer_id, pincode, address_line1, address_line2, landmark, city, state) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $addressStmt = $conn->prepare($insertAddressQuery);
            $addressStmt->bind_param(
                'issssss',
                $id,
                $pincode,
                $addressLine1,
                $addressLine2,
                $landmark,
                $city,
                $state
            );
        }

        if ($addressStmt->execute()) {
            echo "<script>alert('Customer details updated successfully!'); window.location.href = 'customer_table.php';</script>";
        } else {
            echo "<script>alert('Error updating address details: " . $addressStmt->error . "');</script>";
        }
        $addressStmt->close();
        $checkStmt->close();
    } else {
        echo "<script>alert('Error updating customer details: " . $stmt->error . "');</script>";
    }
    $stmt->close();
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
  
</head>
<style>


</style>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<?php include('../navbar.php'); ?>

<div class="container mt-9">
 
<div class="card custom-card">
<div class="card-header custom-card-header">Edit Customer</div>
<div class="card-body">
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($customerData['id']); ?>" />
    <div class="row ">
 
 <div class="row">
 <div class="col-12 col-sm-6 col-md-3 col-lg-4 mt-3">
  <div class="form-group">
    <label class="input-label">Are you a patient?</label>
    <select class="form-control" name="patient_status_display" disabled>
      <option value="" disabled>Select an option</option>
      <option value="yes" <?= $customerData['patient_status'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
      <option value="no" <?= $customerData['patient_status'] === 'no' ? 'selected' : ''; ?>>No</option>
    </select>
    <!-- Hidden input to retain the value for form submission -->
    <input type="hidden" name="patient_status" value="<?= $customerData['patient_status']; ?>">
  </div>
</div>


      <div class="col-12 col-sm-6 col-md-3 col-lg-4 mt-3 hidden" id="patientNameField">
        <div class="form-group">
          <label class="input-label">Patient Name</label>
          <input type="text" class="form-control" name="patient_name" readonly placeholder="Enter patient name" value="<?= htmlspecialchars($customerData['patient_name']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Relationship with Patient</label>
          <select class="form-control" name="relationship">
            <option value="" disabled>Select relationship</option>
            <option value="parent" <?= $customerData['relationship'] === 'parent' ? 'selected' : ''; ?>>Parent</option>
            <option value="sibling" <?= $customerData['relationship'] === 'sibling' ? 'selected' : ''; ?>>Sibling</option>
            <option value="spouse" <?= $customerData['relationship'] === 'spouse' ? 'selected' : ''; ?>>Spouse</option>
            <option value="child" <?= $customerData['relationship'] === 'child' ? 'selected' : ''; ?>>Child</option>
            <option value="friend" <?= $customerData['relationship'] === 'friend' ? 'selected' : ''; ?>>Friend</option>
            <option value="guardian" <?= $customerData['relationship'] === 'guardian' ? 'selected' : ''; ?>>Guardian</option>
          </select>
        </div>
      </div>
    </div>
    </div>

    <div class="row ">
  <div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Customer Name</label>
          <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name" value="<?= htmlspecialchars($customerData['customer_name']); ?>" required />
        </div>
      </div>



      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Contact Number</label>
          <input type="text" class="form-control" name="emergency_contact_number" placeholder="Enter contact number" value="<?= htmlspecialchars($customerData['emergency_contact_number']); ?>" required />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Blood Group</label>
          <select class="form-control" name="blood_group" required>
            <option value="" disabled>Select blood group</option>
            <option value="A+" <?= $customerData['blood_group'] === 'A+' ? 'selected' : ''; ?>>A+</option>
            <option value="A-" <?= $customerData['blood_group'] === 'A-' ? 'selected' : ''; ?>>A-</option>
            <option value="B+" <?= $customerData['blood_group'] === 'B+' ? 'selected' : ''; ?>>B+</option>
            <option value="B-" <?= $customerData['blood_group'] === 'B+' ? 'selected' : ''; ?>>B-</option>
            <option value="O+" <?= $customerData['blood_group'] === 'O+' ? 'selected' : ''; ?>>O+</option>
            <option value="O-"  <?= $customerData['blood_group'] === 'O-' ? 'selected' : ''; ?>>O-</option>
            <option value="AB+" <?= $customerData['blood_group'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
            <option value="AB-"  <?= $customerData['blood_group'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
          </select>
        </div>
      </div>
 

   
      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Known Medical Conditions</label>
          <input type="text" class="form-control" name="medical_conditions" placeholder="Enter medical conditions" value="<?= htmlspecialchars($customerData['medical_conditions']); ?>" />
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Email</label>
          <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?= htmlspecialchars($customerData['email']); ?>" />
        </div>
      </div>
     

      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Patient Age</label>
          <input type="number" class="form-control" name="patient_age" placeholder="Enter patient age" value="<?= htmlspecialchars($customerData['patient_age']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
        <div class="form-group">
          <label class="input-label">Gender</label>
          <select class="form-control" name="gender" required>
            <option value="" disabled>Select gender</option>
            <option value="male" <?= $customerData['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?= $customerData['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
            <option value="other" <?= $customerData['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
          </select>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Mobility Status</label>
          <select class="form-control" name="mobility_status">
            <option value="" disabled>Select mobility status</option>
            <option value="walking" <?= $customerData['mobility_status'] === 'walking' ? 'selected' : ''; ?>>Walking</option>
            <option value="wheelchair" <?= $customerData['mobility_status'] === 'wheelchair' ? 'selected' : ''; ?>>Wheelchair</option>
          </select>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Discharge Summary Sheet</label>
          <input type="file" class="form-control" name="discharge_summary_sheet" />
          <small>Current File: <?= htmlspecialchars($customerData['discharge_summary_sheet']); ?></small>
        </div>
      </div>
    </div>
    </div>
 

    <!-- Address Row -->
    <div id="address-container">
            <div class="address-entry" id="address-1">
            <div class="row ">
            <div class="row">
      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
                  <div class="form-group">
          <label class="input-label">Pincode</label>
          <input type="text" class="form-control" name="pincode" placeholder="Enter Pincode" value="<?= htmlspecialchars($customerData['pincode']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Flat/House No./Apartment</label>
          <input type="text" class="form-control" name="flat_house_building_apartment" placeholder="Enter Flat/House No./Apartment" value="<?= htmlspecialchars($customerData['address_line2']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Area, Street, Sector</label>
          <input type="text" class="form-control" name="area" placeholder="Enter Area, Street, Sector" value="<?= htmlspecialchars($customerData['address_line1']); ?>" />
        </div>
      </div>

    
      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Landmark</label>
          <input type="text" class="form-control" name="landmark" placeholder="Enter Landmark" value="<?= htmlspecialchars($customerData['landmark']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
        <div class="form-group">
          <label class="input-label">Town/City</label>
          <input type="text" class="form-control" name="town_city" placeholder="Enter Town/City" value="<?= htmlspecialchars($customerData['city']); ?>" />
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
    <div class="form-group">
        <label class="input-label">State</label>
        <select class="form-control" name="state">
            <?php
            // Include the states array (or use states_dropdown.php if it contains the list)
            $states = [
                'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa',
                'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala',
                'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland',
                'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
                'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman and Nicobar Islands',
                'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Lakshadweep', 'Puducherry'
            ];

            // Get the selected state from the database
            $selectedState = $customerData['state'] ?? '';

            // Generate the options dynamically
            foreach ($states as $state) {
                $selected = ($state == $selectedState) ? 'selected' : '';
                echo "<option value='$state' $selected>$state</option>";
            }
            ?>
        </select>
    </div>
</div>

      <div class="col-md-12">
                  <i class="fas fa-plus-square text-success add-more" title="Add More"></i>
                  <i class="fas fa-trash-alt text-danger delete-icon" title="Delete"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-4 text-center">
<div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Update</button>
          </div>

    </div>

      </div>
    </div>
</div>
</div>
  </form>
</div>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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


