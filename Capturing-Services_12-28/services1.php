<?php
// Include database connection
include '../config.php';

// Fetch existing customers
$customers = [];
$query = "SELECT id, customer_name FROM customer_master";
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

    $insertQuery = "INSERT INTO customer_master (customer_name, email, emergency_contact_number) VALUES ('$name', '$email', '$emergency_contact_number')";
    mysqli_query($conn, $insertQuery);

    // Optionally, reload to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
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
    @media (max-width: 768px) {
  .form-section {
    padding: 15px;
    margin: 10px;
  }

  .styled-input {
    font-size: 12px;
    padding: 8px;
  }

  .input-label {
    font-size: 12px;
  }
}

@media (max-width: 576px) {
  .form-section {
    padding: 10px;
    margin: 5px;
  }

  .styled-input {
    font-size: 12px;
    padding: 6px;
  }

  .input-label {
    font-size: 11px;
  }

  button {
    font-size: 14px;
    padding: 8px 12px;
  }
}

/* Form Responsiveness */
.row {
  margin: 0 -10px;
}

.col-md-6, .col-md-12 {
  padding: 0 10px;
}

.add-button {
    margin-top: 20px;
    padding: 10px 15px;
    font-size: 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .add-button:hover {
    background-color: #0056b3;
  }

  .bordered-field {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
  }

  .input-field-container {
    margin-bottom: 15px;
  }

  .input-label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
  }
  </style>
</head>
<body>
  <?php
  include '../navbar.php';
  ?>
 <div class="container mt-7">
  <h3 class="mb-4">Capturing Service Request Form</h3>
  <div class="form-section">
    <form action="services_db.php" method="POST">
      <div class="row">
        <!-- Customer Name -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Customer Name</label>
            <div style="display: flex; align-items: center;">
              <input
                id="customer-name"
                class="styled-input"
                name="customer_name"
                oninput="if (this.value.length >= 3) searchCustomers(this.value)"
                placeholder="Search by name or phone"
                style="flex: 1; margin-right: 10px;"
              />
              <button
                type="button"
                class="btn btn-primary btn-sm"
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
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Phone Number</label>
            <input type="text" id="emergency_contact_number" class="styled-input" name="emergency_contact_number" placeholder="Phone Number" readonly />
          </div>
        </div>

        <!-- Patient Name -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Patient Name</label>
            <input type="text" class="styled-input" name="patient_name" id="patient_name" placeholder="Patient Name" readonly />
          </div>
        </div>

        <!-- Relationship -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Patient Relation With Customer</label>
            <input type="text" class="styled-input" name="relationship" id="relationship" placeholder="Patient Relation With Customer" readonly />
          </div>
        </div>

        <!-- Enquiry Time -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Enquiry Time</label>
            <input type="time" name="enquiry_time" class="styled-input" id="enquiry-time" />
          </div>
        </div>

        <!-- Enquiry Date -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Enquiry Date</label>
            <input type="date" class="styled-input" name="enquiry_date" id="enquiry-date" />
          </div>
        </div>
      </div>

      <!-- Dynamic Fields for Service Details -->
      <div id="field-container">
  <div class="row field-set bordered-field">
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Start Date</label>
        <input type="date" class="styled-input" name="from_date[]" id="fromDate" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">End Date</label>
        <input type="date" class="styled-input" name="end_date[]" id="endDate"/>
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Total Days</label>
        <input type="number" class="styled-input" name="total_days[]"   id="total_days"  placeholder="Total Days" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Service Duration (in Hours)</label>
        <select class="styled-input" name="service_duration[]" id="service_duration">
          <option value="" disabled selected>Select Service Duration</option>
          <option value="8">8 Hours</option>
          <option value="12">12 Hours</option>
          <option value="24">24 Hours</option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Service Type</label>
        <select class="styled-input" name="service_type[]" id="service_type">
          <option value="" disabled selected>Select Service Type</option>
          <option value="care_taker">Care Taker</option>
          <option value="fully_trained_nurse">Fully Trained Nurse</option>
          <option value="semi_trained_nurse">Semi Trained Nurse</option>
          <option value="nannies">Nannies</option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Per Day Service Price</label>
        <input type="text" class="styled-input" name="per_day_service_price[]" placeholder="Service Price" id="per_day_service_price" readonly />
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Total Service Price</label>
        <input type="text" class="styled-input" name="service_price[]" placeholder="Service Price" id="service_price" readonly />
        <div class="col-md-12 text-right">
          <button id="add-field-set" type="button" class="btn btn-primary mt-3">+ Add Services</button>
        </div>
      </div>
    </div>
   
  </div>
</div>
<div class="col-md-6">
    <div class="input-field-container">
      <label class="input-label">Total Price of All Services</label>
      <input type="text" class="styled-input" id="total_price_all_services" readonly placeholder="Total Price" />
    </div>
  </div>

      <!-- Additional Inputs -->
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Enquiry Source</label>
            <select class="styled-input" name="enquiry_source">
              <option value="" disabled selected>Select Enquiry Source</option>
              <option value="phone">Phone Call</option>
              <option value="email">Email</option>
              <option value="walkin">Walk-In</option>
              <option value="website">Website</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Priority Level</label>
            <select class="styled-input" name="priority_level">
              <option value="" disabled selected>Select Priority Level</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Status</label>
            <select class="styled-input" name="status">
              <option value="" disabled selected>Select Status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="booked">Booked</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Request Details</label>
            <input type="text" class="styled-input" name="request_details" placeholder="Enter Request Details" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Resolution Notes (Timings)</label>
            <textarea class="styled-input" rows="1" name="resolution_notes" placeholder="Enter Resolution Notes"></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Comments</label>
            <textarea class="styled-input" rows="1" name="comments" placeholder="Enter Comments"></textarea>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary mt-3">Submit</button>
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
          <div class="row">
            <!-- Are you a patient? -->
            <div class="col-md-6">
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
            <div class="col-md-6 hidden" id="patientNameField">
              <div class="input-field-container">
                <label class="input-label">Patient Name</label>
                <input type="text" class="styled-input" name="patient_name" placeholder="Enter patient name" />
              </div>
            </div>
          </div>

          <!-- Second Row -->
          <div class="row">
            <!-- Relationship with Patient -->
            <div class="col-md-6 hidden" id="relationshipField">
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
            <!-- Customer Name -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Customer Name</label>
                <input type="text" class="styled-input" name="customer_name" placeholder="Enter your name" required />
              </div>
            </div>
          </div>

          <!-- Third Row -->
          <div class="row">
            <!-- Contact Number -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Contact Number</label>
                <input type="text" class="styled-input" name="emergency_contact_number" placeholder="Enter your emergency contact number" required />
              </div>
            </div>

            <!-- Blood Group -->
            <div class="col-md-6">
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

          <!-- Fourth Row -->
          <div class="row">
            <!-- Known Medical Conditions -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Known Medical Conditions</label>
                <input type="text" class="styled-input" name="medical_conditions" placeholder="Enter known medical conditions" required />
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
      <div class="input-field-container">
        <label class="input-label">Email</label>
        <input type="email" class="styled-input" name="email" id="email" placeholder="Enter Email" />
      </div>
    </div>
          </div>
         
          <!-- Fifth Row -->
          <div class="row">
            <!-- Patient Age -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Patient Age</label>
                <input type="number" class="styled-input" name="patient_age" placeholder="Enter patient age" />
              </div>
            </div>

            <!-- Gender -->
            <div class="col-md-6">
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
          </div>

          <!-- Sixth Row -->
          <div class="row">
            <!-- Mobility Status -->
            <div class="col-md-6">
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

            <!-- Discharge Summary Sheet -->
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Discharge Summary Sheet</label>
                <input type="file" class="styled-input" name="discharge" accept=".pdf,.doc,.docx,.txt" />
              </div>
            </div>
          </div>

      <div class="row">
  <!-- Pincode Field -->
  <div class="col-md-4">
    <div class="input-field-container">
      <label class="input-label">Pincode</label>
      <input 
        type="text" 
        name="pincode" 
        class="styled-input" 
        placeholder="6 digits [0-9] PIN code" 
        required 
        pattern="\d{6}" 
        maxlength="6" />
    </div>
  </div>

  <!-- Flat, House No., Building, etc. Field -->
  <div class="col-md-8">
    <div class="input-field-container">
      <label class="input-label">Flat, House No., Building, Company, Apartment</label>
      <input 
        type="text" 
        name="address_line1" 
        class="styled-input" 
        placeholder="Enter Flat, House No., Building, etc." 
        required />
    </div>
  </div>
</div>

<div class="row">
  <!-- Area, Street, Sector, Village Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="input-label">Area, Street, Sector, Village</label>
      <input 
        type="text" 
        name="address_line2" 
        class="styled-input" 
        placeholder="Enter Area, Street, Sector, Village" />
    </div>
  </div>

  <!-- Landmark Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="input-label">Landmark</label>
      <input 
        type="text" 
        name="landmark" 
        class="styled-input" 
        placeholder="E.g. near Apollo Hospital" />
    </div>
  </div>
</div>

<div class="row">
  <!-- Town/City Field -->
  <div class="col-md-6">
    <div class="input-field-container">
      <label class="input-label">Town/City</label>
      <input 
        type="text" 
        name="city" 
        class="styled-input" 
        placeholder="Enter Town/City" 
        required />
    </div>
  </div>

  <!-- State Field -->
  <div class="col-md-6">
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
</div>

          <!-- Seventh Row -->
          <!-- <div class="row">
      
            <div class="col-md-12">
              <div class="input-field-container">
                <label class="input-label">Address</label>
                <textarea class="styled-input" name="address" placeholder="Enter address"></textarea>
              </div>
            </div>
          </div> -->

          <!-- Submit Button -->
          <div class="row">
            <div class="col-md-12">
              <div class="input-field-container">
                <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
              </div>
            </div>
          </div>
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

<script>
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


  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
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
    const inputFieldCustomerName = document.getElementById("customer-name");
    const inputFieldContactNo = document.getElementById("emergency_contact_number");
    const patientNameField = document.getElementById("patient_name");
    const patientRelationField = document.getElementById("relationship");

    // Clear previous suggestions and reset fields
    customerList.innerHTML = "";
    inputFieldCustomerName.value = "";
    inputFieldContactNo.value = "";
    patientNameField.value = "";
    patientRelationField.value = "";

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
                                <strong>${customer.customer_name}</strong> - ${customer.emergency_contact_number}
                                <ul style="margin-top: 5px; padding-left: 20px;">
                                    <li>
                                        <input 
                                            type="checkbox" 
                                            id="patient_${index}" 
                                            data-customer-name="${customer.customer_name}" 
                                            data-contact-number="${customer.emergency_contact_number}" 
                                            data-patient-name="${customer.patient_name || ""}" 
                                            data-patient-relation="${customer.relationship || ""}" 
                                            onchange="selectPatient(this)" 
                                        />
                                        <label for="patient_${index}">
                                            ${customer.patient_name || "Unknown"} (${customer.relationship || "Unknown"})
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
    const customerList = document.getElementById("customerList");

    if (checkbox.checked) {
        // Populate all fields with data from the checkbox
        inputFieldCustomerName.value = checkbox.dataset.customerName || "Unknown";
        inputFieldContactNo.value = checkbox.dataset.contactNumber || "Unknown";
        patientNameField.value = checkbox.dataset.patientName || "Unknown";
        patientRelationField.value = checkbox.dataset.patientRelation || "Unknown";

        // Hide the suggestion list after selecting
        customerList.innerHTML = "";

        // Uncheck other checkboxes if one is selected
        const allCheckboxes = document.querySelectorAll('#customerList input[type="checkbox"]');
        allCheckboxes.forEach((cb) => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    } else {
        // Clear all fields if the checkbox is unchecked
        inputFieldCustomerName.value = "";
        inputFieldContactNo.value = "";
        patientNameField.value = "";
        patientRelationField.value = "";
    }
}


 // Function to set current date and time
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
    document.getElementById("add-field-set").addEventListener("click", function () {
        addFieldSet();
    });

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

                    // Update the total price of all services
                    updateTotalPrice();
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

document.addEventListener("DOMContentLoaded", function () {
    const fieldContainer = document.getElementById("field-container");
    const addButton = document.getElementById("add-field-set");
    const totalPriceField = document.getElementById("total_price_all_services");

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
          alert("End date cannot be before the Start date.");
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

              updateTotalPrice();
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

    function updateTotalPrice() {
      const servicePriceInputs = document.querySelectorAll('input[name^="service_price[]"]');
      let totalPrice = 0;

      servicePriceInputs.forEach((input) => {
        const price = parseFloat(input.value);
        if (!isNaN(price)) {
          totalPrice += price;
        }
      });

      totalPriceField.value = totalPrice.toFixed(2);
    }

    function addFieldSet() {
      const firstFieldSet = fieldContainer.querySelector(".field-set");
      const clonedFieldSet = firstFieldSet.cloneNode(true);

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

      fieldContainer.appendChild(clonedFieldSet);
      attachListenersToInputs(clonedFieldSet);
    }

    function attachListenersToInputs(fieldSet) {
      const fromDateInput = fieldSet.querySelector('input[name^="from_date"]');
      const endDateInput = fieldSet.querySelector('input[name^="end_date"]');
      const totalDaysInput = fieldSet.querySelector('input[name^="total_days"]');
      const serviceDurationInput = fieldSet.querySelector('select[name^="service_duration"]');
      const serviceTypeInput = fieldSet.querySelector('select[name^="service_type"]');
      const perDayServicePriceInput = fieldSet.querySelector('input[name^="per_day_service_price"]');
      const servicePriceInput = fieldSet.querySelector('input[name^="service_price"]');

      if (fromDateInput && endDateInput && totalDaysInput) {
        fromDateInput.addEventListener("change", () => calculateTotalDays(fromDateInput, endDateInput, totalDaysInput));
        endDateInput.addEventListener("change", () => calculateTotalDays(fromDateInput, endDateInput, totalDaysInput));
      }

      if (serviceTypeInput && serviceDurationInput && totalDaysInput) {
        const updateServicePrice = () => calculateServiceCharge(serviceTypeInput, serviceDurationInput, totalDaysInput, perDayServicePriceInput, servicePriceInput);
        serviceTypeInput.addEventListener("change", updateServicePrice);
        serviceDurationInput.addEventListener("change", updateServicePrice);
        totalDaysInput.addEventListener("change", updateServicePrice);
      }
    }

    const initialFieldSet = fieldContainer.querySelector(".field-set");
    attachListenersToInputs(initialFieldSet);

    addButton.addEventListener("click", addFieldSet);
  });

  function fetchServiceDetails(serviceType) {
    return new Promise((resolve, reject) => {
      // Replace with actual API endpoint or PHP backend script
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
</script>

</body>
</html>
