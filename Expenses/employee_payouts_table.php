<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Employee Payouts</title>
    <style>
        .center-table {
            margin: 0 auto;
            text-align: left;
        }
        .center-table-card {
            background-color: #A26D2B;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include '../navbar.php'; ?>

<div class="container text-center vh-100">
    <div class="card center-table">
        <div class="card-header center-table-card">
            <h5>Employee Payout Info</h5>
            <?php
            if (isset($_SESSION['message'])) {
                $message = $_SESSION['message'];
                $messageType = $_SESSION['message_type'];
                echo "<div class='alert alert-$messageType alert-dismissible fade show' role='alert'>
                        $message
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
        </div>
        <div class="card-body">
            <form method="POST" id="payoutForm" action="update_payouts.php">
                <div class="table-responsive">
                    <table id="employeeTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Employee Name</th>
                                <th>Assigned Service</th>
                                <th>Customer Name</th>
                                <th>Total Days</th>
                                <th>Worked Days</th>
                                <th>Daily Rate</th>
                                <th>Total Pay</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                                        assigned_employee, 
                                        service_type, 
                                        customer_name, 
                                        total_days, 
                                        total_service_price, 
                                        emp_id, 
                                        status 
                                    FROM service_requests 
                                    WHERE assigned_employee IS NOT NULL 
                                    ORDER BY created_at DESC";

                            $result = $conn->query($sql);
                            $serial_no = 1;

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $daily_rate = $row['total_service_price'] / $row['total_days'];
                                    $worked_days = $row['total_days']; // Assuming all days are worked
                                    $total_pay = $daily_rate * $worked_days;

                                    echo "<tr>";
                                    echo "<td>$serial_no</td>";
                                    echo "<td>{$row['assigned_employee']}</td>";
                                    echo "<td>{$row['service_type']}</td>";
                                    echo "<td>{$row['customer_name']}</td>";
                                    echo "<td>{$row['total_days']}</td>";
                                    echo "<td>$worked_days</td>";
                                    echo "<td>" . number_format($daily_rate, 2) . "</td>";
                                    echo "<td>" . number_format($total_pay, 2) . "</td>";
                                    echo "<td>
                                        <select name='status[]' class='form-select'>
                                            <option value='Pending'" . ($row['status'] === 'Pending' ? " selected" : "") . ">Pending</option>
                                            <option value='Paid'" . ($row['status'] === 'Paid' ? " selected" : "") . ">Paid</option>
                                        </select>
                                    </td>";
                                    echo "</tr>";

                                    $serial_no++;
                                }
                            } else {
                                echo "<tr><td colspan='9'>No records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#employeeTable').DataTable();
    });
</script>
</body>
</html>
