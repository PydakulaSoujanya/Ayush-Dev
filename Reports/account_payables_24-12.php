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
    $stmt = $conn->prepare("INSERT INTO employee_payouts (employee_id, employee_name, service_type, total_days, worked_days, daily_rate, total_pay, status) \n        VALUES (?, ?, ?, ?, ?, ?, ?, ?)\n        ON DUPLICATE KEY UPDATE \n        worked_days = VALUES(worked_days),\n        total_pay = VALUES(total_pay),\n        status = VALUES(status),\n        updated_at = CURRENT_TIMESTAMP\n    ");
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
  <title>Account Payables</title>
 
</head>
<body>
  <?php 
include '../navbar.php';
  ?>
    <div class="container mt-7">

        <h3 class="text-center">You have to Pay more Rs. 15,024</h3>

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
        <th>S.No.</th>
            <th>Invoice ID</th>
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
        i.invoice_id AS InvoiceID, 
        e.entity_name AS Employee_Name, 
        e.entity_id AS Employee_ID, 
        sr.service_type AS Service_Type, 
        sr.total_days AS Total_Days, 
        sr.per_day_service_price AS Daily_Rate, 
        e.status AS Expense_Status,
        sr.service_price AS Total_Pay
    FROM 
        service_requests sr
    LEFT JOIN 
        expenses e ON sr.emp_id = e.entity_id  -- Joining with expenses based on employee ID
    LEFT JOIN 
        invoice i ON sr.id = i.service_id  -- Joining with invoice based on service ID
    WHERE 
        (i.invoice_id LIKE '%$searchTerm%' OR e.entity_name LIKE '%$searchTerm%' OR sr.service_type LIKE '%$searchTerm%')
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $uniqueEmployees = [];
        $serial_no = 0;
        while ($row = $result->fetch_assoc()) {
            $serial_no++;

            // Skip duplicates based on employee ID and service type
            if (in_array($row['Employee_ID'] . '-' . $row['Service_Type'], $uniqueEmployees)) {
                continue;
            }
            $uniqueEmployees[] = $row['Employee_ID'] . '-' . $row['Service_Type'];

            // Calculate worked days (if needed)
            // For now, assuming worked days = total days (you can modify this logic as required)
            $worked_days = $row['Total_Days'];

            // Calculate total price (worked days * daily rate)
            $total_price = $worked_days * $row['Daily_Rate'];

            echo "<tr>";
            echo "<td>$serial_no</td>";
            echo "<td><input type='hidden' name='invoice_id' value='" . $row['InvoiceID'] . "'>" . $row['InvoiceID'] . "</td>";
            echo "<td>
                    <input type='hidden' name='employee_id' value='" . $row['Employee_ID'] . "'>
                    <input type='hidden' name='employee_name' value='" . htmlspecialchars($row['Employee_Name']) . "'>
                    " . htmlspecialchars($row['Employee_Name']) . "
                  </td>";
            echo "<td><input type='hidden' name='service_type' value='" . $row['Service_Type'] . "'>" . $row['Service_Type'] . "</td>";
            echo "<td><input type='hidden' name='total_days' value='" . $row['Total_Days'] . "'>" . $row['Total_Days'] . "</td>";
            echo "<td><input type='hidden' name='worked_days' value='" . $worked_days . "'>" . $worked_days . "</td>";
            echo "<td><input type='hidden' name='daily_rate' value='" . $row['Daily_Rate'] . "'>" . $row['Daily_Rate'] . "</td>";
            echo "<td><input type='hidden' name='total_pay' value='" . $total_price . "'>" . $total_price . "</td>";
            echo "<td>
                    <select name='status' class='form-select'>
                        <option value='Select' " . ($row['Expense_Status'] === null ? "selected" : "") . " readonly>Select</option>
                        <option value='Pending' " . ($row['Expense_Status'] === 'Pending' ? "selected" : "") . ">Pending</option>
                        <option value='Paid' " . ($row['Expense_Status'] === 'Paid' ? "selected" : "") . ">Paid</option>
                        <option value='Processing' " . ($row['Expense_Status'] === 'Processing' ? "selected" : "") . ">Processing</option>
                        <option value='In Review' " . ($row['Expense_Status'] === 'In Review' ? "selected" : "") . ">In Review</option>
                    </select>
                  </td>";
            echo "<td><button type='submit' class='btn btn-success btn-sm mt-1'>Update</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No data found</td></tr>";
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
