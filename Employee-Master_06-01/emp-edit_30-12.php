<?php
session_start();
$alert_message = isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : null;
$alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : null;

// Clear session variables after displaying the alert
unset($_SESSION['alert_message'], $_SESSION['alert_type']);
?>
<?php
include('../config.php');

$employee = [];

// Fetch employee details for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Prepare the SQL query
    $sql = "SELECT * FROM emp_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        if (!$employee) {
            echo "Employee not found.";
            exit;
        }
    } else {
        echo "Error fetching employee data.";
        exit;
    }
    $stmt->close();
} 

// Update employee details for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $employee_id = $_POST['id'];

    // Sanitize and collect input data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $doj = $_POST['doj'];
    $aadhar = $_POST['aadhar'];
    $police_verification = $_POST['police_verification'];
    $daily_rate8 = $_POST['daily_rate8'];
    $daily_rate12 = $_POST['daily_rate12'];
    $daily_rate24 = $_POST['daily_rate24'];
    $reference = $_POST['reference'];
    $beneficiary_name = $_POST['beneficiary_name'];
    $status = $_POST['status'];
    $termination_date = $_POST['termination_date'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $address = $_POST['address'];

    // Handle file upload if provided
    $document = null;
    if (!empty($_FILES['document']['name'])) {
        $target_dir = "uploads/";
        $document = $target_dir . basename($_FILES['document']['name']);
        move_uploaded_file($_FILES['document']['tmp_name'], $document);
    } else {
        // If no new document is uploaded, retain the old document
        $document = $employee['document']; // Retain the old document if no new file is uploaded
    }

    // Prepare the update query
    $sql = "UPDATE emp_info SET name=?, dob=?, gender=?, phone=?, email=?, role=?, qualification=?, 
            experience=?, doj=?, aadhar=?, police_verification=?, daily_rate=?, status=?, 
            termination_date=?, bank_name=?, bank_account_no=?, ifsc_code=?, address=?, document=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssi",
        $name,
        $dob,
        $gender,
        $phone,
        $email,
        $role,
        $qualification,
        $experience,
        $doj,
        $aadhar,
        $police_verification,
        $daily_rate,
        $status,
        $termination_date,
        $bank_name,
        $bank_account_no,
        $ifsc_code,
        $address,
        $document,
        $employee_id
    );

    if ($stmt->execute()) {
        echo "Employee updated successfully.";
        header("Location: employee_list.php"); // Redirect to list page after update
        exit;
    } else {
        echo "Error updating employee.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Form</title>
 
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="path/to/fontawesome/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">

  
</head>
<body>
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <h3 class="mb-4">Employee Form</h3>
    <!-- <form method="POST" id="employee_registartion" enctype="multipart/form-data" action="empdb.php"> -->
    <!-- Row 1 -->
    <form action="update_emp.php" method="POST" enctype="multipart/form-data">
    <!-- Hidden ID Field -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']); ?>">
  <div class="row">
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Name</label>
        <input type="text" name="name" class="styled-input" placeholder="Enter your name"   value="<?= htmlspecialchars($employee['name']); ?>"/>
      </div>
    </div>
    <div class="col-md-3">
      <div class="input-field-container">
      <label class="input-label">Date of Birth</label>
            <input type="date" name="dob" class="styled-input date-input"   value="<?= htmlspecialchars($employee['dob']); ?>" />
            <!-- <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" > -->
      </div>
    </div>
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Gender</label>
        <select name="gender" class="styled-input">
                <option value="Male" <?= $employee['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $employee['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?= $employee['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
      </div>
    </div>
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Phone Number</label>
        <input type="tel" name="phone" class="styled-input" placeholder="Enter phone number" pattern="[0-9]{10}" value="<?= htmlspecialchars($employee['phone']); ?>" />
      </div>
    </div>
  </div>

  <!-- Row 2 -->
  <div class="row">
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Email</label>
        <input type="email" name="email" class="styled-input" placeholder="Enter email"  value="<?= htmlspecialchars($employee['email']); ?>" />
      </div>
    </div>
    <div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label">Role</label>
    <select name="role" class="styled-input">
                <option value="care_taker" <?= $employee['role'] == 'care_taker' ? 'selected' : ''; ?>>Care Taker</option>
                <option value="nanny" <?= $employee['role'] == 'nanny' ? 'selected' : ''; ?>>Nanny</option>
                <option value="fully_trained_nurse" <?= $employee['role'] == 'fully_trained_nurse' ? 'selected' : ''; ?>>Fully Trained Nurse</option>
                <option value="semi_trained_nurse" <?= $employee['role'] == 'semi_trained_nurse' ? 'selected' : ''; ?>>Semi Trained Nurse</option>
            </select>
  
   
  </div>
</div>


    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Qualification</label>
        <select name="qualification" class="styled-input">
                <option value="10th" <?= $employee['qualification'] == '10th' ? 'selected' : ''; ?>>10th</option>
                <option value="intermediate" <?= $employee['qualification'] == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                <option value="degree" <?= $employee['qualification'] == 'degree' ? 'selected' : ''; ?>>Degree</option>
                <option value="pg" <?= $employee['qualification'] == 'pg' ? 'selected' : ''; ?>>PG</option>
            </select>
     
      </div>
    </div>
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Experience</label>
        <select name="experience" class="styled-input">
                <option value="0-1" <?= $employee['experience'] == '0-1' ? 'selected' : ''; ?>>0 to 1 year</option>
                <option value="2-3" <?= $employee['experience'] == '2-3' ? 'selected' : ''; ?>>2 to 3 years</option>
                <option value="4-5" <?= $employee['experience'] == '4-5' ? 'selected' : ''; ?>>4 to 5 years</option>
                <option value="above 5" <?= $employee['experience'] == 'above 5' ? 'selected' : ''; ?>>above 5 years</option>
            </select>
        
      </div>
    </div>
  </div>

  <!-- Row 3 -->
  <div class="row">
  <div class="col-md-3">
    <div class="input-field-container">
        <label class="input-label">Date of Joining</label>
        <input type="date" name="doj" class="styled-input date-input" id="doj" value="<?= htmlspecialchars($employee['doj']); ?>"   />
    </div>
</div>

    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Aadhar Number</label>
        <input type="text" name="aadhar" class="styled-input" placeholder="Enter Aadhar Number" pattern="[0-9]{12}"   value="<?= htmlspecialchars($employee['aadhar']); ?>" />
      </div>
    </div>
    <!-- <div class="row"> -->
  <!-- Police Verification Field -->
<div class="col-md-3">
<div class="input-field-container">
  <label class="input-label">Police Verification</label>
  <select 
    name="police_verification" 
    class="styled-input" 
    id="policeVerificationSelect" 
    onchange="toggleDocumentUploadField()">
    <option value="" disabled <?= !isset($employee['police_verification']) ? 'selected' : ''; ?>>Select Status</option>
    <option value="verified" <?= isset($employee['police_verification']) && $employee['police_verification'] === 'verified' ? 'selected' : ''; ?>>Verified</option>
    <option value="pending" <?= isset($employee['police_verification']) && $employee['police_verification'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
    <option value="rejected" <?= isset($employee['police_verification']) && $employee['police_verification'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
  </select>
</div>
</div>

<!-- Document Upload Field -->
<div class="col-md-3" id="documentUploadField" style="display: none;">
  <div class="input-field-container">
    <label class="input-label" id="documentLabel">Upload Document</label>
    <input 
      type="file" 
      name="verification_document" 
      class="styled-input" 
      accept=".pdf,.doc,.docx,.jpg,.png"
    />
  </div>
</div>

<!-- </div> -->
<div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label">Aadhar Upload Document</label>
    <input 
      type="file" 
      name="adhar_upload_doc" 
      class="styled-input" 
      accept=".pdf,.jpg,.jpeg,.png" 
       
      title="Please upload a valid Aadhar document (PDF, JPG, JPEG, or PNG)" value="<?= htmlspecialchars($employee['adhar_upload_doc']); ?>" />
  </div>
</div>

  <!-- Row 4 -->
<div class="row">
  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (8 hours)</label>
      <input type="number" name="daily_rate8" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate8']); ?>"/>
    </div>
  </div>
  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (12 hours)</label>
      <input type="number" name="daily_rate12" class="styled-input"  value="<?= htmlspecialchars($employee['daily_rate12']); ?>" />
    </div>
  </div>
  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (24 hours)</label>
      <input type="number" name="daily_rate24" class="styled-input"  value="<?= htmlspecialchars($employee['daily_rate24']); ?>" />
    </div>
  </div>
  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Reference</label>
      <select name="reference" id="reference" class="styled-input">
        <option value="" disabled selected>Select Reference</option>
        <option value="ayush">Ayush</option>
        <option value="vendors">Vendors</option>
      </select>
    </div>
  </div>
</div>


<!-- Hidden Fields for Vendor Name and Contact -->
<div class="col-md-3" id="vendorFields" style="display: none;">
  <div class="input-field-container">
  <div class="d-flex align-items-center">
        <label class="input-label me-2 mb-0">Vendor Name</label>
        <select name="vendor_name" id="vendor_name" class="styled-input form-control me-2">
          <option value="" disabled selected>Select Vendor</option>
        </select>
        <i 
          class="fas fa-plus-square text-success" 
          id="addVendorBtn" 
          style="font-size: 1.5rem; cursor: pointer;" 
          title="Add Vendor">
        </i>
      </div>
  </div>
</div>


<div class="col-md-3" id="vendorContactField" style="display: none;">
    <div class="input-field-container">
      <label class="input-label">Vendor Contact Number</label>
      <input type="text" id="vendor_contact" name="vendor_contact" class="styled-input" placeholder="Enter Vendor Contact Number" pattern="[0-9]{10}" readonly />
    </div>
  </div>
      
  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Beneficiary Name</label>
      <input type="text" id="beneficiary_name" name="beneficiary_name" class="styled-input" placeholder="Enter Beneficiary Name" value="<?= htmlspecialchars($employee['beneficiary_name']); ?>"/>
    </div>
  </div>

  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Bank Name</label>
      <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter Bank Name"  value="<?= htmlspecialchars($employee['bank_name']); ?>" />
    </div>
  </div>

  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Branch</label>
      <input type="text" id="branch" name="branch" class="styled-input" placeholder="Enter Branch Name"  value="<?= htmlspecialchars($employee['branch']); ?>"  />
    </div>
  </div>

  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Bank Account Number</label>
      <input type="text" id="bank_account_no" name="bank_account_no" class="styled-input" placeholder="Enter Account Number" value="<?= htmlspecialchars($employee['bank_account_no']); ?>" />
    </div>
  </div>

  <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">IFSC Code</label>
      <input type="text" id="ifsc_code" name="ifsc_code" class="styled-input" placeholder="Enter IFSC Code" value="<?= htmlspecialchars($employee['ifsc_code']); ?>" />
    </div>
  </div>
    
    <div class="row">
    <!-- Card inside col-md-6 -->
    <div class="col-md-6">
        <div class="card" style="border: 1px solid #8B4513; border-radius: 8px;">
            <div class="card-body">
                <!-- <h5 class="card-title">Address Details</h5> -->
                <div id="address-container">
                    <!-- First Address Entry -->
                    <div class="address-entry" id="address-1">
                        <div class="row">
                            <!-- Pincode Field -->
                            <div class="col-md-6">
                                <div class="input-field-container">
                                    <label class="input-label">Pincode</label>
                                    <input type="text" name="pincode" class="styled-input" placeholder="6 digits [0-9] PIN code"  pattern="\d{6}" maxlength="6" value="<?= htmlspecialchars($employee['pincode']); ?>" />
                                </div>
                            </div>
                            <!-- Flat, House No., Building, Apartment -->
                            <div class="col-md-6">
                                <div class="input-field-container">
                                    <label class="input-label">Flat, House No., Building, Apartment</label>
                                    <input type="text" name="address_line1" class="styled-input" placeholder="Enter Flat, House No., Building, etc."  value="<?= htmlspecialchars($employee['address_line1']); ?>" />
                                </div>
                            </div>

                            <!-- Area, Street, Sector, Village -->
                            <div class="col-md-6">
                                <div class="input-field-container">
                                    <label class="input-label">Area, Street, Sector, Village</label>
                                    <input type="text" name="address_line2" class="styled-input" placeholder="Enter Area, Street, Sector, Village" value="<?= htmlspecialchars($employee['address_line2']); ?>" />
                                </div>
                            </div>

                            <!-- Landmark -->
                            <div class="col-md-6">
                                <div class="input-field-container">
                                    <label class="input-label">Landmark</label>
                                    <input type="text" name="landmark" class="styled-input" placeholder="E.g. near Apollo Hospital" value="<?= htmlspecialchars($employee['landmark']); ?>"/>
                                </div>
                            </div>

                            <!-- Town/City -->
                            <div class="col-md-6">
                                <div class="input-field-container">
                                    <label class="input-label">Town/City</label>
                                    <input type="text" name="city" class="styled-input" placeholder="Enter Town/City" value="<?= htmlspecialchars($employee['city']); ?>" />
                                </div>
                            </div>

                            <!-- State Dropdown -->
                            <div class="col-md-6">
                            <?php
include('states_dropdown.php');

// Fetch the selected state (if editing a record)
$selectedState = isset($employee['state']) ? $employee['state'] : '';
?>
<div class="input-field-container">
    <label class="input-label">State</label>
    <select name="state" class="styled-input" required>
        <?php
        foreach ($states as $state) {
            $isSelected = ($state === $selectedState) ? 'selected' : '';
            echo "<option value=\"$state\" $isSelected>$state</option>";
        }
        ?>
    </select>
</div>



</div>

                            <!-- Add and Delete Icons -->
                            <div class="col-md-12">
                                <i class="fas fa-plus-square text-success add-more" style="font-size: 1.5rem; cursor: pointer; margin-top: 1px;" title="Add More"></i>
                                <i class="fas fa-trash-alt text-danger delete-icon" style="font-size: 1.3rem; cursor: pointer; margin-top: 10px;" title="Delete"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
  <div class="input-field-container">
    <label class="input-label">Other Documents</label>
    <div id="document-card-container" class="mt-3">
      <!-- Initial Card for Document -->
      <div class="card document-card mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Document Name Field -->
            <div class="me-2  w-100">
              <label class="input-label">Document Name</label>
              <input 
                type="text" 
                name="other_doc_name[]" 
                class="styled-input form-control" 
                placeholder="Enter Document Name" 
                 
                title="Enter the document name" />
            </div>

            <!-- Document File Field -->
            <div class="me-2  w-100 ">
              <label class="input-label">Other Document</label>
              <input 
                type="file" 
                name="other_doc[]" 
                class="styled-input form-control" 
                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                 
                title="Upload a document (PDF, JPG, PNG, DOC, DOCX)" />
            </div>

            <!-- Add More Icon -->
            <i 
              class="fas fa-plus-square text-success me-2 add-more-documents" 
              style="font-size: 1.5rem; cursor: pointer;" 
              title="Add More">
            </i>
            <!-- Remove Icon (Initially Hidden) -->
            <i 
              class="fas fa-trash-alt text-danger remove-field" 
              style="font-size: 1rem; cursor: pointer; display: none;" 
              title="Remove">
            </i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>



 

<script>
  document.getElementById('reference').addEventListener('change', function () {
  const vendorFields = document.getElementById('vendorFields');
  const vendorContactField = document.getElementById('vendorContactField');

  if (this.value === 'vendors') {
    vendorFields.style.display = 'block';
    vendorContactField.style.display = 'block';
    fetchVendorData();
  } else {
    vendorFields.style.display = 'none';
    vendorContactField.style.display = 'none';
  }
});

function fetchVendorData() {
  fetch("fetch_vendor_data.php")
    .then(response => response.json())
    .then(data => {
      if (data.length > 0) {
        const vendorNameSelect = document.getElementById('vendor_name');
        vendorNameSelect.innerHTML = '<option value="" disabled selected>Select Vendor</option>';

        data.forEach(vendor => {
          const option = document.createElement('option');
          option.value = vendor.id;
          option.text = `${vendor.vendor_name} (${vendor.phone_number})`; // Display name with phone number
          option.dataset.phone = vendor.phone_number;
          option.dataset.bank = vendor.bank_name;
          option.dataset.branch = vendor.branch;
          option.dataset.account = vendor.account_number;
          option.dataset.ifsc = vendor.ifsc;

          vendorNameSelect.appendChild(option);
        });

        vendorNameSelect.addEventListener('change', function () {
          const selectedOption = vendorNameSelect.options[vendorNameSelect.selectedIndex];

          document.getElementById('vendor_contact').value = selectedOption.dataset.phone || '';
          document.getElementById('bank_name').value = selectedOption.dataset.bank || '';
          document.getElementById('branch').value = selectedOption.dataset.branch || '';
          document.getElementById('bank_account_no').value = selectedOption.dataset.account || '';
          document.getElementById('ifsc_code').value = selectedOption.dataset.ifsc || '';
        });
      } else {
        console.error("No vendors found.");
      }
    })
    .catch(error => console.error("Error fetching vendor data:", error));
}

</script>
</div>
<!-- Submit Button -->
  <div class="row mt-4">
    <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
</div>

    <?php include 'vendormodal.php'; ?>

   



    <script>
    
    document.querySelector('#addVendorModal form').addEventListener('submit', function (e) {
        e.preventDefault(); 
    
       // Collect field values manually
        const vendorName = document.querySelector('#popup_vendor_name').value;
        const gstin = document.querySelector('#gstin').value;
      
        const phoneNumber = document.querySelector('#phone_number').value;
        const email = document.querySelector('#email').value;
        const vendorType = document.querySelector('#vendor_type').value;
        const bankName = document.querySelector('#bank_name').value;
        const accountNumber = document.querySelector('#account_number').value;
    
        // Create a JSON object or plain object
        const requestData = {
            vendor_name: vendorName,
            gstin: gstin,
          
            phone_number: phoneNumber,
            email: email,
            vendor_type: vendorType,
            bank_name: bankName,
             account_number: accountNumber,
        address: document.querySelector('#address').value,
        services_provided: document.querySelector('#services_provided').value,
        additional_notes: document.querySelector('#additional_notes').value,
        ifsc: document.querySelector('#ifsc').value,
        payment_terms: document.querySelector('#payment_terms').value,
        };
    // Log to console
    console.log(requestData);
    
    // Or use alert to display it
    //alert(JSON.stringify(requestData, null, 2));  // Pretty prints the object
        // Send the data using fetch
        fetch('add_vendor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Sending JSON data
            },
            body: JSON.stringify(requestData), // Serialize object to JSON
        })
        .then(response => {
            if (!response.ok) {
                //throw new Error(HTTP error! status: ${response.status});
                  alert(Error ${response.status}: ${text});  // Display the error as an alert
                    throw new Error(${response.status}: ${text});  // Continue to propagate the error
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addVendorModal'));
                modal.hide();
    
                // Add the new vendor to the dropdown
                const vendorNameSelect = document.getElementById('vendor_name');
                const newOption = document.createElement('option');
                newOption.value = data.vendor.vendor_name;
                newOption.textContent = data.vendor.vendor_name;
                newOption.dataset.phone = data.vendor.phone_number;
                newOption.dataset.id = data.vendor.id;
    
                vendorNameSelect.appendChild(newOption);
                vendorNameSelect.value = data.vendor.vendor_name; // Select the newly added vendor
    
                // Update the contact field
                document.querySelector('input[name="vendor_contact"]').value = data.vendor.phone_number;
                document.querySelector('input[name="vendor_id"]').value = data.vendor.id;
    
              //  alert('Vendor added successfully!');
            } else {
             console.error('Error:', data.message, 'SQL:', data.sql);
    // Display the SQL query returned from the server
    alert('An error occurred: ' + data.message + '\nSQL: ' + data.sql);
    
            }
        })
        .catch(error => {
            console.error('Error:', error);
          //  alert('from catch block An error occurred while adding the vendor.');
        });
    });
    </script>
    
    
     
    <script>
      
      function toggleDocumentUploadField() {
        const policeVerificationSelect = document.getElementById('policeVerificationSelect');
        const documentUploadField = document.getElementById('documentUploadField');
        const documentLabel = document.getElementById('documentLabel');
        
        // Check the selected value
        const selectedValue = policeVerificationSelect.value;
        
        // Show the upload field and update label based on the selection
        if (selectedValue === 'verified') {
            documentUploadField.style.display = 'block';
            documentLabel.textContent = 'Upload Verified Document';
        } else if (selectedValue === 'rejected') {
            documentUploadField.style.display = 'block';
            documentLabel.textContent = 'Upload Rejected Document';
        } else {
            documentUploadField.style.display = 'none';
        }
    }
    
    
    document.addEventListener('DOMContentLoaded', function () {
      // Add more document fields
      document.querySelector('.add-more-documents').addEventListener('click', function () {
        // Create a new card for document input
        const newCard = document.createElement('div');
        newCard.classList.add('card', 'document-card', 'mb-3');
        newCard.innerHTML = `
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-6">
                <label class="input-label">Document Name</label>
                <input 
                  type="text" 
                  name="other_doc_name[]" 
                  class="styled-input form-control" 
                  placeholder="Enter Document Name" 
                   
                  title="Enter the document name" />
              </div>
              <div class="col-md-6">
                <label class="input-label">Upload Document</label>
                <input 
                  type="file" 
                  name="other_doc[]" 
                  class="styled-input form-control" 
                  accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                   
                  title="Upload a document (PDF, JPG, PNG, DOC, DOCX)" />
              </div>
            </div>
            <div class="text-end mt-2">
              <i 
                class="fas fa-trash-alt text-danger remove-field" 
                style="font-size: 1rem; cursor: pointer;" 
                title="Remove">
              </i>
            </div>
          </div>
        `;
        // Append the new card to the container
        document.getElementById('document-card-container').appendChild(newCard);
    
        // Add event listener for remove button
        newCard.querySelector('.remove-field').addEventListener('click', function () {
          newCard.remove();
        });
    
        // Show the remove button for all cards
        document.querySelectorAll('.remove-field').forEach(icon => icon.style.display = 'inline');
      });
    
      // Remove existing cards except for the initial ones (maintain stable fields)
      document.querySelectorAll('.remove-field').forEach(function (icon) {
        icon.addEventListener('click', function () {
          const card = icon.closest('.card');
          // Ensure only newly added cards are removed
          if (card.classList.contains('document-card')) {
            card.remove();
          }
        });
      });
    });
    
    
    
    
    
        document.getElementById('reference').addEventListener('change', function () {
        const addVendorBtn = document.getElementById('addVendorBtn');
        if (this.value === 'vendors') {
          addVendorBtn.style.display = 'inline-block'; // Show the "+" button
        } else {
          addVendorBtn.style.display = 'none'; // Hide the "+" button
        }
      });
    
     document.getElementById('addVendorBtn').addEventListener('click', function () {
        // Get the modal element
        const addVendorModalElement = document.getElementById('addVendorModal');
        
        // Create a Bootstrap modal instance
        const addVendorModal = new bootstrap.Modal(addVendorModalElement);
        
        // Show the modal
        addVendorModal.show();
    });
    
      document.getElementById('reference').addEventListener('change', function () {
        const addVendorBtn = document.getElementById('addVendorBtn');
        if (this.value === 'vendors') {
          addVendorBtn.style.display = 'inline-block'; // Show the "+" button
        } else {
          addVendorBtn.style.display = 'none'; // Hide the "+" button
        }
      });
    </script>
    
    <script>
        // Function to set the Date of Joining field to today's date
        window.onload = function() {
            // Get today's date
            const today = new Date();
            const year = today.getFullYear();
            const month = ("0" + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
            const day = ("0" + today.getDate()).slice(-2);
    
            // Set the date input value
            const dateOfJoiningField = document.getElementById('doj');
            dateOfJoiningField.value = ${year}-${month}-${day};
        };
    
    
        // Add more address functionality
      document.querySelector('.add-more').addEventListener('click', function() {
        const addressContainer = document.getElementById('address-container');
        const newAddress = document.querySelector('.address-entry').cloneNode(true);
        // Show the delete icon from the second entry onwards
        addressContainer.appendChild(newAddress);
        updateDeleteIcons();
      });
    
      // Delete an address entry
      function updateDeleteIcons() {
        const deleteIcons = document.querySelectorAll('.delete-icon');
        deleteIcons.forEach((icon, index) => {
          if (index > 0) {
            icon.style.display = 'inline'; // Show delete icon from the second entry onward
            icon.addEventListener('click', function() {
              const addressEntry = icon.closest('.address-entry');
              addressEntry.remove();
            });
          } else {
            icon.style.display = 'none'; // Hide delete icon in the first entry
          }
        });
      }
    
      // Initialize delete icons
      updateDeleteIcons();
    </script>



<!-- jQuery, Popper.js, and Bootstrap JS -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>