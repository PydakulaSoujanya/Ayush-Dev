<?php
session_start();
$alert_message = isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : null;
$alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : null;

// Clear session variables after displaying the alert
unset($_SESSION['alert_message'], $_SESSION['alert_type']);
?>
<?php
// Fetch the data from service_master table
include '../config.php';

$sql = "SELECT `id`, `service_name` FROM `service_master` WHERE `status` = 'active'"; // Assuming 'active' status for services you want to show
$result = $conn->query($sql);

// Prepare dropdown options
$options = "";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id'] . "'>" . $row['service_name'] . "</option>";
    }
} else {
    $options = "<option value='' disabled>No services available</option>";
}

$conn->close();
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
<style>
 /* .form-center{
  margin-left: 110px;
 } */
</style>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-7">
    <div class="card custom-card">
    <div class="card-header custom-card-header">Add New Employee</div>
    <div class="card-body">
    <form method="POST" id="employee_registartion" enctype="multipart/form-data" action="empdb.php">
    <!-- Row 1 -->
  <div class="row">
    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Name</label>
        <input type="text" name="name" class="form-control custom-input" placeholder="Enter your name"  />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
      <label class="custom-label">DOB</label>
            <input type="date" name="dob" class="form-control custom-input date-input"  />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Gender</label>
        <select name="gender" class="form-control custom-input" >
          <option value="" disabled selected>Select Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Phone Number</label>
        <input type="tel" name="phone" class="form-control custom-input" placeholder="Enter phone number" />
      </div>
    </div>
    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Email</label>
        <input type="email" name="email" class="form-control custom-input" placeholder="Enter email"  />
      </div>
    </div>
    
    <div class="col-md-6 col-lg-3 custom-padding">
  <div class="form-group custom-form-group">
    <label class="custom-label">Role</label>
    <select class="form-control" name="service_type[]" id="service_type" style="appearance: none; padding-right: 2.5rem;">
      <option value="" disabled selected>Select Role</option>
      <?php echo $options; ?>  <!-- Dynamically populated options -->
    </select>
    <!-- <span class="dropdown-icon position-absolute" style="top: 175px; right: 675px;">
      ▼
    </span> -->
  </div>
  </div>


    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Qualification</label>
        <select name="qualification" class="form-control custom-input" >
          <option value="" disabled selected>Select Qualification</option>
          <option value="10th">10th</option>
          <option value="intermediate">Intermediate</option>
          <option value="degree">Degree</option>
          <option value="pg">PG</option>

        </select>
        
      </div>
    </div>
    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Experience</label>
        <select name="experience" class="form-control custom-input" >
          <option value="" disabled selected>Select Experience</option>
          <option value="0-1">0 to 1 year</option>
          <option value="2-3">2 to 3 years</option>
          <option value="4-5">4 to 5 years</option>
          <option value="above 5">above 5 years</option>

        </select>
      </div>
    </div>


  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
        <label class="custom-label">DOJ</label>
        <input type="date" name="doj" class="form-control custom-input date-input" id="doj"  />
    </div>
</div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Aadhar Number</label>
        <input type="text" name="aadhar" class="form-control custom-input" placeholder="Enter Aadhar Number"   />
      </div>
    </div>
    
  <div class="col-md-6 col-lg-3 custom-padding" >
    
        <div class="form-group custom-form-group">
    <label class="custom-label">Police Verification</label>
    <select 
      name="police_verification" 
      class="form-control custom-input" 
      id="policeVerificationSelect" 
      onchange="toggleDocumentUploadField()">
      <option value="">Select Status</option>
      <option value="verified">Verified</option>
      <option value="pending">Pending</option>
      <option value="rejected">Rejected</option>
    </select>
  </div>
</div>

<div class="col-md-6 col-lg-3 custom-padding" id="documentUploadField" style="display: none;">
  <div class="form-group custom-form-group">
    <label class="custom-label" id="documentLabel">Upload Document</label>
    <input 
      type="file" 
      name="police_verification_document" 
      class="form-control custom-input" 
      accept=".pdf,.jpg,.png,.doc,.docx" />
  </div>
</div>

    <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Daily Rate (8 hours)</label>
      <input type="number" name="daily_rate8" class="form-control custom-input" placeholder="Enter Daily Rate" />
    </div>
  </div>

  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Daily Rate (12 hours)</label>
      <input type="number" name="daily_rate12" class="form-control custom-input" placeholder="Enter Daily Rate" />
    </div>
  </div>

  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Daily Rate (24 hours)</label>
      <input type="number" name="daily_rate24" class="form-control custom-input" placeholder="Enter Daily Rate" />
    </div>
  </div>
  
    <!-- Reference -->
    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
      <label class="custom-label">Reference</label>
      <select name="reference" id="reference" class="form-control custom-input">
        <option value="" disabled selected>Select Reference</option>
        <option value="ayush">Ayush</option>
        <option value="vendors">Vendors</option>
      </select>
    </div>
  </div>
<!-- Hidden Fields for Vendor Name and Contact -->
<div class="col-md-6 col-lg-3 custom-padding" id="vendorFields" style="display: none;">
  <div class="form-group custom-form-group">
    <label class="custom-label">Vendor Name</label>
    <div class="d-flex align-items-center">
      <select name="vendor_name" id="vendor_name" class="form-control custom-input me-2">
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

<div class="col-md-6 col-lg-3 custom-padding" id="vendorContactField" style="display: none;">
    <div class="form-group custom-form-group">
      <label class="custom-label">Vendor Contact Number</label>
      <input type="text" id="vendor_contact" name="vendor_contact" class="form-control custom-input" placeholder="Enter Vendor Contact Number"  readonly />
    </div>
  </div>
      
  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Beneficiary Name</label>
      <input type="text" id="beneficiary_name" name="beneficiary_name" class="form-control custom-input" placeholder="Enter Beneficiary Name"/>
    </div>
  </div>



  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Bank Name</label>
      <input type="text" id="bank_name" name="bank_name" class="form-control custom-input" placeholder="Enter Bank Name" required />
    </div>
</div>

<div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Branch</label>
      <input type="text" id="branch" name="branch" class="form-control custom-input" placeholder="Enter Branch Name" required />
    </div>
</div>

 

  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">Bank Account Number</label>
      <input type="text" id="bank_account_no" name="bank_account_no" class="form-control custom-input" placeholder="Enter Account Number"  />
    </div>
  </div>

  <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="custom-label">IFSC Code</label>
      <input type="text" id="ifsc_code" name="ifsc_code" class="form-control custom-input" placeholder="Enter IFSC Code"  />
    </div>
  </div>


  
  
      <div id="address-container">
                    <div class="address-entry" id="address-1">
                        <div class="row">
                            <!-- Flat, House No., Building, Apartment -->
                            <div class="col-md-6 col-lg-3 custom-padding">
                                <div class="form-group custom-form-group">
                                    <label class="custom-label">Flat, House No., Building, Apartment</label>
                                    <input type="text" name="address_line1[]" class="form-control custom-input" placeholder="Enter Flat, House No., Building, etc."  />
                                </div>
                            </div>

                            <!-- Area, Street, Sector, Village -->
                            <div class="col-md-6 col-lg-3 custom-padding">
                                <div class="form-group custom-form-group">
                                    <label class="custom-label">Area, Street, Sector, Village</label>
                                    <input type="text" name="address_line2[]" class="form-control custom-input" placeholder="Enter Area, Street, Sector, Village" />
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3 custom-padding">
                                <div class="form-group custom-form-group">
                                    <label class="custom-label">Pincode</label>
                                    <input type="text" name="pincode[]" class="form-control custom-input" placeholder="6 digits [0-9] PIN code" maxlength="6" />
                                </div>
                            </div>

                            <!-- Landmark -->
                            <div class="col-md-6 col-lg-3 custom-padding">
                                <div class="form-group custom-form-group">
                                    <label class="custom-label">Landmark</label>
                                    <input type="text" name="landmark[]" class="form-control custom-input" placeholder="E.g. near Apollo Hospital" />
                                </div>
                            </div>

                            <!-- Town/City -->
                            <div class="col-md-6 col-lg-3 custom-padding">
                                <div class="form-group custom-form-group">
                                    <label class="custom-label">Town/City</label>
                                    <input type="text" name="city[]" class="form-control custom-input" placeholder="Enter Town/City" />
                                </div>
                            </div>

                            <!-- State Dropdown -->
        <div class="col-md-6 col-lg-3 custom-padding">
    <div class="form-group custom-form-group">
      <label class="input-label">State</label>
      <select 
        name="state" 
        class="form-control custom-input" 
        >
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
    
                            <div id="document-container">
    <div class="row document-entry">
        <!-- Document Name Field -->
        <div class="col-md-3 custom-padding">
            <div class="form-group custom-form-group">
                <label class="input-label">Document Name</label>
                <input 
                    type="text" 
                    name="other_doc_name[]" 
                    class="form-control custom-input" 
                    placeholder="Enter Document Name" 
                    title="Enter the document name" />
            </div>
        </div>

        <!-- Document File Field -->
        <div class="col-md-3 custom-padding">
            <div class="form-group custom-form-group">
                <label class="input-label">Other Document</label>
                <input 
                    type="file" 
                    name="other_doc[]" 
                    class="form-control custom-input" 
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                    title="Upload a document (PDF, JPG, PNG, DOC, DOCX)" />
            </div>
        </div>
       

        <!-- Add More and Remove Icons -->
        <div class="col-md-3 custom-padding d-flex align-items-center">
            <i class="fas fa-plus-square text-success me-3 add-more-documents" 
               style="font-size: 1.5rem; cursor: pointer;" 
               title="Add More"></i>
            <i class="fas fa-trash-alt text-danger remove-field" 
               style="font-size: 1rem; cursor: pointer;" 
               title="Remove"></i>
        </div>
        
    </div>

    <!-- Aadhaar Upload Field (Only Once) -->
    
       
    </div>
    <div class="col-md-3 col-lg-3 custom-padding">
            <div class="form-group custom-form-group">
                <label class="input-label">Aadhar Upload Document</label>
                <input 
                    type="file" 
                    name="adhar_upload_doc" 
                    class="form-control custom-input" 
                    accept=".pdf,.jpg,.jpeg,.png" 
                    title="Please upload a valid Aadhar document (PDF, JPG, JPEG, or PNG)" />
            </div>
        </div>


    </div>
    </div>
</div>

   <div class="submit-btn-container">
      <button type="submit" class="btn btn-secondary submit-btn">Submit</button>
    </div>
  
</form>
   </div>
    </div>
  </div>

    <!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVendorModalLabel">Add Vendor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     
      <form method="POST" id="add_vendor" action="add_vendor.php" enctype="multipart/form-data">

          <!-- Vendor Form Fields -->
          <!-- <form action="vendordb.php" method="POST" id="customer_form" enctype="multipart/form-data"> -->
          <div class="row">
            <!-- Vendor Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="vendor_name" class="custom-label">Vendor Name</label>
                <input type="text" name="vendor_name" id="vendor_name" class="form-control custom-input" placeholder="Enter Vendor Name">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="gstin" class="custom-label">GSTIN</label>
                <input type="text" name="gstin" id="gstin" class="form-control custom-input" placeholder="Enter GSTIN">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="contact_person" class="custom-label">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control custom-input" placeholder="Enter Contact Person">
              </div>
            </div>

            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="phone_number" class="custom-label">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control custom-input" placeholder="Enter Phone Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="email" class="custom-label">Email</label>
                <input type="email" name="email" id="email" class="form-control custom-input" placeholder="Enter Email">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="supporting_documents" class="custom-label">Documents</label>
                <input type="file" name="supporting_documents" id="supporting_documents" class="form-control custom-input">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="services_provided" class="custom-label">Services Provided</label>
                <select class="form-control" name="service_type[]" id="service_type" style="appearance: none; padding-right: 2.5rem;">
  <option value="" disabled selected>Select Role</option>
  <?php echo $options; ?> <!-- Dynamically populated options -->
</select>

              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="vendor_type" class="custom-label">Vendor Type</label>
                <select name="vendor_type" id="vendor_type" class="form-control custom-input">
                  <option value="Individual">Individual</option>
                  <option value="Company">Company</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>

            <!-- Address Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="address_line1" class="custom-label">Flat, House No., Building</label>
                <input type="text" name="address_line1" id="address_line1" class="form-control custom-input" placeholder="Enter Address Line 1">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="address_line2" class="custom-label">Area, Street, Sector</label>
                <input type="text" name="address_line2" id="address_line2" class="form-control custom-input" placeholder="Enter Address Line 2">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="pincode" class="custom-label">Pincode</label>
                <input type="text" name="pincode" id="pincode" class="form-control custom-input" placeholder="Enter Pincode" maxlength="6">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="landmark" class="custom-label">Landmark</label>
                <input type="text" name="landmark" id="landmark" class="form-control custom-input" placeholder="Enter Landmark">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="city" class="custom-label">City</label>
                <input type="text" name="city" id="city" class="form-control custom-input" placeholder="Enter City">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="state" class="custom-label">State</label>
                <select name="state" id="state" class="form-control custom-input">
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

            <!-- Bank Details -->
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="bank_name" class="custom-label">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control custom-input" placeholder="Enter Bank Name">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="account_number" class="custom-label">Account Number</label>
                <input type="text" name="account_number" id="account_number" class="form-control custom-input" placeholder="Enter Account Number">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="ifsc" class="custom-label">IFSC Code</label>
                <input type="text" name="ifsc" id="ifsc" class="form-control custom-input" placeholder="Enter IFSC Code">
              </div>
            </div>
            <div class="col-md-6 col-lg-3 custom-padding">
              <div class="form-group custom-form-group">
                <label for="branch" class="custom-label">Branch</label>
                <input type="text" name="branch" id="branch" class="form-control custom-input" placeholder="Enter Branch">
              </div>
            </div>
          </div>
          <!-- Submit Button -->
          <div class="submit-btn-container">
            <button type="submit" class="btn btn-secondary submit-btn">Submit</button>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
</div>



    
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <script>
document.addEventListener('DOMContentLoaded', function () {
    // Add More Documents
    document.querySelectorAll('.add-more-documents').forEach(function (button) {
        button.addEventListener('click', function () {
            const documentContainer = document.getElementById('document-container');
            const documentEntry = documentContainer.querySelector('.document-entry');
            const newDocumentEntry = documentEntry.cloneNode(true);

            // Clear input values in the cloned entry
            newDocumentEntry.querySelectorAll('input').forEach(function (input) {
                input.value = '';
            });

            // Show the remove button in the new entry
            const removeButton = newDocumentEntry.querySelector('.remove-field');
            if (removeButton) {
                removeButton.style.display = 'inline';
            }

            // Append the cloned entry
            documentContainer.appendChild(newDocumentEntry);

            // Add event listener for remove button in the new entry
            const newRemoveButton = newDocumentEntry.querySelector('.remove-field');
            if (newRemoveButton) {
                newRemoveButton.addEventListener('click', function () {
                    newDocumentEntry.remove();
                });
            }
        });
    });

    // Remove Document Entry
    document.querySelectorAll('.remove-field').forEach(function (button) {
        button.addEventListener('click', function () {
            this.closest('.document-entry').remove();
        });
    });
});
</script>


<script>


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


<script>
  document.getElementById('reference').addEventListener('change', function() {
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
</script>

    <!-- <script>
    
    document.querySelector('#addVendorModal form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Collect field values
    const requestData = {
        vendor_name: document.querySelector('#popup_vendor_name').value.trim(),
        gstin: document.querySelector('#gstin').value.trim(),
        phone_number: document.querySelector('#phone_number').value.trim(),
        email: document.querySelector('#email').value.trim(),
        vendor_type: document.querySelector('#vendor_type').value,
        bank_name: document.querySelector('#bank_name').value.trim(),
        account_number: document.querySelector('#account_number').value.trim(),
        address: document.querySelector('#address').value.trim(),
        services_provided: document.querySelector('#services_provided').value.trim(),
        additional_notes: document.querySelector('#additional_notes').value.trim(),
        ifsc: document.querySelector('#ifsc').value.trim(),
        payment_terms: document.querySelector('#payment_terms').value.trim(),
    };

    // Basic validation
    if (!requestData.vendor_name || !requestData.phone_number || !requestData.email) {
        alert('Please fill in all required fields.');
        return;
    }
    fetch('add_vendor.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
})
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Vendor added successfully
            const modal = bootstrap.Modal.getInstance(document.getElementById('addVendorModal'));
            modal.hide();

            const vendorNameSelect = document.getElementById('vendor_name');
            const newOption = document.createElement('option');
            newOption.value = data.vendor.id;
            newOption.textContent = data.vendor.vendor_name;
            vendorNameSelect.appendChild(newOption);

            vendorNameSelect.value = data.vendor.id;
            alert('Vendor added successfully!');
        } else {
            // Display backend error message
            console.error('Backend Error:', data.message);
            alert(`An error occurred: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('An unexpected error occurred. Please try again later.');
    });


    </script> -->
    
    
     
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
                  class="form-control custom-input form-control" 
                  placeholder="Enter Document Name" 
                   
                  title="Enter the document name" />
              </div>
              <div class="col-md-6">
                <label class="input-label">Upload Document</label>
                <input 
                  type="file" 
                  name="other_doc[]" 
                  class="form-control custom-input form-control" 
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


document.addEventListener('DOMContentLoaded', () => {
  const referenceDropdown = document.querySelector('#reference');
  const vendorFields = document.querySelector('#vendorFields');
  const vendorContactField = document.querySelector('#vendorContactField');
  const bankFields = document.querySelectorAll('#bank_name, #branch, #bank_account_no, #ifsc_code');
  const vendorNameDropdown = document.querySelector('#vendor_name');
  const vendorContactInput = document.querySelector('#vendor_contact');

  // Listen to reference dropdown changes
  referenceDropdown.addEventListener('change', () => {
    const selectedReference = referenceDropdown.value;

    if (selectedReference === 'vendors') {
      // Show vendor fields
      vendorFields.style.display = 'block';
      vendorContactField.style.display = 'block';

      // Make bank fields readonly
      bankFields.forEach(field => field.setAttribute('readonly', true));

      // Fetch vendor data dynamically (mock API call)
      fetch('get_vendor_data.php') // Replace with your actual endpoint
        .then(response => response.json())
        .then(data => {
          // Populate the vendor dropdown
          vendorNameDropdown.innerHTML = '<option value="" disabled selected>Select Vendor</option>';
          data.vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.id;
            option.textContent = vendor.name;
            option.dataset.phone = vendor.phone;
            option.dataset.bankName = vendor.bank_name;
            option.dataset.branch = vendor.branch;
            option.dataset.accountNo = vendor.account_no;
            option.dataset.ifsc = vendor.ifsc;
            vendorNameDropdown.appendChild(option);
          });
        })
        .catch(error => console.error('Error fetching vendors:', error));
    } else if (selectedReference === 'ayush') {
      // Hide vendor fields
      vendorFields.style.display = 'none';
      vendorContactField.style.display = 'none';

      // Make bank fields editable
      bankFields.forEach(field => field.removeAttribute('readonly'));
    }
  });

  // Listen to vendor dropdown changes
  vendorNameDropdown.addEventListener('change', () => {
    const selectedVendor = vendorNameDropdown.options[vendorNameDropdown.selectedIndex];

    if (selectedVendor) {
      // Populate vendor contact and bank details
      vendorContactInput.value = selectedVendor.dataset.phone || '';
      document.querySelector('#bank_name').value = selectedVendor.dataset.bankName || '';
      document.querySelector('#branch').value = selectedVendor.dataset.branch || '';
      document.querySelector('#bank_account_no').value = selectedVendor.dataset.accountNo || '';
      document.querySelector('#ifsc_code').value = selectedVendor.dataset.ifsc || '';
    }
  });
});

    </script>

<script>
  // Cache to hold form data temporarily
  let formCache = {};

  // Function to save form data
  function cacheFormData() {
    const form = document.getElementById("employeeForm");
    formCache = {};
    [...form.elements].forEach((field) => {
      if (field.name) {
        formCache[field.name] = field.value; // Save field values
      }
    });
  }

  // Function to restore form data
  function restoreFormData() {
    const form = document.getElementById("employeeForm");
    [...form.elements].forEach((field) => {
      if (field.name && formCache[field.name] !== undefined) {
        field.value = formCache[field.name]; // Restore field values
      }
    });
  }

  // Event listener for opening the modal
  document.getElementById("addVendorBtn").addEventListener("click", () => {
    cacheFormData(); // Save form data before modal opens
  });

  // Event listener for closing the modal
  document.getElementById("addVendorModal").addEventListener("hidden.bs.modal", () => {
    restoreFormData(); // Restore form data after modal closes
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