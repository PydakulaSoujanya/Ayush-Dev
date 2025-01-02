
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







  <style>
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

    h1, h2, h3, h4 {
      color: #A26D2B;
    }

   


    .document-upload-card {
      border: 1px solid #A26D2B;
  border-radius: 8px;

}
.input-field-container label {
  font-weight: bold;
}
.mt-3 {
  margin-top: 0.01rem !important;
}
.error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        .styled-input.error {
            border-color: red;
        }

</style>

  
</head>
<body>

  <div class="container mt-7">
    <h3 class="mb-4">Employee Form</h3>
    <form method="POST" id="employee_registartion" enctype="multipart/form-data" action="empdb.php">
    <!-- Row 1 -->
  <div class="row">
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label">Name</label>
    <input 
        type="text" 
        id="nameInput" 
        name="name" 
        class="styled-input" 
        placeholder="Enter your name" 
        maxlength="50"
    />
    <div id="nameError" class="error-message"></div>
</div>



    </div>
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label">Date of Birth</label>
    <input 
        type="date" 
        name="dob" 
        class="styled-input date-input" 
        required 
        id="dob"
    />
    <div id="dobError" class="error-message"></div>
</div>

    </div>
    <div class="col-md-3">
      <div class="input-field-container">
        <label class="input-label">Gender</label>
        <select name="gender" class="styled-input" required>
          <option value="" disabled selected>Select Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
    </div>
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label" for="phone-unique">Phone Number</label>
    <input 
        type="tel" 
        id="phone-unique" 
        name="phone" 
        class="styled-input" 
        placeholder="Enter phone number" 
        required
    />
    <div id="phoneError" class="error-message"></div>
</div>


    </div>
  </div>

  <!-- Row 2 -->
  <div class="row">
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label" for="email-unique">Email</label>
    <input 
        type="email" 
        id="email-unique" 
        name="email" 
        class="styled-input" 
        placeholder="Enter email" 
        required 
        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" 
        title="Enter a valid email address (e.g., example@example.com)"
    />
    <div id="emailError" class="error-message"></div>
</div>


    </div>
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label">Role</label>
    <select name="role" class="styled-input" required>
        <option value="" disabled selected>Select Role</option>
        <option value="care_taker">Care Taker</option>
        <option value="nanny">Nanny</option>
        <option value="fully_trained_nurse">Fully Trained Nurse</option>
        <option value="semi_trained_nurse">Semi Trained Nurse</option>
    </select>
    <div id="roleError" class="error-message"></div>
</div>

</div>


    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label">Qualification</label>
    <select name="qualification" class="styled-input" required>
        <option value="" disabled selected>Select Qualification</option>
        <option value="10th">10th</option>
        <option value="intermediate">Intermediate</option>
        <option value="degree">Degree</option>
        <option value="pg">PG</option>
    </select>
    <div id="qualificationError" class="error-message"></div>
</div>

    </div>
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label">Experience</label>
    <select name="experience" class="styled-input" required>
        <option value="" disabled selected>Select Experience</option>
        <option value="0-1">0 to 1 year</option>
        <option value="2-3">2 to 3 years</option>
        <option value="4-5">4 to 5 years</option>
        <option value="above 5">above 5 years</option>
    </select>
    <div id="experienceError" class="error-message"></div>
</div>

  </div>

  <!-- Row 3 -->
  <div class="row">
  <div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label">Date of Joining</label>
    <input type="date" name="doj" class="styled-input date-input" id="doj" required />
    <div id="dojError" class="error-message"></div>
</div>

</div>

    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label" for="aadhar-unique">Aadhar Number</label>
    <input 
        type="text" 
        id="aadhar-unique" 
        name="aadhar" 
        class="styled-input" 
        placeholder="Enter Aadhar Number" 
        pattern="\d{12}" 
        title="Aadhar number must be exactly 12 digits" 
        required 
    />
    <div id="aadharError" class="error-message"></div>
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
        required
        onchange="toggleDocumentUploadField()">
        <option value="" disabled selected>Select Status</option>
        <option value="verified">Verified</option>
        <option value="pending">Pending</option>
        <option value="rejected">Rejected</option>
    </select>
    <div id="policeVerificationError" class="error-message"></div>
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
        required 
        title="Please upload a valid Aadhar document (PDF, JPG, JPEG, or PNG)" />
    <div id="aadharDocError" class="error-message"></div>
</div>

</div>

  <!-- Row 4 -->
<div class="row">
  <div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label" for="daily-rate">Daily Rate (8 hours)</label>
    <input 
        type="number" 
        id="daily-rate" 
        name="daily_rate8" 
        class="styled-input" 
        placeholder="Enter Daily Rate" 
        min="0" 
        step="1" 
        title="Only numeric values are allowed" 
        required 
    />
    <div id="dailyRateError" class="error-message"></div>
</div>

  </div>
  <div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label" for="daily-rate-12">Daily Rate (12 hours)</label>
    <input 
        type="number" 
        id="daily-rate-12" 
        name="daily_rate12" 
        class="styled-input" 
        placeholder="Enter Daily Rate" 
        min="0" 
        step="1" 
        required 
    />
    <div id="dailyRate12Error" class="error-message"></div>
</div>

  </div>
  <div class="col-md-3">
  <div class="input-field-container">
    <label class="input-label" for="daily-rate-24">Daily Rate (24 hours)</label>
    <input 
        type="number" 
        id="daily-rate-24" 
        name="daily_rate24" 
        class="styled-input" 
        placeholder="Enter Daily Rate" 
        min="0" 
        step="1" 
        required 
    />
    <div id="dailyRate24Error" class="error-message"></div>
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
                required 
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
                required 
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


    <div class="col-md-6">
    <div class="input-field-container">
    <label class="input-label">Bank Name</label>
    <select name="bank_name" class="styled-input" required>
        <option value="" disabled selected>Select Bank Name</option>
        <?php include("banks-dropdown.php");?>
    </select>
    <div id="bankNameError" class="error-message"></div>
</div>

</div>
<div class="col-md-3">
<div class="input-field-container">
    <label class="input-label" for="branch">Branch</label>
    <input 
        type="text" 
        id="branch" 
        name="branch" 
        class="styled-input" 
        placeholder="Enter Branch Name" 
        pattern="[A-Za-z\s]+" 
        title="Only letters and spaces are allowed" 
        required 
    />
</div>

    </div>

    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label" for="bank-account-no">Bank Account Number</label>
    <input 
        type="text" 
        id="bank-account-no" 
        name="bank_account_no" 
        class="styled-input" 
        placeholder="Enter Account Number" 
        pattern="\d{12}" 
        title="Only 12 digits are allowed" 
        required 
    />
    <div id="bankAccountError" class="error-message"></div>
</div>


    </div>
    <div class="col-md-3">
    <div class="input-field-container">
    <label class="input-label" for="ifsc-code">IFSC Code</label>
    <input 
        type="text" 
        id="ifsc-code" 
        name="ifsc_code" 
        class="styled-input" 
        placeholder="Enter IFSC Code" 
        pattern="[A-Za-z0-9]+" 
        title="Only letters and digits are allowed" 
        required 
    />
    <div id="ifscError" class="error-message"></div>
</div>

    </div>
   

<!-- Pincode Field -->
<div id="address-container">
        <!-- First Address Entry -->
        <div class="address-entry" id="address-1">
          <div class="row">
            <div class="col-md-3">
            <div class="input-field-container">
    <label class="input-label" for="pincode">Pincode</label>
    <input 
        type="text" 
        id="pincode" 
        name="pincode[]" 
        class="styled-input" 
        placeholder="6 digits [0-9] PIN code" 
        pattern="\d{6}" 
        maxlength="6" 
        title="Only 6 digits are allowed" 
        required 
    />
</div>
            </div>
            <div class="col-md-3">
                <div class="input-field-container">
                    <label class="input-label">Flat, House No.,Building,Apartment</label>
                    <input type="text" name="address_line1[]" class="styled-input" placeholder="Enter Flat, House No., Building, etc." required />
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-field-container">
                    <label class="input-label">Area, Street, Sector, Village</label>
                    <input type="text" name="address_line2[]" class="styled-input" placeholder="Enter Area, Street, Sector, Village" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-field-container">
                    <label class="input-label">Landmark</label>
                    <input type="text" name="landmark[]" class="styled-input" placeholder="E.g. near Apollo Hospital" />
                </div>
            </div>
            <div class="col-md-3">
            <div class="input-field-container">
    <label class="input-label" for="city">Town/City</label>
    <input 
        type="text" 
        id="city" 
        name="city[]" 
        class="styled-input" 
        placeholder="Enter Town/City" 
        pattern="[A-Za-z\s]+" 
        title="Only letters and spaces are allowed" 
        required 
    />
</div>
            </div>
            <div class="col-md-3">
                <div class="input-field-container">
                    <label class="input-label">State</label>
                    <select name="state[]" class="styled-input" required>
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
            <div class="col-md-3">
          <i class="fas fa-plus-square text-success me-2 add-more" style="font-size: 1.5rem; cursor: pointer; margin-top: 10px;" title="Add More"></i>
          
          <i class="fas fa-trash-alt text-danger delete-icon" style="font-size: 1.3rem; cursor: pointer; margin-top: 10px;" title="Delete"></i>
        </div>
        </div>
    </div>
    </div>

    <div class="col-md-3">
    <div class="input-field-container">
      <label class="input-label">Reference</label>
      <select name="reference" id="reference" class="styled-input" required>
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
      <!-- Vendor Name Field -->
      <label class="input-label me-2 mb-0">Vendor Name</label>
      <select name="vendor_name" id="vendor_name" class="styled-input form-control me-2" required>
        <option value="" disabled selected>Select Vendor</option>
        <!-- Option items here -->
      </select>
      
      <!-- Plus Icon for adding vendor -->
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
    <input type="text" name="vendor_contact" class="styled-input" placeholder="Enter Vendor Contact Number" pattern="[0-9]{10}" />
  </div>
</div>
      


<script>
  document.getElementById('reference').addEventListener('change', function () {
    const vendorFields = document.getElementById('vendorFields');
    const vendorContactField = document.getElementById('vendorContactField');

    if (this.value === 'vendors') {
      // Show the Vendor Name and Contact fields
      vendorFields.style.display = 'block';
      vendorContactField.style.display = 'block';


  fetchVendorData(this.value);

    } else {
      // Hide the Vendor Name and Contact fields
      vendorFields.style.display = 'none';
      vendorContactField.style.display = 'none';
    }
  });
 function fetchVendorData(reference) {
  fetch("fetch_vendor_data.php?reference=" + reference)
    .then(response => {
      if (!response.ok) {
        throw new Error(HTTP error! status: ${response.status});
      } 
      return response.text(); // Read as text first for debugging
    })
    .then(text => {
      console.log('Raw response:', text); // Debug raw response
      try {
        const data = JSON.parse(text); // Try parsing JSON
        if (data.error) {
          console.error("Server error:", data.error);
          return;
        }
        if (data.length > 0) {
          alert("on clicked vendors and calling fetchVendor fun");
          const vendorNameSelect = document.getElementById('vendor_name');
          vendorNameSelect.innerHTML = '<option value="" disabled selected>Select Vendor</option>';
          // Populate the dropdown with vendor details
data.forEach(vendor => {
  const option = document.createElement('option');
  option.value = vendor.vendor_name; // Use vendor name as the value
  option.text = vendor.vendor_name; // Display vendor name
  option.dataset.phone = vendor.phone_number; // Store phone number in a data attribute
  option.dataset.id = vendor.id; // Store vendor ID in a data attribute
  vendorNameSelect.appendChild(option);
});

// Add an event listener to the dropdown to update fields dynamically
vendorNameSelect.addEventListener('change', function () {
  const selectedOption = vendorNameSelect.options[vendorNameSelect.selectedIndex];
  const vendorContactField = document.querySelector('input[name="vendor_contact"]');
  const vendorIdField = document.querySelector('input[name="vendor_id"]');

  if (selectedOption) {
    // Set the phone number and vendor ID based on the selected option
    vendorContactField.value = selectedOption.dataset.phone || ''; // Default to empty if not found
    vendorIdField.value = selectedOption.dataset.id || ''; // Default to empty if not found
  } else {
    // Clear the fields if no vendor is selected
    vendorContactField.value = '';
    vendorIdField.value = '';
  }
});

        } else {
          console.error("No vendors found.");
        }
      } catch (e) {
        console.error("Failed to parse JSON:", e);
      }
    })
    .catch(error => {
      console.error("Error fetching vendor data:", error);
    });
}
</script>
</div>
<!-- Submit Button -->
  <div class="row">
    <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
</div>
<!-- Alert Modal -->
<!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVendorModalLabel">Add Vendor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  method="POST" id="add_vendor" enctype="multipart/form-data">
          <!-- Vendor Form Fields -->
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Vendor Name</label>
                <input type="text" id="popup_vendor_name" name="vendor_name" class="styled-input" placeholder="Enter Vendor Name" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">GSTIN</label>
                <input type="text" id="gstin" name="gstin" class="styled-input" placeholder="Enter GSTIN" />
              </div>
            </div>
          </div>
          <!-- Additional Fields (Contact, Address, etc.) -->
          <div class="row">
           
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="styled-input" placeholder="Enter Phone Number" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Email</label>
                <input type="email" id="email" name="email" class="styled-input" placeholder="Enter Email" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Vendor Type</label>
                <select name="vendor_type" id="vendor_type" class="styled-input">
                  <option value="Individual">Individual</option>
                  <option value="Company">Company</option>
                </select>
              </div>
            </div>
          </div>
          <!-- Bank Details -->
          <h4>Bank Details</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter Bank Name" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Account Number</label>
                <input type="text" id="account_number" name="account_number" class="styled-input" placeholder="Enter Account Number" />
              </div>
            </div>
             
            <div class="col-md-6">
            <div class="input-field-container">
    <label class="input-label">Address</label>
    <textarea id="address" name="address" class="styled-input" placeholder="Enter Address"></textarea>
  </div>
 </div>
            <div class="col-md-6">
  <!-- Services Provided -->
  <div class="input-field-container">
    <label class="input-label">Services Provided</label>
    <textarea id="services_provided" name="services_provided" class="styled-input" placeholder="Enter Services Provided"></textarea>
  </div>
 </div>
            <div class="col-md-6">
  <!-- Additional Notes -->
  <div class="input-field-container">
    <label class="input-label">Additional Notes</label>
    <textarea id="additional_notes" name="additional_notes" class="styled-input" placeholder="Enter Additional Notes"></textarea>
  </div>
 </div>
            
            <div class="col-md-6">
  <!-- IFSC Code -->
  <div class="input-field-container">
    <label class="input-label">IFSC Code</label>
    <input type="text" id="ifsc" name="ifsc" class="styled-input" placeholder="Enter IFSC Code" />
  </div>
 </div>
            <div class="col-md-6">
  <!-- Payment Terms -->
  <div class="input-field-container">
    <label class="input-label">Payment Terms</label>
    <textarea id="payment_terms" name="payment_terms" class="styled-input" placeholder="Enter Payment Terms"></textarea>
  </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>


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
              required 
              title="Enter the document name" />
          </div>
          <div class="col-md-6">
            <label class="input-label">Upload Document</label>
            <input 
              type="file" 
              name="other_doc[]" 
              class="styled-input form-control" 
              accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
              required 
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

<script>
   

   

    document.getElementById('phone-unique').addEventListener('input', function(e) {
        let value = e.target.value;

        // Allow only digits
        value = value.replace(/\D/g, '');

        // Restrict to 10 digits
        if (value.length > 10) {
            value = value.slice(0, 10);
        }

        // Update the input value
        e.target.value = value;
    });


    document.getElementById('email-unique').addEventListener('input', function (e) {
        const email = e.target.value;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Check if the email matches the pattern
        if (!emailRegex.test(email)) {
            e.target.setCustomValidity("Please enter a valid email address");
        } else {
            e.target.setCustomValidity(""); // Clear the error if valid
        }
    });

    document.getElementById('aadhar-unique').addEventListener('input', function(e) {
        let value = e.target.value;

        // Remove all non-digit characters
        value = value.replace(/\D/g, '');

        // Restrict input to 12 digits
        if (value.length > 12) {
            value = value.slice(0, 12);
        }

        // Update the input value
        e.target.value = value;
    });

    document.getElementById('daily-rate').addEventListener('input', function(e) {
        // Remove any non-digit input
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('bank-account-no').addEventListener('input', function(e) {
        // Allow only numeric digits
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Restrict input to exactly 12 digits
        if (e.target.value.length > 12) {
            e.target.value = e.target.value.slice(0, 12);
        }
    });

    document.getElementById('ifsc-code').addEventListener('input', function(e) {
        // Allow only alphanumeric characters
        e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '');
    });

    document.getElementById('pincode').addEventListener('input', function(e) {
        // Allow only numeric digits
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Restrict input to exactly 6 digits
        if (e.target.value.length > 6) {
            e.target.value = e.target.value.slice(0, 6);
        }
    });

    document.getElementById('city').addEventListener('input', function(e) {
        // Allow only letters and spaces
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
    });
</script>

<script>
const nameInput = document.getElementById('nameInput');
const nameError = document.getElementById('nameError');

nameInput.addEventListener('input', () => {
    let value = nameInput.value;
    let errorMessage = '';

    // Validation checks
    if (!/^[a-zA-Z\s]*$/.test(value)) {
        errorMessage = 'Only letters and spaces are allowed.';
    } else if (value.trim() === '') {
        errorMessage = 'Please enter a name.';
    } else if (value.length > 50) {
        errorMessage = 'Name should not exceed 50 characters.';
    }

    // Display error messages
    if (errorMessage) {
        nameError.textContent = errorMessage;
        nameError.style.display = 'block';
        nameInput.classList.add('error');
    } else {
        nameError.style.display = 'none';
        nameInput.classList.remove('error');
    }
});

// Format the name on blur (when the user finishes typing)
nameInput.addEventListener('blur', () => {
    let value = nameInput.value.trim();
    value = value
        .split(' ') // Split into words
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each word
        .join(' '); // Rejoin words with spaces

    nameInput.value = value;
});



const dobInput = document.getElementById('dob');
const dobError = document.getElementById('dobError');

// Set maximum selectable date to today's date
const today = new Date().toISOString().split('T')[0];
dobInput.setAttribute('max', today);

dobInput.addEventListener('change', () => {
    const dobValue = dobInput.value; // Get the selected date value
    let errorMessage = '';

    if (!dobValue) {
        // If the field is empty
        errorMessage = 'Please select a date.';
    } else {
        const selectedDate = new Date(dobValue);
        const currentDate = new Date();

        // Calculate the age
        const age = currentDate.getFullYear() - selectedDate.getFullYear();
        const isBeforeBirthday = 
            currentDate.getMonth() < selectedDate.getMonth() ||
            (currentDate.getMonth() === selectedDate.getMonth() && currentDate.getDate() < selectedDate.getDate());

        const exactAge = isBeforeBirthday ? age - 1 : age;

        // Check if age is less than 18
        if (exactAge < 18) {
            errorMessage = 'Age should be greater than or equal to 18.';
        }
    }

    // Display error message
    if (errorMessage) {
        dobError.textContent = errorMessage;
        dobError.style.display = 'block';
        dobInput.classList.add('error');
    } else {
        dobError.style.display = 'none';
        dobInput.classList.remove('error');
    }
});

const phoneInput = document.getElementById('phone-unique');
const phoneError = document.getElementById('phoneError');

phoneInput.addEventListener('input', () => {
    let value = phoneInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (value.length > 10) {
        errorMessage = 'Phone number should be no more than 10 digits.';
    } else if (!/^[6-9]\d*$/.test(value)) {
        errorMessage = 'Phone number must start with a valid prefix (6-9 in India).';
    } else if (!/^\d*$/.test(value)) {
        errorMessage = 'Please enter a valid Phone Number without letters or special characters.';
    }

    // Display error messages
    if (errorMessage) {
        phoneError.textContent = errorMessage;
        phoneError.style.display = 'block';
        phoneInput.classList.add('error');
    } else {
        phoneError.style.display = 'none';
        phoneInput.classList.remove('error');
    }
});


const emailInput = document.getElementById('email-unique');
const emailError = document.getElementById('emailError');

emailInput.addEventListener('input', () => {
    let value = emailInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) {
        if (!value.includes('@') || !value.includes('.')) {
            errorMessage = 'Please enter a valid email format.';
        } else if (/[^a-zA-Z0-9._%+-@]/.test(value)) {
            errorMessage = 'Email contains invalid characters.';
        } else if (value.trim() === '') {
            errorMessage = 'Please enter Email.';
        }
    }

    // Display error messages
    if (errorMessage) {
        emailError.textContent = errorMessage;
        emailError.style.display = 'block';
        emailInput.classList.add('error');
    } else {
        emailError.style.display = 'none';
        emailInput.classList.remove('error');
    }
});

const roleSelect = document.querySelector('select[name="role"]');
const roleError = document.getElementById('roleError');

roleSelect.addEventListener('change', () => {
    let value = roleSelect.value.trim();
    let errorMessage = '';

    // Validation check
    if (!value) {
        errorMessage = 'Please select Role.';
    }

    // Display error messages
    if (errorMessage) {
        roleError.textContent = errorMessage;
        roleError.style.display = 'block';
        roleSelect.classList.add('error');
    } else {
        roleError.style.display = 'none';
        roleSelect.classList.remove('error');
    }
});

const qualificationSelect = document.querySelector('select[name="qualification"]');
const qualificationError = document.getElementById('qualificationError');

qualificationSelect.addEventListener('change', () => {
    let value = qualificationSelect.value.trim();
    let errorMessage = '';

    // Validation check
    if (!value) {
        errorMessage = 'Please select Qualification.';
    }

    // Display error messages
    if (errorMessage) {
        qualificationError.textContent = errorMessage;
        qualificationError.style.display = 'block';
        qualificationSelect.classList.add('error');
    } else {
        qualificationError.style.display = 'none';
        qualificationSelect.classList.remove('error');
    }
});

const dojInput = document.getElementById('doj');
const dojError = document.getElementById('dojError');

dojInput.addEventListener('input', () => {
    let value = dojInput.value.trim();
    let errorMessage = '';

    // Validate date format
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        errorMessage = 'Please enter valid Date in dd-mm-yyyy format.';
    } else {
        const selectedDate = new Date(value);
        const today = new Date();

        // Validate if the date is not a future date
        if (selectedDate > today) {
            errorMessage = 'Please enter a valid date. Date cannot be a future date.';
        } else if (value === '') {
            errorMessage = 'Please enter Date.';
        }
    }

    // Display error messages
    if (errorMessage) {
        dojError.textContent = errorMessage;
        dojError.style.display = 'block';
        dojInput.classList.add('error');
    } else {
        dojError.style.display = 'none';
        dojInput.classList.remove('error');
    }
});

const aadharInput = document.getElementById('aadhar-unique');
const aadharError = document.getElementById('aadharError');

aadharInput.addEventListener('input', () => {
    let value = aadharInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (!/^\d{12}$/.test(value)) {
        errorMessage = 'Aadhar Number should be 12 digits.';
    } else if (value.length !== 12) {
        errorMessage = 'Aadhar Number should be exactly 12 digits.';
    } else if (!/^\d+$/.test(value)) {
        errorMessage = 'Please enter a valid Aadhar Number.';
    }

    // Display error messages
    if (errorMessage) {
        aadharError.textContent = errorMessage;
        aadharError.style.display = 'block';
        aadharInput.classList.add('error');
    } else {
        aadharError.style.display = 'none';
        aadharInput.classList.remove('error');
    }
});

const aadharUploadInput = document.querySelector('input[name="adhar_upload_doc"]');
const aadharDocError = document.getElementById('aadharDocError');

aadharUploadInput.addEventListener('change', () => {
    let file = aadharUploadInput.files[0];
    let errorMessage = '';

    // Validate file format
    if (file) {
        const validFormats = ['.pdf', '.jpg', '.jpeg', '.png'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const fileSizeMB = file.size / (1024 * 1024); // Convert to MB

        if (!validFormats.includes(`.${fileExtension}`)) {
            errorMessage = 'Aadhar accepts only PDF, JPEG, PNG format.';
        } else if (fileSizeMB > 2) {
            errorMessage = 'File size exceeds the limit of 2MB.';
        }
    } else {
        errorMessage = 'Please upload Aadhar.';
    }

    // Display error messages
    if (errorMessage) {
        aadharDocError.textContent = errorMessage;
        aadharDocError.style.display = 'block';
        aadharUploadInput.classList.add('error');
    } else {
        aadharDocError.style.display = 'none';
        aadharUploadInput.classList.remove('error');
    }
});

const policeVerificationSelect = document.getElementById('policeVerificationSelect');
const policeVerificationError = document.getElementById('policeVerificationError');

policeVerificationSelect.addEventListener('change', () => {
    let value = policeVerificationSelect.value.trim();
    let errorMessage = '';

    // Validation check
    if (!value) {
        errorMessage = 'Please select police verification.';
    }

    // Display error messages
    if (errorMessage) {
        policeVerificationError.textContent = errorMessage;
        policeVerificationError.style.display = 'block';
        policeVerificationSelect.classList.add('error');
    } else {
        policeVerificationError.style.display = 'none';
        policeVerificationSelect.classList.remove('error');
    }
});

const experienceSelect = document.querySelector('select[name="experience"]');
const experienceError = document.getElementById('experienceError');

experienceSelect.addEventListener('change', () => {
    let value = experienceSelect.value.trim();
    let errorMessage = '';

    // Validation check
    if (!value) {
        errorMessage = 'Please select Experience.';
    }

    // Display error messages
    if (errorMessage) {
        experienceError.textContent = errorMessage;
        experienceError.style.display = 'block';
        experienceSelect.classList.add('error');
    } else {
        experienceError.style.display = 'none';
        experienceSelect.classList.remove('error');
    }
});

const dailyRateInput = document.getElementById('daily-rate');
const dailyRateError = document.getElementById('dailyRateError');

dailyRateInput.addEventListener('input', () => {
    let value = dailyRateInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (value < 0) {
        errorMessage = 'Please enter valid data.';
    } else if (!/^\d+(\.\d{1,2})?$/.test(value)) {
        errorMessage = 'Only numeric values are allowed, with up to 2 decimal places.';
    }

    // Display error messages
    if (errorMessage) {
        dailyRateError.textContent = errorMessage;
        dailyRateError.style.display = 'block';
        dailyRateInput.classList.add('error');
    } else {
        dailyRateError.style.display = 'none';
        dailyRateInput.classList.remove('error');
    }
});

const dailyRate12Input = document.getElementById('daily-rate-12');
const dailyRate12Error = document.getElementById('dailyRate12Error');

dailyRate12Input.addEventListener('input', () => {
    let value = dailyRate12Input.value.trim();
    let errorMessage = '';

    // Validation checks
    if (value < 0) {
        errorMessage = 'Please enter valid data.';
    } else if (!/^\d+(\.\d{1,2})?$/.test(value)) {
        errorMessage = 'Only numeric values are allowed, with up to 2 decimal places.';
    }

    // Display error messages
    if (errorMessage) {
        dailyRate12Error.textContent = errorMessage;
        dailyRate12Error.style.display = 'block';
        dailyRate12Input.classList.add('error');
    } else {
        dailyRate12Error.style.display = 'none';
        dailyRate12Input.classList.remove('error');
    }
});

const dailyRate24Input = document.getElementById('daily-rate-24');
const dailyRate24Error = document.getElementById('dailyRate24Error');

dailyRate24Input.addEventListener('input', () => {
    let value = dailyRate24Input.value.trim();
    let errorMessage = '';

    // Validation checks
    if (value < 0) {
        errorMessage = 'Please enter valid data.';
    } else if (!/^\d+(\.\d{1,2})?$/.test(value)) {
        errorMessage = 'Only numeric values are allowed, with up to 2 decimal places.';
    }

    // Display error messages
    if (errorMessage) {
        dailyRate24Error.textContent = errorMessage;
        dailyRate24Error.style.display = 'block';
        dailyRate24Input.classList.add('error');
    } else {
        dailyRate24Error.style.display = 'none';
        dailyRate24Input.classList.remove('error');
    }
});

const bankNameSelect = document.querySelector('select[name="bank_name"]');
const bankNameError = document.getElementById('bankNameError');

bankNameSelect.addEventListener('change', () => {
    let value = bankNameSelect.value.trim();
    let errorMessage = '';

    // Validation checks
    if (!value) {
        errorMessage = 'Please select Bank Name.';
    } else if (/[\d!@#$%^&*(),.?":{}|<>]/.test(value)) {
        errorMessage = 'Bank Name must not contain numbers or special characters.';
    }

    // Display error messages
    if (errorMessage) {
        bankNameError.textContent = errorMessage;
        bankNameError.style.display = 'block';
        bankNameSelect.classList.add('error');
    } else {
        bankNameError.style.display = 'none';
        bankNameSelect.classList.remove('error');
    }
});

const bankAccountInput = document.getElementById('bank-account-no');
const bankAccountError = document.getElementById('bankAccountError');

bankAccountInput.addEventListener('input', () => {
    let value = bankAccountInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (!value) {
        errorMessage = 'Please enter Bank Account Number.';
    } else if (/[^\d]/.test(value)) {
        errorMessage = 'Please enter valid Bank Account Number.';
    } else if (value.length < 10 || value.length > 18) {
        errorMessage = 'Bank Account Number must be between 10 and 18 digits.';
    }

    // Display error messages
    if (errorMessage) {
        bankAccountError.textContent = errorMessage;
        bankAccountError.style.display = 'block';
        bankAccountInput.classList.add('error');
    } else {
        bankAccountError.style.display = 'none';
        bankAccountInput.classList.remove('error');
    }
});

const ifscInput = document.getElementById('ifsc-code');
const ifscError = document.getElementById('ifscError');

ifscInput.addEventListener('input', () => {
    let value = ifscInput.value.trim();
    let errorMessage = '';

    // Validation checks
    if (!value) {
        errorMessage = 'Please enter IFSC Code.';
    } else if (!/^[A-Za-z0-9]+$/.test(value)) {
        errorMessage = 'Please enter valid IFSC Code.';
    }

    // Display error messages
    if (errorMessage) {
        ifscError.textContent = errorMessage;
        ifscError.style.display = 'block';
        ifscInput.classList.add('error');
    } else {
        ifscError.style.display = 'none';
        ifscInput.classList.remove('error');
    }
});

    </script>


<!-- jQuery, Popper.js, and Bootstrap JS -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>