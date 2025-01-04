<?php
session_start();
$alert_message = isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : null;
$alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : null;

// Clear session variables after displaying the alert
unset($_SESSION['alert_message'], $_SESSION['alert_type']);
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <h3 class="mb-4">Employee Form</h3>
    <form method="POST" id="employee_registartion" enctype="multipart/form-data" action="empdb.php">
    <!-- Row 1 -->
  <div class="row">
  <div class="row" style="margin: 0;">
    <div class="col-12 col-lg-9 form-first-row">
  <!-- Fields Container -->
  <h2 class="basic-title">Basic Details</h2>
    <div class="row">
      <!-- Name Field -->
       
      <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
      <div class="input-field-container">
        <label class="input-label">Name</label>
        <input type="text" name="name" class="styled-input" placeholder="Enter your name"  />
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-3">
      <div class="input-field-container">
      <label class="input-label">DOB</label>
            <input type="date" name="dob" class="styled-input date-input"  />
            <!-- <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" > -->
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-3">
      <div class="input-field-container">
        <label class="input-label">Gender</label>
        <select name="gender" class="styled-input" >
          <option value="" disabled selected>Select Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
    </div>


    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-3">
      <div class="input-field-container">
        <label class="input-label">Phone Number</label>
        <input type="tel" name="phone" class="styled-input" placeholder="Enter phone number" pattern="[0-9]{10}"  />
      </div>
    </div>



    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
      <div class="input-field-container">
        <label class="input-label">Email</label>
        <input type="email" name="email" class="styled-input" placeholder="Enter email"  />
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2">
  <div class="input-field-container">
    <label class="input-label">Role</label>
    <select name="role" class="styled-input" >
      <option value="" disabled selected>Select Role</option>
      <option value="care_taker">Care Taker</option>
      <option value="nanny">Nanny</option>
      <option value="fully_trained_nurse">Fully Trained Nurse</option>
      <option value="semi_trained_nurse">Semi Trained Nurse</option>
    </select>
   
  </div>
</div>


    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-2">
      <div class="input-field-container">
        <label class="input-label">Qualification</label>
        <select name="qualification" class="styled-input" >
          <option value="" disabled selected>Select Qualification</option>
          <option value="10th">10th</option>
          <option value="intermediate">Intermediate</option>
          <option value="degree">Degree</option>
          <option value="pg">PG</option>

        </select>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2">
      <div class="input-field-container">
        <label class="input-label">Experience</label>
        <select name="experience" class="styled-input" >
          <option value="" disabled selected>Select Experience</option>
          <option value="0-1">0 to 1 year</option>
          <option value="2-3">2 to 3 years</option>
          <option value="4-5">4 to 5 years</option>
          <option value="above 5">above 5 years</option>

        </select>
      </div>
    </div>
 

  <!-- Row 3 -->

  <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2">
    <div class="input-field-container">
        <label class="input-label">DOJ</label>
        <input type="date" name="doj" class="styled-input date-input" id="doj"  />
    </div>
</div>


    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-2">
      <div class="input-field-container">
        <label class="input-label">Aadhar Number</label>
        <input type="text" name="aadhar" class="styled-input" placeholder="Enter Aadhar Number" pattern="[0-9]{12}"  />
      </div>
    </div>
    </div>
    </div>

   
  <!-- Police Verification Field -->
  <!-- Right Section (col-md-3) -->
  <div class="col-12 col-lg-2 form-first-sub-row" >
    <!-- Daily Rate Fields -->
    <div class="row">
      <div class="col-12 mt-4">
        <div class="input-field-container">
    <label class="input-label">Police Verification</label>
    <select 
      name="police_verification" 
      class="styled-input" 
      id="policeVerificationSelect" 
      onchange="toggleDocumentUploadField()">
      <option value="">Select Status</option>
      <option value="verified">Verified</option>
      <option value="pending">Pending</option>
      <option value="rejected">Rejected</option>
    </select>
  </div>
</div>

<div class="col-12" id="documentUploadField" style="display: none;">
  <div class="input-field-container">
    <label class="input-label" id="documentLabel">Upload Document</label>
    <input 
      type="file" 
      name="police_verification_document" 
      class="styled-input" 
      accept=".pdf,.jpg,.png,.doc,.docx" />
  </div>
</div>
</div>
  </div>
</div>


<!-- </div> -->




<div class="row form-second-row">
  <!-- Daily Rates Section -->
  <h2 class="daily-title">Daily Rates</h2>
  <div class="row">
    <!-- Daily Rate (8 hours) -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-4 ">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (8 hours)</label>
      <input type="number" name="daily_rate8" class="styled-input" placeholder="Enter Daily Rate" />
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-4">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (12 hours)</label>
      <input type="number" name="daily_rate12" class="styled-input" placeholder="Enter Daily Rate" />
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-4">
    <div class="input-field-container">
      <label class="input-label">Daily Rate (24 hours)</label>
      <input type="number" name="daily_rate24" class="styled-input" placeholder="Enter Daily Rate" />
    </div>
  </div>
  </div>
  </div>

  <div class="row form-second-row-bank-details">
  <!-- Bank Details Section -->
  <h2 class="bank-title">Bank Details</h2>
  <div class="row">
    <!-- Reference -->
    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
      <div class="input-field-container">
      <label class="input-label">Reference</label>
      <select name="reference" id="reference" class="styled-input">
        <option value="" disabled selected>Select Reference</option>
        <option value="ayush">Ayush</option>
        <option value="vendors">Vendors</option>
      </select>
    </div>
  </div>



<!-- Hidden Fields for Vendor Name and Contact -->
<div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3" id="vendorFields" style="display: none;">
  <div class="input-field-container">
    <label class="input-label">Vendor Name</label>
    <div class="d-flex align-items-center">
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




<div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3" id="vendorContactField" style="display: none;">
    <div class="input-field-container">
      <label class="input-label">Vendor Contact Number</label>
      <input type="text" id="vendor_contact" name="vendor_contact" class="styled-input" placeholder="Enter Vendor Contact Number" pattern="[0-9]{10}" readonly />
    </div>
  </div>
      
  <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
    <div class="input-field-container">
      <label class="input-label">Beneficiary Name</label>
      <input type="text" id="beneficiary_name" name="beneficiary_name" class="styled-input" placeholder="Enter Beneficiary Name"/>
    </div>
  </div>



  <div class="col-12 col-sm-6 col-md-3 col-lg-3 mt-3">
    <div class="input-field-container">
      <label class="input-label">Bank Name</label>
      <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter Bank Name"  />
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
    <div class="input-field-container">
      <label class="input-label">Branch</label>
      <input type="text" id="branch" name="branch" class="styled-input" placeholder="Enter Branch Name"  />
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
    <div class="input-field-container">
      <label class="input-label">Bank Account Number</label>
      <input type="text" id="bank_account_no" name="bank_account_no" class="styled-input" placeholder="Enter Account Number"  />
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
    <div class="input-field-container">
      <label class="input-label">IFSC Code</label>
      <input type="text" id="ifsc_code" name="ifsc_code" class="styled-input" placeholder="Enter IFSC Code"  />
    </div>
  </div>
  </div>
  </div>


    
    <div class="row">
    <!-- Card inside col-md-6 -->
    <div class="col-md-7 col-12 mt-3 form-third-row">
        <!-- <div class="card" style="border: 1px solid #8B4513; border-radius: 8px;">
            <div class="card-body"> -->
                <!-- <h5 class="card-title">Address Details</h5> -->
                <div id="address-container">
                <h2 class="address-title">Address</h2>
                    <div class="address-entry" id="address-1">
                        <div class="row">
                            <!-- Pincode Field -->
                            <!-- <div class="col-12 col-sm-6 col-md-12 col-lg-5 mt-3">
                                <div class="input-field-container">
                                    <label class="input-label">Pincode</label>
                                    <input type="text" name="pincode[]" class="styled-input" placeholder="6 digits [0-9] PIN code"  pattern="\d{6}" maxlength="6" />
                                </div>
                            </div> -->
                            
                            <!-- Flat, House No., Building, Apartment -->
                            <div class="col-12 col-sm-6 col-md-12 col-lg-6 mt-3">
                                <div class="input-field-container">
                                    <label class="input-label">Flat, House No., Building, Apartment</label>
                                    <input type="text" name="address_line1[]" class="styled-input" placeholder="Enter Flat, House No., Building, etc."  />
                                </div>
                            </div>

                            <!-- Area, Street, Sector, Village -->
                            <div class="col-12 col-sm-6 col-md-12 col-lg-6 mt-3">
                                <div class="input-field-container">
                                    <label class="input-label">Area, Street, Sector, Village</label>
                                    <input type="text" name="address_line2[]" class="styled-input" placeholder="Enter Area, Street, Sector, Village" />
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-12 col-lg-3 mt-2">
                                <div class="input-field-container">
                                    <label class="input-label">Pincode</label>
                                    <input type="text" name="pincode[]" class="styled-input" placeholder="6 digits [0-9] PIN code"  pattern="\d{6}" maxlength="6" />
                                </div>
                            </div>

                            <!-- Landmark -->
                            <div class="col-12 col-sm-6 col-md-12 col-lg-3 mt-2">
                                <div class="input-field-container">
                                    <label class="input-label">Landmark</label>
                                    <input type="text" name="landmark[]" class="styled-input" placeholder="E.g. near Apollo Hospital" />
                                </div>
                            </div>

                            <!-- Town/City -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mt-2">
                                <div class="input-field-container">
                                    <label class="input-label">Town/City</label>
                                    <input type="text" name="city[]" class="styled-input" placeholder="Enter Town/City" />
                                </div>
                            </div>

                            <!-- State Dropdown -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mt-2">
    <div class="input-field-container">
      <label class="input-label">State</label>
      <select 
        name="state" 
        class="styled-input" 
        required>
        <option value="" disabled selected>Choose a state</option>
        <option value="Andhra Pradesh">Andhra Pradesh</option>
        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
        <option value="Assam">Assam</option>
        <option value="Bihar">Bihar</option>
        <option value="Chhattisgarh">Chhattisgarh</option>
        <option value="Goa">Goa</option>
        <option value="Gujarat">Gujarat</option>
        <option value="Haryana">Haryana</option>
        <option value="Himachal Pradesh">Himachal Pradesh</option>
        <option value="Jharkhand">Jharkhand</option>
        <option value="Karnataka">Karnataka</option>
        <option value="Kerala">Kerala</option>
        <option value="Madhya Pradesh">Madhya Pradesh</option>
        <option value="Maharashtra">Maharashtra</option>
        <option value="Manipur">Manipur</option>
        <option value="Meghalaya">Meghalaya</option>
        <option value="Mizoram">Mizoram</option>
        <option value="Nagaland">Nagaland</option>
        <option value="Odisha">Odisha</option>
        <option value="Punjab">Punjab</option>
        <option value="Rajasthan">Rajasthan</option>
        <option value="Sikkim">Sikkim</option>
        <option value="Tamil Nadu">Tamil Nadu</option>
        <option value="Telangana">Telangana</option>
        <option value="Tripura">Tripura</option>
        <option value="Uttar Pradesh">Uttar Pradesh</option>
        <option value="Uttarakhand">Uttarakhand</option>
        <option value="West Bengal">West Bengal</option>
        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
        <option value="Chandigarh">Chandigarh</option>
        <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
        <option value="Delhi">Delhi</option>
        <option value="Jammu and Kashmir">Jammu and Kashmir</option>
        <option value="Ladakh">Ladakh</option>
        <option value="Lakshadweep">Lakshadweep</option>
        <option value="Puducherry">Puducherry</option>
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
        <!-- </div>
    </div> -->
    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mt-3 form-third-sub-row">
    <h2 class="upload-title">Upload documents</h2>
    <div class="input-field-container">
    <label class="input-label">Other Documents</label>
    <div id="document-card-container" class="mt-3">
      <!-- Initial Card for Document -->
      <!-- <div class="card document-card mb-3">
        <div class="card-body"> -->
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
            <i class="fas fa-plus-square text-success me-2 add-more-documents" style="font-size: 1.5rem; cursor: pointer;" title="Add More"></i>
              <i class="fas fa-trash-alt text-danger remove-field" style="font-size: 1rem; cursor: pointer; display: none;" title="Remove"></i>
          </div>
          <div class="col-12 col-sm-6 col-md-12 col-lg-8 mt-4">
  <div class="input-field-container">
    <label class="input-label">Aadhar Upload Document</label>
    <input 
      type="file" 
      name="adhar_upload_doc" 
      class="styled-input" 
      accept=".pdf,.jpg,.jpeg,.png" 
       
      title="Please upload a valid Aadhar document (PDF, JPG, JPEG, or PNG)" />
  </div>
</div>
        </div>
      </div>
    </div>
    </div>
  <!-- </div>
</div>
</div> -->



 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script>
 document.getElementById('reference').addEventListener('change', function () {
  const vendorFields = document.getElementById('vendorFields');
  const vendorContactField = document.getElementById('vendorContactField');
  const vendorNameInput = document.getElementById('vendor_name');
  const vendorContactInput = document.getElementById('vendor_contact');

  if (this.value === 'vendors') {
    vendorFields.style.display = 'block';
    vendorContactField.style.display = 'block';
    fetchVendorData();
  } else {
    vendorFields.style.display = 'none';
    vendorContactField.style.display = 'none';

    // Clear vendor fields
    vendorNameInput.value = '';
    vendorContactInput.value = '';
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
<!-- </div> -->
<!-- Submit Button -->
  <div class="row emp-submit">
    <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-primary w-100">Submit</button>
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
  
  const selectedValue = policeVerificationSelect.value;
  
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
    window.onload = function() {
    // Set Date of Joining field to today's date
    const today = new Date();
    const year = today.getFullYear();
    const month = ("0" + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
    const day = ("0" + today.getDate()).slice(-2);

    const dateOfJoiningField = document.getElementById('doj');
    if (dateOfJoiningField) {
        dateOfJoiningField.value = `${year}-${month}-${day}`;
    }
};

// Add more address functionality
document.querySelector('.add-more').addEventListener('click', function() {
    const addressContainer = document.getElementById('address-container');
    const addressEntry = document.querySelector('.address-entry');

    if (!addressEntry) {
        console.error("No address-entry element found to clone.");
        return;
    }

    // Clone the first address entry
    const newAddress = addressEntry.cloneNode(true);

    // Reset input fields in the cloned node
    const inputs = newAddress.querySelectorAll('input');
    inputs.forEach(input => input.value = "");

    // Add the cloned entry to the container
    addressContainer.appendChild(newAddress);

    // Update delete icons for all entries
    updateDeleteIcons();
});

// Update delete icons to show/hide and add delete functionality
function updateDeleteIcons() {
    const addressEntries = document.querySelectorAll('.address-entry');
    addressEntries.forEach((entry, index) => {
        let deleteIcon = entry.querySelector('.delete-icon');
        
        if (!deleteIcon) {
            // Create delete icon if it doesn't exist
            deleteIcon = document.createElement('i');
            deleteIcon.classList.add('fas', 'fa-trash', 'delete-icon');
            deleteIcon.style.cursor = 'pointer';
            entry.appendChild(deleteIcon);
        }

        // Show delete icon for all entries except the first one
        deleteIcon.style.display = index > 0 ? 'inline' : 'none';

        // Attach click event to remove the address entry
        deleteIcon.onclick = function() {
            entry.remove();
            updateDeleteIcons();
        };
    });
}

// Initialize delete icons on page load
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