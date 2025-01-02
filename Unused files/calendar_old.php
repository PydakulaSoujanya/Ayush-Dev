<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Styled Form with Calendar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

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

    #calendar-container {
      margin: 50px auto;
      padding: 10px;
      background: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
    }

    #calendar {
      max-width: 100%;
    }

    /* Modal adjustments */
    .modal-dialog {
      margin: 1.75rem auto;
      max-width: 800px;
    }

    .modal-content {
      padding: 20px;
      border-radius: 10px;
    }

    .modal-header {
      background-color: #A26D2B;
      color: white;
      border-bottom: none;
      border-radius: 10px 10px 0 0;
    }

    .modal-title {
      font-size: 18px;
    }

    .close {
      color: white;
      opacity: 0.8;
    }

    .close:hover {
      opacity: 1;
    }

    .form-group label {
      font-weight: bold;
      color: #A26D2B;
    }

    .btn-primary {
      background-color: #A26D2B;
      border-color: #A26D2B;
    }

    .btn-primary:hover {
      background-color: #854E1C;
      border-color: #854E1C;
    }
  </style>
</head>
<body>
<?php include('navbar.php'); ?>
  
  <div class="container mt-7">
    <h2 class="text-center">Allotment Calendar</h2>
    <div id="calendar-container">
      <div id="calendar"></div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Allotment Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" id="assignmentForm">
          <!-- Row 1 -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="traineeName">Trainee Name</label>
                <input type="text" class="form-control" id="traineeName" name="traineeName" placeholder="Enter trainee name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="customerName">Customer Name</label>
                <input type="text" class="form-control" id="customerName" name="customerName" placeholder="Enter customer name" required>
              </div>
            </div>
          </div>

          <!-- Row 2 -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="fromDate">From Date</label>
                <input type="date" class="form-control" id="fromDate" name="fromDate" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="toDate">To Date</label>
                <input type="date" class="form-control" id="toDate" name="toDate" required>
              </div>
            </div>
          </div>

          <!-- Row 3 -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks"></textarea>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
      // Initialize FullCalendar
      $('#calendar').fullCalendar({
        editable: true,
        selectable: true,
        dayClick: function (date) {
          // Open modal on date click
          $('#fromDate').val(date.format('DD-MM-YYYY'));
          $('#toDate').val(date.format('DD-MM-YYYY'));
          $('#eventModal').modal('show');
        },
        events: function (start, end, timezone, callback) {
          $.ajax({
            url: 'load_events.php',
            dataType: 'json',
            success: function (data) {
              callback(data);
            },
            error: function () {
              alert('Error loading events.');
            }
          });
        }
      });

      // Handle form submission
      $('#assignmentForm').submit(function (e) {
        e.preventDefault();
        
        // Collect form data
        const formData = {
          trainee_name: $('#traineeName').val(),
          customer_name: $('#customerName').val(),
          customer_mobile: $('#customerMobile').val(),
          customer_address: $('#customerAddress').val(),
          from_date: $('#fromDate').val(),
          to_date: $('#toDate').val()
        };

        // Submit form data via AJAX
        $.ajax({
          url: 'save_assignment.php',
          type: 'POST',
          data: formData,
          dataType: 'json',
          success: function (response) {
            if (response.status === 'success') {
              alert(response.message);
              $('#eventModal').modal('hide');
              $('#assignmentForm')[0].reset();
              $('#calendar').fullCalendar('refetchEvents'); // Reload calendar events
            } else {
              alert(response.message);
            }
          },
          error: function () {
            alert('An error occurred while saving the assignment.');
          }
        });
      });
    });
  </script>
</body>
</html>
