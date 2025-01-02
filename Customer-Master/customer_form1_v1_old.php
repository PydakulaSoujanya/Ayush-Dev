<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Details Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<?php include('../navbar.php'); ?>

<div class="container mt-7">
  <h3 class="mb-4">Customer Details Form</h3>
  
  <form action="customer_db.php" method="POST" id="customer_form" enctype="multipart/form-data">
    <div class="row">
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

      <div class="col-md-4 hidden" id="patientNameField">
        <div class="input-field-container">
          <label class="input-label">Patient Name</label>
          <input type="text" class="styled-input" name="patient_name" placeholder="Enter patient name" required />
        </div>
      </div>

      <div class="col-md-4 hidden" id="relationshipField">
        <div class="input-field-container">
          <label class="input-label">Relationship with Patient</label>
          <select class="styled-input" name="relationship">
            <option value="" disabled selected>Select relationship</option>
            <option value="parent">Parent</option>
            <option value="sibling">Sibling</option>
            <option value="spouse">Spouse</option>
            <option value="child">Child</option>
            <option value="friend">Friend</option>
            <option value="guardian">Guardian</option>
            <option value="grandchild">Grandchild</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Customer Name</label>
          <input type="text" class="styled-input" name="customer_name" placeholder="Enter your name" required />
        </div>
      </div>

      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Contact Number</label>
          <input type="text" class="styled-input" name="emergency_contact_number" placeholder="Enter emergency contact number" required />
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

    </div>

    <div class="col-md-12 mt-4">
      <div class="card" style="border: 1px solid #8B4513; border-radius: 8px;">
        <div class="card-body">
          <div id="address-container">
            <div class="address-entry" id="address-1">
              <div class="row">
                <div class="col-md-6">
                  <div class="input-field-container">
                    <label class="input-label">Pincode</label>
                    <input type="text" name="pincode[]" class="styled-input" placeholder="6 digits [0-9] PIN code" required pattern="\d{6}" maxlength="6" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field-container">
                    <label class="input-label">Flat, House No., Building, Apartment</label>
                    <input type="text" name="address_line1[]" class="styled-input" placeholder="Enter Flat, House No., Building, etc." required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field-container">
                    <label class="input-label">Area, Street, Sector, Village</label>
                    <input type="text" name="address_line2[]" class="styled-input" placeholder="Enter Area, Street, Sector, Village" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field-container">
                    <label class="input-label">Landmark</label>
                    <input type="text" name="landmark[]" class="styled-input" placeholder="E.g. near Apollo Hospital" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field-container">
                    <label class="input-label">Town/City</label>
                    <input type="text" name="city[]" class="styled-input" placeholder="Enter Town/City" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field-container">
                  <?php include('states_dropdown.php'); ?>
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
      </div>
    </div>

    <div class="col-md-12 mt-4">
      <div class="input-field-container">
        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
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

  document.querySelector('.add-more').addEventListener('click', function () {
    var addressContainer = document.getElementById('address-container');
    var newAddressEntry = document.createElement('div');
    newAddressEntry.classList.add('address-entry');
    newAddressEntry.innerHTML = `
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Pincode</label>
            <input type="text" name="pincode[]" class="styled-input" placeholder="6 digits [0-9] PIN code" required pattern="\\d{6}" maxlength="6" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Flat, House No., Building, Apartment</label>
            <input type="text" name="address_line1[]" class="styled-input" placeholder="Enter Flat, House No., Building, etc." required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Area, Street, Sector, Village</label>
            <input type="text" name="address_line2[]" class="styled-input" placeholder="Enter Area, Street, Sector, Village" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Landmark</label>
            <input type="text" name="landmark[]" class="styled-input" placeholder="E.g. near Apollo Hospital" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Town/City</label>
            <input type="text" name="city[]" class="styled-input" placeholder="Enter Town/City" required />
          </div>
        </div>
         <div class="col-md-6">
                  <?php include('states_dropdown.php'); ?>
                </div>
        <div class="col-md-12">
          <i class="fas fa-plus-square text-success add-more" title="Add More"></i>
          <i class="fas fa-trash-alt text-danger delete-icon" title="Delete"></i>
        </div>
      </div>
    `;
    addressContainer.appendChild(newAddressEntry);
  });

  document.querySelector('#address-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-icon')) {
      var addressEntry = e.target.closest('.address-entry');
      addressEntry.remove();
    }
  });
</script>

</body>
</html>
