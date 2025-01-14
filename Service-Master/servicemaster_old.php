<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Master Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  
 
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <?php
include '../navbar.php';
?>
  <div class="container mt-7">
    <h3 class="mb-4">Service Master Details</h3>
    <form action="service_masterdb.php" method="POST">
      <!-- Service Name -->
      <div class="row form-section form-first-row">
      <h2 class="section-title1">Service Details</h2>

        <div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-3">
          <div class="input-field-container">
            <label class="input-label">Service Name</label>
            <select id="service-name" class="styled-input" name="service_name" onchange="handleOtherOption()">
              <option value="" disabled selected>Select Service</option>
              <option value="care_taker">Care Taker</option>
              <option value="fully_trained_nurse">Fully Trained Nurse</option>
              <option value="semi_trained_nurse">Semi Trained Nurse</option>
              <option value="nannies">Nannies</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-3">
          <div class="input-field-container">
            <label class="input-label">Status</label>
            <select class="styled-input" name="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
    
    
      <!-- Daily Rates -->
      
     
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
          <div class="input-field-container">
            <label class="input-label">Daily Rate (8 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_8_hours" placeholder="Enter Rate for 8 Hours" />
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
          <div class="input-field-container">
            <label class="input-label">Daily Rate (12 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_12_hours" placeholder="Enter Rate for 12 Hours" />
          </div>
        </div>
      
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3">
          <div class="input-field-container">
            <label class="input-label">Daily Rate (24 Hours)</label>
            <input type="number" class="styled-input" name="daily_rate_24_hours" placeholder="Enter Rate for 24 Hours" />
          </div>
        </div>
     


       <!-- Description -->
      
        <div class="col-md-12 mt-3">
          <div class="input-field-container">
            <label class="input-label">Description</label>
            <textarea class="styled-input" rows="1" name="description" placeholder="Enter Service Description"></textarea>
          </div>
        </div>
      </div>
      <div class="col-md-12 mt-4 text-center">
  <div class="input-field-container">
    <button type="submit" class="btn btn-primary" name="submit" value="Submit" style="background-color: #A26D2B; border-color: #A26D2B; color: #fff;">
      Submit
    </button>
  </div>
</div>
  </div>

  <!-- Modal for Adding New Service -->
  <div class="modal fade" id="otherServiceModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add New Service</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="new-service-form">
            <div class="form-group">
              <label for="new-service-name">Service Name</label>
              <input type="text" id="new-service-name" class="form-control" placeholder="Enter New Service Name" required />
            </div>
            <button type="button" class="btn btn-success" onclick="addNewService()">Add Service</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    
    function handleOtherOption() {
      const selectedValue = document.getElementById("service-name").value;
      if (selectedValue === "other") {
        $('#otherServiceModal').on('shown.bs.modal', () => {
          
          document.getElementById("new-service-name").value = "";
        });
        $('#otherServiceModal').modal('show'); 
      }
    }

    
    function addNewService() {
    const newServiceName = document.getElementById("new-service-name").value.trim();
    if (newServiceName) {
        // Send new service to the server via AJAX
        $.ajax({
            url: 'add_service.php', // Separate PHP script to handle new service addition
            method: 'POST',
            data: { service_name: newServiceName },
            success: function (response) {
                const dropdown = document.getElementById("service-name");
                const newOption = document.createElement("option");
                newOption.value = newServiceName.toLowerCase().replace(/\s+/g, "_");
                newOption.text = newServiceName;
                dropdown.add(newOption, dropdown.options[dropdown.length - 1]);
                dropdown.value = newServiceName.toLowerCase().replace(/\s+/g, "_");
                $('#otherServiceModal').modal('hide');
            },
            error: function () {
                alert("Failed to add the service. Please try again.");
            }
        });
    } else {
        alert("Please enter a service name.");
    }
}

  </script>
</body>
</html>