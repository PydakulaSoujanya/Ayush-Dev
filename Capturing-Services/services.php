<?php
// Include database connection
include '../config.php';

// Fetch existing customers
$customers = [];
$query = "SELECT id, customer_name FROM customer_master_new";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
}

// Handle new customer addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    $name = $_POST['new_customer_name'];
    $email = $_POST['new_customer_email'];
    $phone = $_POST['new_customer_phone'];

    $insertQuery = "INSERT INTO customer_master_new (customer_name, email, emergency_contact_number) VALUES ('$name', '$email', '$emergency_contact_number')";
    mysqli_query($conn, $insertQuery);

    // Optionally, reload to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
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
  <title>Service Request Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

   <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    

  .highlighted-total-price {
    font-size: 1.5rem; /* Larger font size */
    font-weight: bold; /* Bold font style */
    color: #1d4ed8; /* Optional: Highlighted color */
    background-color: #f3f4f6; /* Optional: Light gray background */
    border: 2px solid #1d4ed8; /* Optional: Colored border */
    text-align: center; /* Center-align text */
    padding: 10px; /* Add spacing for better visibility */
  }
  </style>
</head>



<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <?php
  include '../navbar.php';
  ?>
 <div class="container mt-7">
 <div class="card custom-card">
      <div class="card-header custom-card-header">Capturing Service Request</div>
      <div class="card-body">
  <!-- <h3 class="mb-4">Capturing Service Request Form</h3>
  <div class="form-section"> -->
    <form action="services_db.php" method="POST">
    <div class="row mt-3">
  
    <input type="hidden" id="customer_id" name="customer_id" >
  
        <!-- Customer Name -->
        <div class="col-md-6 col-lg-3">
  <div class="form-group">
    <label class="form-group">Customer Name</label>
    <div style="display: flex; align-items: center;">
      <input
        id="customer-name"
        class="form-control"
        name="customer_name"
        oninput="if (this.value.length >= 2) searchCustomers(this.value)" 
        placeholder="Search by phone"
        style="flex: 1; margin-right: 10px;"
      />
      <button
        type="button"
        class="btn btn-secondary btn-sm"
        data-toggle="modal"
        data-target="#addCustomerModal"
      >
        +
      </button>
    </div>
    <div class="suggestionItem">
      <ul id="customerList"></ul>
    </div>
  </div>
</div>

<!-- Phone Number -->
<div class="col-md-6 col-lg-3">
  <div class="form-group">
    <label class="form-group">Phone Number</label>
    <input type="text" id="emergency_contact_number" class="form-control" name="emergency_contact_number" placeholder="Phone Number" readonly />
  </div>
</div>


        <!-- Patient Name -->
        <div class="col-md-6 col-lg-3">
          <div class="form-group">
            <label class="form-group">Patient Name</label>
            <input type="text" class="form-control" name="patient_name" id="patient_name" placeholder="Patient Name" readonly />
          </div>
        </div>

        <!-- Relationship -->
        <div class="col-md-6 col-lg-3">
          <div class="form-group">
            <label class="form-group">Patient Relation With Customer</label>
            <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Patient Relation With Customer" readonly />
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="form-group">
            <label class="form-group">Email</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Email" readonly />
          </div>
        </div>
        <!-- Enquiry Time -->
        <div class="col-md-6 col-lg-3">
          <div class="form-group">
            <label class="form-group">Enquiry Time</label>
            <input type="time" name="enquiry_time" class="form-control" id="enquiry-time" />
          </div>
        </div>

        <!-- Enquiry Date -->
        <div class="col-md-6 col-lg-3">
          <div class="input-field-container">
            <label class="form-group">Enquiry Date</label>
            <input type="date" class="form-control" name="enquiry_date" id="enquiry-date" />
          </div>
        </div>
      </div>

      <!-- Dynamic Fields for Service Details -->
      <div class="row">
      
      <div id="field-container">
    <div class="row field-set bordered-field">
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Start Date</label>
                <input type="date" class="form-control" name="from_date[]" id="fromDate" />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">End Date</label>
                <input type="date" class="form-control" name="end_date[]" id="endDate" />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
  <div class="form-group position-relative">
    <label class="form-group">Service Type</label>
    <select class="form-control" name="service_type[]" id="service_type" style="appearance: none; padding-right: 2.5rem;">
      <option value="" disabled selected>Select Service Type</option>
      <option value="care_taker">Care Taker</option>
      <option value="fully_trained_nurse">Fully Trained Nurse</option>
      <option value="semi_trained_nurse">Semi Trained Nurse</option>
      <option value="nannies">Nannies</option>
    </select>
    <span class="dropdown-icon position-absolute" style="top: 45px; right: 15px;">
      ▼
    </span>
  </div>
</div>
<!-- <div class="col-md-6 col-lg-3">
  <div class="form-group position-relative">
    <label class="form-group">Service Type</label>
    <select class="form-control" name="service_type[]" id="service_type" style="appearance: none; padding-right: 2.5rem;">
      <option value="" disabled selected>Select Service Type</option>
      <?php echo $options; ?>  
    </select>
    <span class="dropdown-icon position-absolute" style="top: 45px; right: 15px;">
      ▼
    </span>
  </div>
</div> -->

        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Service Duration (in Hours)</label>
                <select class="form-control" name="service_duration[]" id="service_duration">
                    <option value="" disabled selected>Select Service Duration</option>
                    <option value="8">8 Hours</option>
                    <option value="12">12 Hours</option>
                    <option value="24">24 Hours</option>
                </select>
                <span class="dropdown-icon position-absolute" style="top: 45px; right: 15px;">
      ▼
    </span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Total Days</label>
                <input type="number" class="form-control" name="total_days[]" id="total_days" placeholder="Total Days" readonly />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Per Day Service Price</label>
                <input type="text" class="form-control" name="per_day_service_price[]" placeholder="Service Price" id="per_day_service_price" readonly />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Total Service Price</label>
                <input type="text" class="form-control" name="service_price[]" id="service_price" placeholder="Service Price" readonly />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Discount Price</label>
                <input type="text" class="form-control" name="discount_price[]" id="discount_price" placeholder="Discount Price" />
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-group">Total Service Price (After Discount)</label>
                <input type="text" class="form-control total_service_price" id="total_service_price" name="total_service_price[]" placeholder="Total Price" readonly />
            </div>
        </div>
        <div class="col-md-12 text-right">
            <button id="add-field-set" type="button" class="btn btn-secondary mt-3">+ Add Services</button>
            <button type="button" class="btn btn-danger delete-service mt-3">Delete</button>
        </div>
    </div>
</div>
</div>
<!-- Highlighted Total Price Section -->
<div class="row">
<div class="col-lg-3"></div>
<div class="col-lg-3"></div>
<div class="col-lg-3"></div>
<div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
  <div class="form-group">
    <label class="form-group">Total Price</label>
    <input
      type="text"
      id="total_price"
      class="form-control highlighted-total-price text-align:right"
      name="total_price[]"
      readonly
      placeholder="Total Price"
    />
    </div>
  </div>
</div>

      <!-- Additional Inputs -->
         
      <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Enquiry Source</label>
            <select class="form-control" name="enquiry_source">
              <option value="" disabled selected>Select Enquiry Source</option>
              <option value="phone">Phone Call</option>
              <option value="email">Email</option>
              <option value="walkin">Walk-In</option>
              <option value="website">Website</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Priority Level</label>
            <select class="form-control" name="priority_level">
              <option value="" disabled selected>Select Priority Level</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Status</label>
            <select class="form-control" name="status">
              <option value="" disabled selected>Select Status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="booked">Booked</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Request Details</label>
            <input type="text" class="form-control" name="request_details" placeholder="Enter Request Details" />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Resolution Notes</label>
            <textarea class="form-control" rows="1" name="resolution_notes" placeholder="Enter Resolution Notes"></textarea>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group">
            <label class="form-group">Comments</label>
            <textarea class="form-control" rows="1" name="comments" placeholder="Enter Comments"></textarea>
          </div>
        </div>
     
      <!-- Submit Button -->
      <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
          </div>
    </form>
  </div>
</div>


  <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Increased modal size to large -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add New Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="add_customer.php" method="POST" enctype="multipart/form-data">
          <!-- First Row -->
        
          <!-- <h2 class="section-title1">Customer Details</h2> -->
          <div class="row">
            <!-- Are you a patient? -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Are you a patient?</label>
                <select class="form-control" id="patientStatus" name="patient_status" >
                  <option value="" disabled selected>Select an option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>

            <!-- Patient Name -->
            <div class="col-md-6 hidden" id="patientNameField">
              <div class="input-field-container">
                <label class="form-group">Patient Name</label>
                <input type="text" class="form-control" name="patient_name" placeholder="Enter patient name" required/>
              </div>
            </div>
          </div>

          <!-- Second Row -->
          <div class="row">
            <!-- Relationship with Patient -->
            <div class="col-md-6 hidden" id="relationshipField">
              <div class="input-field-container">
                <label class="form-group" for="relationship">Relationship with Patient</label>
                <select class="form-control" id="relationship" name="relationship">
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
            <!-- Customer Name -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Customer Name</label>
                <input type="text" class="form-control" name="customer_name" placeholder="Enter your name"  required/>
              </div>
            </div>
          </div>

          <!-- Third Row -->
          <div class="row">
            <!-- Contact Number -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Contact Number</label>
                <input type="text" class="form-control" name="emergency_contact_number" placeholder="Enter your emergency contact number"  required/>
              </div>
            </div>

            <!-- Blood Group -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Blood Group</label>
                <select class="form-control" name="blood_group" >
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

          <!-- Fourth Row -->
          <div class="row">
            <!-- Known Medical Conditions -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Known Medical Conditions</label>
                <input type="text" class="form-control" name="medical_conditions" placeholder="Enter known medical conditions"  />
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
      <div class="input-field-container">
        <label class="form-group">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" />
      </div>
    </div>
          </div>
         
          <!-- Fifth Row -->
          <div class="row">
            <!-- Patient Age -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Patient Age</label>
                <input type="number" class="form-control" name="patient_age" placeholder="Enter patient age" />
              </div>
            </div>

            <!-- Gender -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Gender</label>
                <select class="form-control" name="gender">
                  <option value="" disabled selected>Select gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Sixth Row -->
          <div class="row">
            <!-- Mobility Status -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Mobility Status</label>
                <select class="form-control" name="mobility_status" >
                  <option value="" disabled selected>Select Mobility Status</option>
                  <option value="Walking">Walking</option>
                  <option value="Wheelchair">Wheelchair</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>

            <!-- Discharge Summary Sheet -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="form-group">Discharge Summary Sheet</label>
                <input type="file" class="form-control" name="discharge" accept=".pdf,.doc,.docx,.txt" />
              </div>
            </div>
          </div>

      <div class="row">
  <!-- Pincode Field -->
  <div class="col-md-4">
    <div class="input-field-container">
      <label class="form-group">Pincode</label>
      <input 
        type="text" 
        name="pincode[]" 
        class="form-control" 
        placeholder="6 digits [0-9] PIN code" 
         
        pattern="\d{6}" 
        maxlength="6" />
    </div>
  </div>

  <!-- Flat, House No., Building, etc. Field -->
  <div class="col-md-8">
    <div class="input-field-container">
      <label class="form-group">Flat, House No., Building, Company, Apartment</label>
      <input 
        type="text" 
        name="address_line1[]"
        class="form-control" 
        placeholder="Enter Flat, House No., Building, etc." 
         />
    </div>
  </div>
</div>

<div class="row">
  <!-- Area, Street, Sector, Village Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="form-group">Area, Street, Sector, Village</label>
      <input 
        type="text" 
        name="address_line2[]"
        class="form-control" 
        placeholder="Enter Area, Street, Sector, Village" />
    </div>
  </div>

  <!-- Landmark Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="form-group">Landmark</label>
      <input 
        type="text" 
        name="landmark[]" 
        class="form-control" 
        placeholder="E.g. near Apollo Hospital" />
    </div>
  </div>
</div>

<div class="row">
  <!-- Town/City Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="form-group">Town/City</label>
      <input 
        type="text" 
       name="city[]"
        class="form-control" 
        placeholder="Enter Town/City" 
         required />
    </div>
  </div>

  <!-- State Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="form-group">State</label>
      <select 
        name="state[]" 
        class="form-control" 
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
</div>
</div>

<div class="text-center mt-4 mb-3">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
          </div>
          <!-- Submit Button -->
          <!-- <div class="row emp-submit mt-2">
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-secondary" name="submit" value="Submit">Submit</button>
            </div>
          </div> -->
        </form>
      </div>
    </div>
  </div>
</div>


<style>
  .modal-dialog {
    max-width: 50%; /* Set a larger modal width */
  }

  .input-field-container {
    margin-bottom: 15px;
  }

  .input-label {
    font-size: 14px;
    font-weight: bold;
    color: #A26D2B;
  }

  .styled-input {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
    border: 1px solid #A26D2B;
    border-radius: 5px;
  }

  .styled-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  /* Ensure input fields are responsive */
  .row {
    margin-bottom: 20px;
  }

  /* Hide elements by default */
  .hidden {
    display: none;
  }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script>
 let previousValues = [];
setInterval(() => {
  const inputs = document.querySelectorAll("input[name='service_price[]']");
  let values = Array.from(inputs).map(input => input.value);
  
  if (JSON.stringify(values) !== JSON.stringify(previousValues)) {
    updateTotalPrice();
    previousValues = values; // Update the reference to prevent repeated updates
  }
}, 500); // Check every 500ms


  // Show/Hide fields based on patient status selection
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
 
    $(document).ready(function () {
    // Function to add new field set
    $('#add-field-set').on('click', function () {
      
      // var newFieldSet = $('#field-container .field-set').first().clone(); // Clone the first field set
      newFieldSet.find('input').val(''); // Clear the input fields
      newFieldSet.find('select').prop('selectedIndex', 0); // Reset the selects to default option
      newFieldSet.find('#service_price').val(''); // Clear service price input
      newFieldSet.find('.delete-service').show(); // Show delete button in the new set
      $('#field-container').append(newFieldSet); // Append the new field set to the container
    });

    // Function to delete the field set
    $(document).on('click', '.delete-service', function () {
      $(this).closest('.field-set').remove(); // Remove the parent field set
    });
  });


    function handleAddCustomer() {
      const selectedValue = document.getElementById("customer-name").value;
      if (selectedValue === "add_customer") {
        $('#addCustomerModal').modal('show');
      }
    }

    function addNewCustomer() {
      const customerName = document.getElementById("new-customer-name").value.trim();
      const customerEmail = document.getElementById("new-customer-email").value.trim();
      const customerPhone = document.getElementById("new-customer-phone").value.trim();

      if (customerName && customerEmail && customerPhone) {
        // Send data to the server using AJAX
        fetch("add_customer.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `new_customer_name=${encodeURIComponent(customerName)}&new_customer_email=${encodeURIComponent(customerEmail)}&new_customer_phone=${encodeURIComponent(customerPhone)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Add the new customer to the dropdown
            const dropdown = document.getElementById("customer-name");
            const newOption = document.createElement("option");
            newOption.value = data.id; // Use the new customer ID
            newOption.text = customerName;
            dropdown.add(newOption, dropdown.options[dropdown.length - 1]);
            dropdown.value = data.id;

            // Auto-fill fields
            document.getElementById("contact_no").value = customerPhone;
            document.getElementById("email").value = customerEmail;

            // Hide the modal
            $('#addCustomerModal').modal('hide');
          } else {
            alert(data.error || "Failed to add customer. Please try again.");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An unexpected error occurred.");
        });
      } else {
        alert("Please fill in all fields.");
      }
    }
    function handleAddCustomer() {
  const selectedValue = document.getElementById("customer-name").value;
  
  if (selectedValue === "add_customer") {
    $('#addCustomerModal').modal('show');
  } else if (selectedValue) {
    populateCustomerDetails(selectedValue);
  }
}

function searchCustomers(search) {
    const customerList = document.getElementById("customerList");
    const inputFieldContactNo = document.getElementById("emergency_contact_number");
    const patientNameField = document.getElementById("patient_name");
    const patientRelationField = document.getElementById("relationship");
    const patientEmailField = document.getElementById("email");
    const customerId = document.getElementById("customer_id");

    // Clear previous suggestions but retain input values
    customerList.innerHTML = "";

    if (search.trim() !== "") {
        fetch(`search_customer.php?search=${search}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const customers = data.data;

                    // Create list items for suggestions
                    customers.forEach((customer, index) => {
                        const listItem = document.createElement("li");

                        // Create the HTML for each customer
                        listItem.innerHTML = `
    <div>
        <strong>${customer.id}</strong> - <strong>${customer.customer_name}</strong> - ${customer.emergency_contact_number}
        <ul style="margin-top: 5px; padding-left: 20px;">
            <li>
                <input 
                    type="checkbox" 
                    id="patient_${index}" 
                    data-customer-name="${customer.customer_name || 'Unknown'}" 
                    data-customer-id="${customer.id}"
                    data-contact-number="${customer.emergency_contact_number || 'Unknown'}" 
                    data-patient-name="${customer.patient_name || 'Unknown'}" 
                    data-patient-relation="${customer.relationship || 'Unknown'}" 
                    data-patient-email="${customer.email || 'Unknown'}" 
                    onchange="selectPatient(this)" 
                />
                <label for="patient_${index}">
                    ${customer.patient_name || 'Unknown'} (${customer.relationship || 'Unknown'})
                </label>
            </li>
        </ul>
    </div>`;
                        customerList.appendChild(listItem);
                    });
                } else {
                    console.error("Error: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error fetching customer data:", error);
            });
    }
}

function selectPatient(checkbox) {
    const inputFieldCustomerName = document.getElementById("customer-name");
    const inputFieldContactNo = document.getElementById("emergency_contact_number");
    const patientNameField = document.getElementById("patient_name");
    const patientRelationField = document.getElementById("relationship");
    const patientEmailField = document.getElementById("email"); // Ensure this field is included
    const customerList = document.getElementById("customerList");
    const customerId = document.getElementById("customer_id");

if (checkbox.checked) {
        // Populate all fields with data from the checkbox
        inputFieldCustomerName.value = checkbox.dataset.customerName || "Unknown";
        inputFieldContactNo.value = checkbox.dataset.contactNumber || "Unknown";
        patientNameField.value = checkbox.dataset.patientName || "Unknown";
        patientRelationField.value = checkbox.dataset.patientRelation || "Unknown";
        patientEmailField.value = checkbox.dataset.patientEmail || "Unknown"; // Populate the email field
        customerId.value = checkbox.dataset.customerId;
  
        customerList.innerHTML = "";
        const allCheckboxes = document.querySelectorAll('#customerList input[type="checkbox"]');
        allCheckboxes.forEach((cb) => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
        // Uncheck other checkboxes if one is selected
        // const allCheckboxes = document.querySelectorAll('#customerList input[type="checkbox"]');
        // allCheckboxes.forEach((cb) => {
        //     if (cb !== checkbox) {
        //         cb.checked = false;
        //     }
        // });
      } else {
        // Clear all fields if the checkbox is unchecked
        inputFieldCustomerName.value = "";
        inputFieldContactNo.value = "";
        patientNameField.value = "";
        patientRelationField.value = "";
        patientEmailField.value = ""; // Clear email field
        customerId.value = "";
    }
}
//     } else {
//         // Clear all fields if the checkbox is unchecked
//         inputFieldCustomerName.value = "";
//         inputFieldContactNo.value = "";
//         patientNameField.value = "";
//         patientRelationField.value = "";
//     }
// }

function updateTotalPrice() {
  let totalPrice = 0;

  // Loop through all service price and discount price fields
  const servicePriceInputs = document.querySelectorAll("input[name='service_price[]']");
  const discountPriceInputs = document.querySelectorAll("input[name='discount_price[]']");
  const totalServicePriceFields = document.querySelectorAll(".total_service_price");

  servicePriceInputs.forEach((servicePriceInput, index) => {
    const servicePrice = parseFloat(servicePriceInput.value) || 0; // Get service price
    const discountPrice = parseFloat(discountPriceInputs[index].value) || 0; // Get discount price

    // If discount price is provided, apply it to the service price
    const discountedPrice = servicePrice - discountPrice;

    // Update the Total Service Price field for this service
    totalServicePriceFields[index].value = discountedPrice < 0 ? 0 : discountedPrice.toFixed(2);

    // Add the discounted service price to the overall total
    totalPrice += discountedPrice < 0 ? 0 : discountedPrice;
  });

  // Update the total price field with the final total value
  const totalPriceInput = document.getElementById("total_price");
  totalPriceInput.value = totalPrice.toFixed(2);
}

// Attach event listeners to all input fields to update total price when any field changes
function attachEventListeners() {
  document.querySelectorAll("input[name='service_price[]']").forEach(input => {
    input.addEventListener("input", updateTotalPrice);
  });

  document.querySelectorAll("input[name='discount_price[]']").forEach(input => {
    input.addEventListener("input", updateTotalPrice);
  });
}

// Function to add new service fields dynamically
document.getElementById("add-field-set").addEventListener("click", function () {
  const fieldSetContainer = document.getElementById("field-container");

  // Find the first field set (we'll clone it)
  const fieldSetTemplate = fieldSetContainer.querySelector(".field-set");
  const newFieldSet = fieldSetTemplate.cloneNode(true); // Clone the first field set

  // Append the new field set
  fieldSetContainer.appendChild(newFieldSet);

  // Show the delete button for new field sets (but hide it for the first field set)
  const deleteButton = newFieldSet.querySelector(".delete-service");
  deleteButton.style.display = 'inline-block'; // Show delete button for new field sets

  // Reset the input fields in the new service set
  const inputs = newFieldSet.querySelectorAll("input");
  inputs.forEach(input => {
    input.value = ''; // Clear the input fields
  });

  // Re-attach event listeners to newly added fields
  attachEventListeners();
});

// Function to delete a service field
document.addEventListener("click", function (e) {
  if (e.target && e.target.classList.contains("delete-service")) {
    const fieldSet = e.target.closest(".field-set");
    if (fieldSet) {
      fieldSet.remove();
      updateTotalPrice(); 
    }
  }
});

// Initialize event listeners for existing services
attachEventListeners();

// Hide the delete button for the first field set
document.addEventListener("DOMContentLoaded", function () {
  const firstFieldSet = document.querySelector(".field-set");
  const deleteButton = firstFieldSet.querySelector(".delete-service");
  if (deleteButton) {
    deleteButton.style.display = 'none'; // Hide delete button in the first field set
  }
});


 function setCurrentDateTime() {
    const now = new Date();

    // Format time as HH:MM
    const hours = String(now.getHours()).padStart(2, "0");
    const minutes = String(now.getMinutes()).padStart(2, "0");
    const currentTime = `${hours}:${minutes}`;

    // Format date as YYYY-MM-DD
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const date = String(now.getDate()).padStart(2, "0");
    const currentDate = `${year}-${month}-${date}`;

    // Set the values of the input fields
    document.getElementById("enquiry-time").value = currentTime;
    document.getElementById("enquiry-date").value = currentDate;
  }

  // Set current date and time on page load
  document.addEventListener("DOMContentLoaded", setCurrentDateTime);



  document.addEventListener('DOMContentLoaded', function () {
    const fieldContainer = document.getElementById("field-container");

    // Set current date and time on page load
    setCurrentDateTime();

    // Handle the "+" button to add new field sets
    // document.getElementById("add-field-set").addEventListener("click", function () {
    //     addFieldSet();
    // });

    // Delegate input events to the container for dynamic field sets
    fieldContainer.addEventListener("input", handleInputEvent);

    function setCurrentDateTime() {
        const now = new Date();

        // Format time as HH:MM
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        const currentTime = `${hours}:${minutes}`;

        // Format date as YYYY-MM-DD
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, "0");
        const date = String(now.getDate()).padStart(2, "0");
        const currentDate = `${year}-${month}-${date}`;

        document.getElementById("enquiry-time").value = currentTime;
        document.getElementById("enquiry-date").value = currentDate;
    }


    function addFieldSet() {
        const firstFieldSet = fieldContainer.querySelector(".field-set");
        const clonedFieldSet = firstFieldSet.cloneNode(true);

        // Clear inputs in the cloned field set
        const inputs = clonedFieldSet.querySelectorAll("input, select");
        inputs.forEach((input) => {
            if (input.type === "text" || input.type === "number" || input.type === "date") {
                input.value = "";
            } else if (input.tagName.toLowerCase() === "select") {
                input.selectedIndex = 0;
            }

            if (input.id) {
                input.id = `${input.id}_${Date.now()}`;
            }
        });

        // Remove the "+" button from the cloned set
        const addButton = clonedFieldSet.querySelector("#add-field-set");
        if (addButton) {
            addButton.remove();
        }

        fieldContainer.appendChild(clonedFieldSet);
    }

    document.addEventListener("DOMContentLoaded", function () {
  const fieldContainer = document.querySelector(".input-field-container");
  const addFieldSetButton = document.getElementById("add-field-set");
  const totalPriceInput = document.getElementById("total_price");

  // Function to add a new input field for service price
  function addFieldSet() {
    const newInput = document.createElement("input");
    newInput.type = "text";
    newInput.className = "styled-input";
    newInput.name = "service_price[]";
    newInput.placeholder = "Service Price";

    // Add event listener to update total when value changes
    newInput.addEventListener("input", calculateTotalPrice);
    fieldContainer.insertBefore(newInput, addFieldSetButton.parentElement);
  }

  // Function to calculate and display the total
  function calculateTotalPrice() {
    const inputs = document.querySelectorAll("input[name='service_price[]']");
    let total = 0;

    inputs.forEach((input) => {
      const value = parseFloat(input.value) || 0; // Default to 0 if input is empty or invalid
      total += value;
    });

    totalPriceInput.value = total.toFixed(2); // Display the total
  }

  // Add event listener to the "Add Services" button
  addFieldSetButton.addEventListener("click", addFieldSet);

  // Add initial event listener to the first input field
  const initialInput = document.querySelector("input[name='service_price[]']");
  initialInput.addEventListener("input", calculateTotalPrice);
});

    function handleInputEvent(event) {
        const target = event.target;

        if (target.closest(".field-set")) {
            const fieldSet = target.closest(".field-set");
            const fromDateInput = fieldSet.querySelector('input[name^="from_date"]');
            const endDateInput = fieldSet.querySelector('input[name^="end_date"]');
            const totalDaysInput = fieldSet.querySelector('input[name^="total_days"]');
            const serviceDurationInput = fieldSet.querySelector('select[name^="service_duration"]');
            const serviceTypeInput = fieldSet.querySelector('select[name^="service_type"]');
            const perDayServicePriceInput = fieldSet.querySelector('input[name^="per_day_service_price"]');
            const servicePriceInput = fieldSet.querySelector('input[name^="service_price"]');

            if (fromDateInput && endDateInput && totalDaysInput) {
                calculateTotalDays(fromDateInput, endDateInput, totalDaysInput);
            }

            if (serviceDurationInput && serviceTypeInput && perDayServicePriceInput && servicePriceInput && totalDaysInput.value) {
                calculateServiceCharge(serviceTypeInput, serviceDurationInput, totalDaysInput, perDayServicePriceInput, servicePriceInput);
            }
        }
    }

    function calculateTotalDays(fromDateInput, endDateInput, totalDaysInput) {
        const fromDate = new Date(fromDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (fromDate && endDate && !isNaN(fromDate) && !isNaN(endDate)) {
            const timeDifference = endDate - fromDate;
            const totalDays = timeDifference / (1000 * 3600 * 24) + 1;

            if (totalDays >= 0) {
                totalDaysInput.value = totalDays;
            } else {
                totalDaysInput.value = "";
                alert("End date cannot be before the From date.");
            }
        } else {
            totalDaysInput.value = "";
        }
    }

    function calculateServiceCharge(serviceTypeInput, serviceDurationInput, totalDaysInput, perDayServicePriceInput, servicePriceInput) {
        const serviceType = serviceTypeInput.value;
        const totalDays = parseInt(totalDaysInput.value, 10);
        const serviceDuration = parseInt(serviceDurationInput.value, 10);

        if (serviceType && totalDays > 0 && serviceDuration) {
            fetchServiceDetails(serviceType).then(serviceDetails => {
                if (serviceDetails) {
                    let dailyRate = 0;

                    if (serviceDuration === 8) {
                        dailyRate = parseFloat(serviceDetails.daily_rate_8_hours);
                    } else if (serviceDuration === 12) {
                        dailyRate = parseFloat(serviceDetails.daily_rate_12_hours);
                    } else if (serviceDuration === 24) {
                        dailyRate = parseFloat(serviceDetails.daily_rate_24_hours);
                    }

                    if (!isNaN(dailyRate)) {
                        const totalServicePrice = dailyRate * totalDays;
                        perDayServicePriceInput.value = dailyRate.toFixed(2);
                        servicePriceInput.value = totalServicePrice.toFixed(2);
                    } else {
                        perDayServicePriceInput.value = "";
                        servicePriceInput.value = "Rate not available";
                    }
                }
            }).catch(error => {
                console.error("Error fetching service details:", error);
            });
        } else {
            perDayServicePriceInput.value = "";
            servicePriceInput.value = "";
        }
    }


    function applyDiscount() {
      const servicePriceInput = document.getElementById("service_price");
      const discountPriceInput = document.getElementById("discount_price");
      const totalServicePriceInput = document.getElementById("total_service_price");

      // Get the values
      const servicePrice = parseFloat(servicePriceInput.value) || 0;
      const discountPrice = parseFloat(discountPriceInput.value) || 0;

      // Apply discount to service price
      const discountedPrice = servicePrice - discountPrice;

      // Ensure the total price doesn't go below zero
      totalServicePriceInput.value = (discountedPrice < 0 ? 0 : discountedPrice).toFixed(2);
    }

    // Attach event listeners to update the total service price whenever service price or discount price changes
    document.getElementById("service_price").addEventListener("input", applyDiscount);
    document.getElementById("discount_price").addEventListener("input", applyDiscount);
    function fetchServiceDetails(serviceType) {
        return new Promise((resolve, reject) => {
            fetch("get_service_details.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `service_type=${encodeURIComponent(serviceType)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resolve(data.serviceDetails);
                    } else {
                        reject("Service not found.");
                    }
                })
                .catch(error => reject(error));
        });
    }
});



</script>

</body>
</html>
