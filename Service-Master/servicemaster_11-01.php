<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Master Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


</head>

<style>
    .card {
      box-shadow: 0 10px 15px rgba(0, 1, 4, 0.7);
      margin-bottom: 20px;
      border-radius: 10px;
    }
    .card-header {
      background-color: #6c757d !important;
      font-size: 1.5rem;
      font-weight: bold;
      text-align: left;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      color: white !important;
    }
    .btn {
      background-color: #007bff;
      color: white;
    }
    .btn:hover {
      background-color: #0056b3;
    }
    .form-group {
      margin-bottom: 0.5rem;
    }
    label {
      position: absolute;
      top: -10px;
      left: 10px;
      background-color: white;
      padding: 0 5px;
      font-size: 14px;
      font-weight: 500;
      color: grey;
    }
    .form-control {
      margin-bottom: 0.5rem;
      padding: 0.375rem 0.75rem;
    }

    /* Standardize placeholder styles */
    ::placeholder,
    select.form-control option[disabled][selected] {
      font-size: 14px;
      color: grey;
    }

    :-ms-input-placeholder { /* IE10-11 */
      font-size: 14px;
      color: grey;
    }

    ::-ms-input-placeholder { /* Edge */
      font-size: 14px;
      color: grey;
    }

    .row .col-md-6,
    .row .col-lg-3 {
      padding-bottom: 30px;
    }
  </style>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <?php
    include '../navbar.php';
    ?>
 <div class="container mt-7">
    <div class="card">
      <div class="card-header">Add Service</div>
      <div class="card-body">
        <form action="service_masterdb.php" method="POST">
          <div class="row mt-3">
            <div class="col-md-6 col-lg-6">
              <div class="form-group">
                <label class="input-label">Service Name</label>
                <select id="service-name" class="form-control" name="service_name" onchange="handleOtherOption()">
                  <option value="" disabled selected>Select Service</option>
                  <option value="care_taker">Care Taker</option>
                  <option value="fully_trained_nurse">Fully Trained Nurse</option>
                  <option value="semi_trained_nurse">Semi Trained Nurse</option>
                  <option value="nannies">Nannies</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-6">
              <div class="form-group">
                <label class="input-label">Status</label>
                <select class="form-control" name="status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                <label class="input-label">Daily Rate (8 Hours)</label>
                <input type="number" class="form-control" name="daily_rate_8_hours" placeholder="Enter Rate for 8 Hours" />
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                <label class="input-label">Daily Rate (12 Hours)</label>
                <input type="number" class="form-control" name="daily_rate_12_hours" placeholder="Enter Rate for 12 Hours" />
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                <label class="input-label">Daily Rate (24 Hours)</label>
                <input type="number" class="form-control" name="daily_rate_24_hours" placeholder="Enter Rate for 24 Hours" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <label class="input-label">Description</label>
                <textarea class="form-control" rows="1" name="description" placeholder="Enter Service Description"></textarea>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary" style="width: 150px;">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Adding New Service -->
  <div class="modal fade" id="otherServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Enter New Service</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="new-service-name">New Service Name</label>
          <input type="text" class="form-control" id="new-service-name" placeholder="Enter new service name">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="addNewService()">Add Service</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Trigger modal if "Other" is selected
    function handleOtherOption() {
      const selectedValue = document.getElementById("service-name").value;
      if (selectedValue === "other") {
        $('#otherServiceModal').modal('show');
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
  // Load saved services from localStorage
  const savedServices = JSON.parse(localStorage.getItem("customServices")) || [];
  const dropdown = document.getElementById("service-name");
  savedServices.forEach(service => {
    const newOption = document.createElement("option");
    newOption.value = service.toLowerCase().replace(/\s+/g, "_");
    newOption.text = service;
    dropdown.add(newOption, dropdown.options[dropdown.length - 1]);
  });
});

function addNewService() {
  const newServiceName = document.getElementById("new-service-name").value.trim();
  if (newServiceName) {
    // Save the new service in localStorage
    const savedServices = JSON.parse(localStorage.getItem("customServices")) || [];
    savedServices.push(newServiceName);
    localStorage.setItem("customServices", JSON.stringify(savedServices));

    // Add the service to the dropdown
    const dropdown = document.getElementById("service-name");
    const newOption = document.createElement("option");
    newOption.value = newServiceName.toLowerCase().replace(/\s+/g, "_");
    newOption.text = newServiceName;
    dropdown.add(newOption, dropdown.options[dropdown.length - 1]);
    dropdown.value = newServiceName.toLowerCase().replace(/\s+/g, "_");
    $('#otherServiceModal').modal('hide');
  } else {
    alert("Please enter a service name.");
  }
}

  </script>
</body>
</html>