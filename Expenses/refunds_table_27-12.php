<?php
session_start();
include '../config.php'; // Database connection

// Handle form submission to save refund data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['update'] as $row_id => $value) {
    $employee_id = $_POST['employee_id'][$row_id];
    $allotment_id = $_POST['allotment_id'][$row_id];
    $patient_name = $_POST['patient_name'][$row_id];
    $customer_name = $_POST['customer_name'][$row_id];
    $refund_reason = $_POST['refund_reason'][$row_id];
    $refund_amount = $_POST['refund_amount'][$row_id];
    $is_refunded = $_POST['is_refunded'][$row_id];

 
 

    // Insert or update the refund record
    $stmt = $conn->prepare("
        INSERT INTO refunds (employee_id, allotment_id, patient_name, customer_name, refund_reason, refund_amount, is_refunded, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE 
            refund_reason = VALUES(refund_reason),
            refund_amount = VALUES(refund_amount),
            is_refunded = VALUES(is_refunded),
            patient_name = VALUES(patient_name),
            customer_name = VALUES(customer_name),
            updated_at = NOW()
    ");
    $stmt->bind_param("iissssd", $employee_id, $allotment_id, $patient_name, $customer_name, $refund_reason, $refund_amount, $is_refunded);

    if ($stmt->execute()) {
      echo "<script>
      alert('Refund details for Employee ID $employee_id saved successfully!');
      window.location.href = '" . $_SERVER['PHP_SELF'] . "';
  </script>";
    } else {
      echo "<script>
      alert('Failed to save refund details for Employee ID $employee_id: " . $conn->error . "');
      window.location.href = '" . $_SERVER['PHP_SELF'] . "';
  </script>";
    }

    $stmt->close();
}

  // Remove the redirect to allow the alert to display
}

// Fetch data from related tables
$sql = "
    SELECT 
        a.id AS allotment_id,
        e.id AS employee_id,
        e.name AS employee_name,
        cm.customer_name,
        cm.patient_name,
        a.service_type,
        a.no_of_hours,
        sm.daily_rate_8_hours,
        sm.daily_rate_12_hours,
        sm.daily_rate_24_hours,
        CASE 
            WHEN a.no_of_hours = 8 THEN sm.daily_rate_8_hours
            WHEN a.no_of_hours = 12 THEN sm.daily_rate_12_hours
            WHEN a.no_of_hours = 24 THEN sm.daily_rate_24_hours
            ELSE 0
        END AS daily_rate,
        a.start_date,
        a.end_date,
        DATEDIFF(a.end_date, a.start_date) + 1 AS total_days,
        ((DATEDIFF(a.end_date, a.start_date) + 1) * 
            CASE 
                WHEN a.no_of_hours = 8 THEN sm.daily_rate_8_hours
                WHEN a.no_of_hours = 12 THEN sm.daily_rate_12_hours
                WHEN a.no_of_hours = 24 THEN sm.daily_rate_24_hours
                ELSE 0
            END
        ) AS total_pay,
        r.refund_reason,
        r.refund_amount,
        r.is_refunded
    FROM allotment a
    JOIN emp_info e ON a.employee_id = e.id
    JOIN customer_master cm ON cm.id = a.patient_name
    JOIN service_master sm ON a.service_type = sm.service_name
    LEFT JOIN refunds r ON a.id = r.allotment_id
    ORDER BY a.id ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Refund Management</title>
</head>
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
<body>
  <?php include '../navbar.php'; ?>

  <div class="container mt-7">
        <div class="dataTable_card card">
            <div class="card-header">Refunds</div>
      <div class="card-body">
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-{$_SESSION['message_type']} alert-dismissible fade show' role='alert'>
                    {$_SESSION['message']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            unset($_SESSION['message'], $_SESSION['message_type']);
        }
        ?>

        <form method="POST" action="">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Employee Name</th>
                  <th>Customer Name</th>
                  <th>Patient Name</th>
                  <th>Service Type</th>
                  <th>Total Days</th>
                  <th>Daily Rate</th>
                  <th>Total Amount</th>
                  <th>Refund Reason</th>
                  <th>Refund Amount</th>
                  <th>Refunded?</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<input type='hidden' name='employee_id[{$row['allotment_id']}]' value='{$row['employee_id']}'>";
                        echo "<input type='hidden' name='allotment_id[{$row['allotment_id']}]' value='{$row['allotment_id']}'>";
                        echo "<input type='hidden' name='patient_name[{$row['allotment_id']}]' value='{$row['patient_name']}'>";
                        echo "<input type='hidden' name='customer_name[{$row['allotment_id']}]' value='{$row['customer_name']}'>";
                        echo "<td>{$row['employee_name']}</td>";
                        echo "<td>{$row['customer_name']}</td>";
                        echo "<td>{$row['patient_name']}</td>";
                        echo "<td>{$row['service_type']}</td>";
                        echo "<td>{$row['total_days']}</td>";
                        echo "<td>{$row['daily_rate']}</td>";
                        echo "<td>{$row['total_pay']}</td>";
                        echo "<td>
    <select name='refund_reason[{$row['allotment_id']}]' class='form-select'>
    <option value='' " . (empty($row['refund_reason']) ? 'selected' : '') . ">Select</option>
    <option value='Death' " . ($row['refund_reason'] === 'Death' ? 'selected' : '') . ">Death</option>
    <option value='Patient Recovery' " . ($row['refund_reason'] === 'Patient Recovery' ? 'selected' : '') . ">Patient Recovery</option>
    <option value='Not Happy with Service' " . ($row['refund_reason'] === 'Not Happy with Service' ? 'selected' : '') . ">Not Happy with Service</option>
</select>

</td>";

echo "<td><input type='number' name='refund_amount[{$row['allotment_id']}]' class='form-control' value='" . ($row['refund_amount'] ?? 0) . "'></td>";

echo "<td>
    <select name='is_refunded[{$row['allotment_id']}]' class='form-select'>
      <option value='No' " . (($row['is_refunded'] ?? 'No') === 'No' ? 'selected' : '') . ">No</option>
      <option value='Yes' " . (($row['is_refunded'] ?? '') === 'Yes' ? 'selected' : '') . ">Yes</option>
    </select>
</td>";
                        echo "<td>
                                <button type='submit' name='update[{$row['allotment_id']}]' class='btn btn-success btn-sm'>Confirm</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No records found</td></tr>";
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
