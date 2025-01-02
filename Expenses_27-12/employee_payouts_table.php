<?php
session_start(); // Start the session to store flash messages

include '../config.php'; 

// Handle form submission to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'] ?? 'Unknown'; // Use fallback value
    $service_type = $_POST['service_type'];
    $total_days = $_POST['total_days'];
    $worked_days = $_POST['worked_days'];
    $daily_rate = $_POST['daily_rate'];
    $total_pay = $_POST['total_pay'];
    $status = $_POST['status'];

    // Validate fields
    if (empty($employee_id) || empty($employee_name)) {
        $_SESSION['message'] = "Error: Employee name or ID is missing!";
        $_SESSION['message_type'] = "danger";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Insert or update the employee payout details
    $stmt = $conn->prepare("\n        INSERT INTO employee_payouts (employee_id, employee_name, service_type, total_days, worked_days, daily_rate, total_pay, status) \n        VALUES (?, ?, ?, ?, ?, ?, ?, ?)\n        ON DUPLICATE KEY UPDATE \n        worked_days = VALUES(worked_days),\n        total_pay = VALUES(total_pay),\n        status = VALUES(status),\n        updated_at = CURRENT_TIMESTAMP\n    ");
    $stmt->bind_param(
        "issiiids",
        $employee_id,
        $employee_name,
        $service_type,
        $total_days,
        $worked_days,
        $daily_rate,
        $total_pay,
        $status
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Employee payout details saved successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to save employee payout details!";
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();

    // Redirect to clear POST data and show the message
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Employee Payouts</title>
  <!-- <style>
    .dataTable_wrapper {
      padding: 20px;
    }

    .dataTable_search input {
      max-width: 200px;
    }

    .dataTable_headerRow th,
    .dataTable_row td {
      border: 1px solid #dee2e6;
    }

    .dataTable_headerRow {
      background-color: #f8f9fa;
      font-weight: bold;
    }

    .dataTable_row:hover {
      background-color: #f1f1f1;
    }

    .dataTable_card {
      border: 1px solid #ced4da;
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dataTable_card .card-header {
      background-color: #A26D2B;
      color: white;
      font-weight: bold;
    }

    .action-icons i {
      color: black;
      cursor: pointer;
      margin-right: 10px;
    }
  </style> -->
</head>
<body>
  <?php 
include '../navbar.php';
  ?>
    <div class="container mt-7">
        <div class="dataTable_card card">
            <div class="card-header">Employee Payouts</div>
            <div class="card-body">
                <!-- Display success or error message -->
                <?php
// Check if there's a message to show
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'];

    // Display a JavaScript alert based on the message type
    echo "<script>
        alert('$message');
    </script>";

    // Clear the session message after showing the alert
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

                <form method="POST" id="payoutForm">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Service Type</th>
            <th>Total Days</th>
            <th>Worked Days</th>
            <th>Daily Rate</th>
            <th>Total Pay</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

        $sql = "
        SELECT 
            e.id AS employee_id,
            e.name AS employee_name,
            a.service_type,
            a.no_of_hours,
            a.day_1, a.day_2, a.day_3, a.day_4, a.day_5, a.day_6, a.day_7,
            a.day_8, a.day_9, a.day_10, a.day_11, a.day_12, a.day_13, a.day_14,
            a.day_15, a.day_16, a.day_17, a.day_18, a.day_19, a.day_20, a.day_21,
            a.day_22, a.day_23, a.day_24, a.day_25, a.day_26, a.day_27, a.day_28,
            a.day_29, a.day_30, a.day_31,
            CASE 
                WHEN a.no_of_hours = 8 THEN sm.daily_rate_8_hours
                WHEN a.no_of_hours = 12 THEN sm.daily_rate_12_hours
                WHEN a.no_of_hours = 24 THEN sm.daily_rate_24_hours
                ELSE 0
            END AS daily_rate,
            sr.total_days,
            ep.status
        FROM allotment a
        JOIN emp_info e ON a.employee_id = e.id
        JOIN service_master sm ON a.service_type = sm.service_name
        JOIN service_requests sr ON sr.assigned_employee = e.name
        LEFT JOIN employee_payouts ep ON ep.employee_id = e.id
        WHERE e.name LIKE '%$searchTerm%'
        ORDER BY e.name ASC;
    ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $uniqueEmployees = [];
          while ($row = $result->fetch_assoc()) {
              // Skip duplicates based on employee ID and service type
              if (in_array($row['employee_id'] . '-' . $row['service_type'], $uniqueEmployees)) {
                  continue;
              }
              $uniqueEmployees[] = $row['employee_id'] . '-' . $row['service_type'];
      
              // Rest of the row rendering logic
              $worked_days = 0;
              for ($i = 1; $i <= 31; $i++) {
                  $dayKey = "day_$i";
                  if (isset($row[$dayKey]) && $row[$dayKey] === "yes") {
                      $worked_days++;
                  }
              }
      
              $total_price = $worked_days * $row['daily_rate'];
      
              echo "<tr>";
              echo "<td>
                  <input type='hidden' name='employee_id' value='" . $row['employee_id'] . "'>
                  <input type='hidden' name='employee_name' value='" . htmlspecialchars($row['employee_name']) . "'>
                  " . htmlspecialchars($row['employee_name']) . "
              </td>";
              echo "<td><input type='hidden' name='service_type' value='" . $row['service_type'] . "'>" . $row['service_type'] . "</td>";
              echo "<td><input type='hidden' name='total_days' value='" . $row['total_days'] . "'>" . $row['total_days'] . "</td>";
              echo "<td><input type='hidden' name='worked_days' value='" . $worked_days . "'>" . $worked_days . "</td>";
              echo "<td><input type='hidden' name='daily_rate' value='" . $row['daily_rate'] . "'>" . $row['daily_rate'] . "</td>";
              echo "<td><input type='hidden' name='total_pay' value='" . $total_price . "'>" . $total_price . "</td>";
              echo "<td>
                      <select name='status' class='form-select'>
                          <option value='Select' " . ($row['status'] === null ? "selected" : "") . " readonly>Select</option>
                          <option value='Pending' " . ($row['status'] === 'Pending' ? "selected" : "") . ">Pending</option>
                          <option value='Paid' " . ($row['status'] === 'Paid' ? "selected" : "") . ">Paid</option>
                          <option value='Processing' " . ($row['status'] === 'Processing' ? "selected" : "") . ">Processing</option>
                          <option value='In Review' " . ($row['status'] === 'In Review' ? "selected" : "") . ">In Review</option>
                      </select>
                    </td>";
              echo "<td><button type='submit' class='btn btn-success btn-sm mt-1'>Update</button></td>";
              echo "</tr>";
          }
      } else {
            echo "<tr><td colspan='8'>No data found</td></tr>";
        }
        ?>
    </tbody>
</table>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
