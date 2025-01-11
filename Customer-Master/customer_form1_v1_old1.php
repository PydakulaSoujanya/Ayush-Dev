<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Details Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('../navbar.php'); ?>

<div class="container mt-7">
    <div class="card custom-card">
  <div class="card-header custom-card-header">Add New Customer Details</div>
  <div class="card-body">
  <form action="customer_db.php" method="POST" id="customer_form" enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-6 col-lg-3 custom-padding">
     <div class="form-group custom-form-group">
          <label class="custom-label">Are you a patient?</label>
          <select class="form-control custom-input" id="patientStatus" name="patient_status" required>
            <option value="" disabled selected>Select an option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>
      </div>

      <div class="col-md-6 col-lg-3 custom-padding hidden" id="patientNameField">
        <div class="form-group custom-form-group">
          <label class="custom-label">Patient Name</label>
          <input type="text" class="form-control custom-input" name="patient_name" placeholder="Enter patient name" required />
        </div>
      </div>

      <div class="col-md-6 col-lg-3 custom-padding hidden" id="relationshipField">
        <div class="form-group custom-form-group">
          <label class="custom-label">Relationship with Patient</label>
          <select class="form-control custom-input" name="relationship">
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
  

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Customer Name</label>
        <input type="text" class="form-control custom-input" name="customer_name" placeholder="Enter your name" required />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Contact Number</label>
        <input type="text" class="form-control custom-input" name="emergency_contact_number" placeholder="Enter emergency contact number" required />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Blood Group</label>
        <select class="form-control custom-input" name="blood_group" required>
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
 

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Known Medical Conditions</label>
        <input type="text" class="form-control custom-input" name="medical_conditions" placeholder="Enter known medical conditions" required />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Email</label>
        <input type="email" class="form-control custom-input" name="email" placeholder="Enter your email" required />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Patient Age</label>
        <input type="number" class="form-control custom-input" name="patient_age" placeholder="Enter patient age" />
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Gender</label>
        <select class="form-control custom-input" name="gender">
          <option value="" disabled selected>Select gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Mobility Status</label>
        <select class="form-control custom-input" name="mobility_status" required>
          <option value="" disabled selected>Select Mobility Status</option>
          <option value="Walking">Walking</option>
          <option value="Wheelchair">Wheelchair</option>
          <option value="Other">Other</option>
        </select>
      </div>
    </div>

    <div class="col-md-6 col-lg-3 custom-padding">
      <div class="form-group custom-form-group">
        <label class="custom-label">Discharge Summary Sheet</label>
        <input type="file" class="form-control custom-input" name="discharge" accept=".pdf,.doc,.docx,.txt" />
      </div>
    </div>
  <!-- </div>
</div> -->
<!-- 
    <div class="col-md-12 mt-4">
      <div class="card" style="border: 1px solid #8B4513; border-radius: 8px;">
        <div class="card-body"> -->
          <!-- <div id="address-container"> -->
            <!-- <div class="address-entry" id="address-1">
            <div class="row form-section form-third-row mt-3">
            <h2 class="section-title3">Address Details</h2>
            <div class="row"> -->
                <div class="col-md-6 col-lg-3 custom-padding">
                  <div class="form-group custom-form-group">
                    <label class="custom-label">Pincode</label>
                    <input type="text" name="pincode[]" class="form-control custom-input" placeholder="6 digits [0-9] PIN code" required pattern="\d{6}" maxlength="6" />
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 custom-padding">
                  <div class="form-group custom-form-group">
                    <label class="custom-label">Flat, House No., Building, Apartment</label>
                    <input type="text" name="address_line1[]" class="form-control custom-input" placeholder="Enter Flat, House No., Building, etc." required />
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 custom-padding">
                  <div class="form-group custom-form-group">
                    <label class="custom-label">Area, Street, Sector, Village</label>
                    <input type="text" name="address_line2[]" class="form-control custom-input" placeholder="Enter Area, Street, Sector, Village" />
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 custom-padding">
                  <div class="form-group custom-form-group">
                    <label class="custom-label">Landmark</label>
                    <input type="text" name="landmark[]" class="form-control custom-input" placeholder="E.g. near Apollo Hospital" />
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 custom-padding">
                  <div class="form-group custom-form-group">
                    <label class="custom-label">Town/City</label>
                    <input type="text" name="city[]" class="form-control custom-input" placeholder="Enter Town/City" required />
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 custom-padding">
                  <?php include('states_dropdown.php'); ?>
                </div>
                <div class="col-md-12">
                  <i class="fas fa-plus-square text-success add-more" title="Add More"></i>
                  <i class="fas fa-trash-alt text-danger delete-icon" title="Delete"></i>
                </div>
                </div>
              <!-- </div> -->
            <!-- </div>
          </div>
        </div>
      </div>
    </div>
</div> -->

<div class="submit-btn-container">
    <button type="submit" class="btn btn-secondary submit-btn" name="submit" value="Submit" >
      Submit
    </button>
  </div>



  </form>
</div>

<script>


  document.getElementById('patientStatus').addEventListener('change', function () {
    var patientNameField = document.getElementById('patientNameField');
    var relationshipField = document.getElementById('relationshipField');
    var patientNameInput = document.querySelector('input[name="patient_name"]');
    var relationshipInput = document.querySelector('select[name="relationship"]');

    if (this.value === 'no') {
      // Show fields and add 'required' attribute
      patientNameField.classList.remove('hidden');
      relationshipField.classList.remove('hidden');
      patientNameInput.setAttribute('required', 'required');
      relationshipInput.setAttribute('required', 'required');
    } else {
      // Hide fields and remove 'required' attribute
      patientNameField.classList.add('hidden');
      relationshipField.classList.add('hidden');
      patientNameInput.removeAttribute('required');
      relationshipInput.removeAttribute('required');
    }
  });



  document.getElementById('address-container').addEventListener('click', function (e) {
  if (e.target.classList.contains('add-more')) {
    var addressContainer = document.getElementById('address-container');
    var newAddressEntry = document.createElement('div');
    newAddressEntry.classList.add('address-entry');
    newAddressEntry.innerHTML = `
     <div id="address-container">
            <div class="address-entry" id="address-1">
            <div class="row form-section form-third-row mt-3">
   
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group custom-form-group">
            <label class="custom-label">Pincode</label>
            <input type="text" name="pincode[]" class="form-control custom-input" placeholder="6 digits [0-9] PIN code" required pattern="\\d{6}" maxlength="6" />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group custom-form-group">
            <label class="custom-label">Flat, House No., Building, Apartment</label>
            <input type="text" name="address_line1[]" class="form-control custom-input" placeholder="Enter Flat, House No., Building, etc." required />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group custom-form-group">
            <label class="custom-label">Area, Street, Sector, Village</label>
            <input type="text" name="address_line2[]" class="form-control custom-input" placeholder="Enter Area, Street, Sector, Village" />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group custom-form-group">
            <label class="custom-label">Landmark</label>
            <input type="text" name="landmark[]" class="form-control custom-input" placeholder="E.g. near Apollo Hospital" />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <div class="form-group custom-form-group">
            <label class="custom-label">Town/City</label>
            <input type="text" name="city[]" class="form-control custom-input" placeholder="Enter Town/City" required />
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
          <?php include('states_dropdown.php'); ?>
        </div>
        <div class="col-md-12">
          <i class="fas fa-plus-square text-success add-more" title="Add More"></i>
          <i class="fas fa-trash-alt text-danger delete-icon" title="Delete"></i>
        </div>
      </div>
      </div>
    `;
    addressContainer.appendChild(newAddressEntry);
  }

  if (e.target.classList.contains('delete-icon')) {
    var addressEntry = e.target.closest('.address-entry');
    addressEntry.remove();
  }
});

</script>

</body>
</html>