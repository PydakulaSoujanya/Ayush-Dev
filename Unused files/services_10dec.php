<?php
// Include database connection
include 'config.php';

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
  </style>
</head>
<body>
  <?php
  include 'navbar.php';
  ?>
  <div class="container mt-7">
    <h3 class="mb-4"> Capturing Service Request Form</h3>
    
    <div class="form-section">
      <form action="services_db.php" method="POST">
        <div class="row">
      
          <div class="col-md-6">
  <div class="input-field-container">
    <label class="input-label">Customer Name</label>
    <div style="display: flex; align-items: center;">
    <select id="customer-name" class="styled-input" name="customer_name">
  <option value="" disabled selected>Select Customer</option>
  <?php foreach ($customers as $customer): ?>
    <option value="<?= $customer['id'] ?>"><?= $customer['customer_name'] ?></option>
  <?php endforeach; ?>
</select>
      <button 
        type="button" 
        class="btn btn-primary btn-sm ml-2" 
        data-toggle="modal" 
        data-target="#addCustomerModal" 
        style="margin-left: 10px;">
        +
      </button>
    </div>
  </div>
</div>

   
<div class="col-md-6">
  <div class="input-field-container">
    <label class="input-label">Phone Number</label>
    <input type="text" id="emergency_contact_number" class="styled-input" name="emergency_contact_number" placeholder="Phone Number" readonly />
  </div>
</div>

          <!-- <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Email</label>
              <input type="email" class="styled-input" name="email" id="email" placeholder="Enter Email">
            </div>
          </div> -->
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Enquiry Time</label>
              <input type="time" name="enquiry_time" class="styled-input">
            </div>
          </div>
          <div class="col-md-6">
  <div class="input-field-container">
    <label class="input-label">Enquiry Date</label>
    <input type="date" class="styled-input date-input" name="enquiry_date" id="enquiry-date" />
  </div>
</div>
<div class="col-md-6">
         <div class="input-field-container">
           <label class="input-label">Start Date</label>
           
           <input type="date" class="styled-input" name="from_date" id="fromDate" />
         </div>
       </div>
       <div class="col-md-6">
         <div class="input-field-container">
           <label class="input-label">End Date</label>
           <input type="date" class="styled-input" name="end_date" id="endDate" />
         </div>
       </div>
       
       <div class="col-md-6">
         <div class="input-field-container">
           <label class="input-label">Total Days</label>
           <input type="number" class="styled-input" name="total_days" id="total_days" placeholder="Total Days"/>
         </div>
       </div>

    <div class="col-md-6">
  <div class="input-field-container">
    <label class="input-label">Service Duration (in Hours)</label>
    <select class="styled-input" name="service_duration" id="service_duration">
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
              <select class="styled-input" name="service_type" id="service_type">
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
           <label class="input-label">Service Price</label>
           <input type="text" class="styled-input" name="service_price" id="service_price" placeholder="Service Price" readonly />
         </div>
       </div>
     
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Enquiry Source</label>
              <select class="styled-input" name="enquiry_source">

              <!-- <select class="styled-input" name="enquiry_source"> -->
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

              <!-- <select class="styled-input" name="priority_level"> -->
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

              <!-- <select class="styled-input" name="status"> -->
                <option value="" disabled selected>Select Status</option>
                <option value="new">New</option>
                <option value="pending">Pending</option>
                <option value="followup">Follow-Up Required</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Request Details</label>
              <input type="text" class="styled-input" name="request_details" placeholder="Enter Request Details">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-field-container">
              <label class="input-label">Resolution Notes</label>
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

          <!-- Seventh Row -->
          <div class="row">
            <!-- Address -->
            <div class="col-md-12">
              <div class="input-field-container">
                <label class="input-label">Address</label>
                <textarea class="styled-input" name="address" placeholder="Enter address"></textarea>
              </div>
            </div>
          </div>

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

document.getElementById('customer-name').addEventListener('change', function () {
    var selectedCustomerId = this.value; // Get the selected customer ID
    console.log('Selected Customer ID:', selectedCustomerId); // Debug log

    // Validate the selected value
    if (!selectedCustomerId) {
        console.warn('No customer selected or invalid ID.');
        return;
    }

    fetch('get_customer_details.php?id=' + encodeURIComponent(selectedCustomerId))
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            console.log('Fetch Response:', result); // Debug log the raw response

            if (result.success) {
                // Update the phone number field
                document.getElementById('emergency_contact_number').value = result.data.emergency_contact_number || '';
                console.log('Phone Updated:', result.data.emergency_contact_number); // Debug log
            } else {
                console.error('Error:', result.message || 'Unknown error');
                alert('Failed to fetch customer details.');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);

            if (error.message.includes('Unexpected token')) {
                alert('The server returned an invalid response. Please contact support.');
            } else {
                alert('An error occurred while fetching customer details. Please try again.');
            }
        });
});


document.addEventListener('DOMContentLoaded', function() {
  const fromDateInput = document.getElementById('fromDate');
  const endDateInput = document.getElementById('endDate');
  const totalDaysInput = document.getElementById('total_days');

  // Event listener for "From Date"
  fromDateInput.addEventListener('change', calculateTotalDays);

  // Event listener for "End Date"
  endDateInput.addEventListener('change', calculateTotalDays);

  // Function to calculate total days between "From Date" and "End Date"
  function calculateTotalDays() {
    const fromDate = new Date(fromDateInput.value);
    const endDate = new Date(endDateInput.value);

    // Check if both dates are selected
    if (fromDate && endDate && !isNaN(fromDate) && !isNaN(endDate)) {
      // Calculate the difference in time
      const timeDifference = endDate - fromDate;

      // Calculate the difference in days (divide by milliseconds in a day)
      const totalDays = timeDifference / (1000 * 3600 * 24) + 1; // +1 to include the end date

      // Check if the end date is later than the from date
      if (totalDays >= 0) {
        totalDaysInput.value = totalDays;
      } else {
        alert("End date cannot be before the From date.");
        totalDaysInput.value = '';
      }
    } else {
      totalDaysInput.value = '';
    }
  }
});


document.addEventListener('DOMContentLoaded', function () {
    const serviceTypeInput = document.getElementById('service_type');
    const totalDaysInput = document.getElementById('total_days');
    const servicePriceInput = document.getElementById('service_price');
    const serviceDurationInput = document.getElementById('service_duration'); // Added for duration

    // Event listener for service type and duration selection
    serviceTypeInput.addEventListener('change', calculateServiceCharge);
    serviceDurationInput.addEventListener('change', calculateServiceCharge);

    // Event listener for total days change
    totalDaysInput.addEventListener('input', calculateServiceCharge);

    // Function to fetch service data and calculate service charge
    function calculateServiceCharge() {
        const serviceType = serviceTypeInput.value;
        const totalDays = parseInt(totalDaysInput.value, 10);
        const serviceDuration = parseInt(serviceDurationInput.value, 10); // Fetch selected duration

        // Check if inputs are valid
        if (serviceType && totalDays && serviceDuration && !isNaN(totalDays) && totalDays > 0) {
            console.log("Service Type: ", serviceType);
            console.log("Total Days: ", totalDays);
            console.log("Service Duration: ", serviceDuration);

            // Fetch the service type details from the database
            fetchServiceDetails(serviceType).then(serviceDetails => {
                if (serviceDetails) {
                    console.log("Service Details: ", serviceDetails);
                    let dailyRate = 0;

                    // Determine the rate based on the selected duration
                    if (serviceDuration === 8) {
                        dailyRate = serviceDetails.daily_rate_8_hours;
                    } else if (serviceDuration === 12) {
                        dailyRate = serviceDetails.daily_rate_12_hours;
                    } else if (serviceDuration === 24) {
                        dailyRate = serviceDetails.daily_rate_24_hours;
                    } else {
                        console.log("Invalid service duration selected.");
                        servicePriceInput.value = 'Invalid duration';
                        return;
                    }

                    if (!dailyRate) {
                        console.log("No daily rate found for the selected service type and duration.");
                        servicePriceInput.value = 'Rate not available';
                        return;
                    }

                    // Calculate total service price
                    const totalServicePrice = dailyRate * totalDays;

                    // Display the calculated service charge
                    servicePriceInput.value = totalServicePrice.toFixed(2);
                } else {
                    servicePriceInput.value = '';
                    alert("Failed to fetch service details.");
                }
            }).catch(error => {
                console.error("Error fetching service details:", error);
                alert("An error occurred while calculating service charge.");
            });
        } else {
            servicePriceInput.value = '';
        }
    }

    // Function to fetch service details from the database
    function fetchServiceDetails(serviceType) {
        return new Promise((resolve, reject) => {
            fetch("get_service_details.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
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
