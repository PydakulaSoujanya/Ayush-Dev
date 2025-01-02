<?php
include("../config.php"); // Include your database connection file

include ("../navbar.php");

// Query to get the counts of employees for 'ayush' and 'vendors'
$sql = "SELECT 
            SUM(CASE WHEN reference = 'ayush' THEN 1 ELSE 0 END) AS ayush_count,
            SUM(CASE WHEN reference = 'vendors' THEN 1 ELSE 0 END) AS vendors_count
        FROM emp_info";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Fetch the counts
$ayush_count = $data['ayush_count'];
$vendors_count = $data['vendors_count'];

// Query to count the number of vendors
$sql = "SELECT COUNT(id) AS vendor_count FROM vendors";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Fetch the vendor count
$vendor_count = $data['vendor_count'];

$sql = "SELECT COUNT(id) AS patient_count FROM customer_master";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Fetch the patient count
$patient_count = $data['patient_count'];

$sql = "SELECT COUNT(id) AS nurse_count FROM emp_info WHERE role = 'fully_trained_nurse'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Fetch the nurse count
$nurse_count = $data['nurse_count'];

// Fetch the count of semi-trained nurses
$sql = "SELECT COUNT(id) AS semi_trained_nurse_count FROM emp_info WHERE role = 'semi_trained_nurse'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$semi_trained_nurse_count = $data['semi_trained_nurse_count'];

// Fetch the count of caretakers
$sql = "SELECT COUNT(id) AS caretaker_count FROM emp_info WHERE role = 'care_taker'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$caretaker_count = $data['caretaker_count'];

// Fetch the count of nannies
$sql = "SELECT COUNT(id) AS nannies_count FROM emp_info WHERE role = 'nannies'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$nannies_count = $data['nannies_count'];



$total_balance = 0;

// Fetch service requests
$sql1 = "SELECT * FROM service_requests";
$result1 = mysqli_query($conn, $sql1);

if ($result1 && $result1->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result1)) {
        $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';

        // Fetch invoice ID for this specific row (service request)
        $serviceId = $row['id'];
        $invoiceQuery = "SELECT invoice_id FROM invoice WHERE service_id = ?";
        $stmt = $conn->prepare($invoiceQuery);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $invoiceResult = $stmt->get_result();

        // Fetch the invoice ID if it exists
        $invoiceId = null;
        $totalPaidAmount = 0;

        if ($invoiceResult->num_rows > 0) {
            $invoiceRow = $invoiceResult->fetch_assoc();
            $invoiceId = $invoiceRow['invoice_id'];
            $paidAmountQuery = "SELECT SUM(paid_amount) AS total_paid FROM invoice WHERE invoice_id = ? AND receipt_id IS NOT NULL";
            $paidStmt = $conn->prepare($paidAmountQuery);
            $paidStmt->bind_param("s", $invoiceId);
            $paidStmt->execute();
            $paidResult = $paidStmt->get_result();

            if ($paidRow = $paidResult->fetch_assoc()) {
                $totalPaidAmount = $paidRow['total_paid'] ?? 0; // Handle null sum
            }
        }

        $service_price = $row['service_price']; // Assuming $row['service_price'] is fetched from a database
        $deduction = $totalPaidAmount;
        $balance = $service_price - $deduction;

        // Add the calculated balance to the total balance
        $total_balance += $balance;
    }
}

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

$uniqueEmployees = [];
$serial_no = 0;
$total_amount_to_pay = 0;
$payablesData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip duplicates based on employee ID and service type
        if (in_array($row['Employee_ID'] . '-' . $row['Service_Type'], $uniqueEmployees)) {
            continue;
        }
        $uniqueEmployees[] = $row['Employee_ID'] . '-' . $row['Service_Type'];

        // Calculate worked days (if needed)
        $worked_days = $row['Total_Days']; // Modify this logic as required

        // Calculate total price (worked days * daily rate)
        $total_price = $worked_days * $row['Daily_Rate'];
        $total_amount_to_pay += $total_price;

        // Collect data for display
        $payablesData[] = [
            'serial_no' => ++$serial_no,
            'employee_name' => $row['Employee_Name'],
            'service_type' => $row['Service_Type'],
            'worked_days' => $worked_days,
            'daily_rate' => $row['Daily_Rate'],
            'total_price' => $total_price,
            'expense_status' => $row['Expense_Status'],
        ];
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Dashboard Layout</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Users/hackercode/Downloads/tui.calendar-main/apps/calendar/src/css/layout.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css"> 
</head>
<body>

   <section class="dashboard-section">
    <div class="container-fluid dashboard">
        <!-- Left Section -->
        <div class="left-section">
                <div class="card">
    <div class="card-title">Employees (Ayush)</div>
    <div class="card-body"><?php echo $ayush_count; ?></div>
</div>
            
<div class="card">
    <div class="card-title">Employees (Vendors)</div>
    <div class="card-body"><?php echo $vendors_count; ?></div>
</div>
            <div class="card">
    <div class="card-title" >Vendors</div>
    <div class="card-body"><?php echo str_pad($vendor_count, 2, '0', STR_PAD_LEFT); ?></div>
</div>
            
<div class="card">
    <div class="card-title" >Patients</div>
    <div class="card-body"><?php echo str_pad($patient_count, 2, '0', STR_PAD_LEFT); ?></div>
</div>
            <div class="card">
    <div class="card-title">Fully Trained Nurses</div>
    <div class="card-body"><?php echo str_pad($nurse_count, 2, '0', STR_PAD_LEFT); ?></div>
</div>
            <div class="card">
            <div class="card-title">Semi Trained Nurses</div>
            <div class="card-body"><?php echo $semi_trained_nurse_count; ?></div>
            </div>
            <div class="card">
            <div class="card-title">Caretakers</div>
            <div class="card-body"><?php echo $caretaker_count; ?></div>
            </div>
            <div class="card">
            <div class="card-title">Nannies</div>
            <div class="card-body"><?php echo $nannies_count; ?></div>
            </div>
            <div class="card wide">
                <div class="card-title">Accounts Receivables</div>
                <div class="card-body" style="color: green;">
                        <?php echo number_format($total_balance, 2); // Display formatted balance ?>
                    </div>
            </div>


            <div class="card wide">
                <div class="card-title">Account Payables</div>
                <div class="card-body" style="color: red;">
                <?php echo number_format($total_amount_to_pay, 2); ?>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="card wide">
                <div class="card-title">Company Profile</div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <!-- Profile Picture -->
                    <div class="profile-picture">
                        <img src="https://via.placeholder.com/80" alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #027cc9;">
                    </div>
            
                    <!-- Company Info -->
                    <div class="company-info" style="flex-grow: 1; margin-left: 15px; font-size: 0.9rem; line-height: 1.5;">
                        <h6 style="margin: 0; color: #383636;"><strong>Ayush Healthcare </strong></h6>
                        <p style="margin: 5px 0; color: #383636;">Leading provider of healthcare solutions.</p>
                        <p style="margin: 5px 0; color: #383636;"><strong>Established:</strong> 2010</p>
                        <p style="margin: 5px 0; color: #383636;"><strong>Location:</strong> Bangalore, India</p>
                    </div>
            
                    <!-- Contact Info -->
                    <div class="contact-info" style="text-align: right; font-size: 0.9rem; color: #383636;">
                        <p style="margin: 5px 0;"><strong>Phone:</strong> +91-9876543210</p>
                        <p style="margin: 5px 0;"><strong>Email:</strong> info@ayush.com</p>
                    </div>
                </div>
            </div>
            
            
            <div class="chart-container">
                <div class="chart-filters">
                    <button id="weekly" class="active">Weekly</button>
                    <button id="monthly">Monthly</button>
                    <button id="yearly">Yearly</button>
                </div>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    </section>
    <script>
               // Chart.js Data and Filters
        const chartData = {
            weekly: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Account Payables',
                        data: [100, 200, 150, 300, 250, 200, 100],
                        borderColor: '#ff6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2
                    },
                    {
                        label: 'Account Receivables',
                        data: [150, 250, 200, 350, 300, 250, 150],
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2
                    }
                ]
            },
            monthly: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [
                    {
                        label: 'Account Payables',
                        data: [800, 1200, 900, 1400],
                        borderColor: '#ff6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2
                    },
                    {
                        label: 'Account Receivables',
                        data: [900, 1300, 1000, 1500],
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2
                    }
                ]
            },
            yearly: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Account Payables',
                        data: [4000, 4500, 4200, 4800, 5000, 5200, 5100, 5300, 5400, 5500, 5700, 5900],
                        borderColor: '#ff6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2
                    },
                    {
                        label: 'Account Receivables',
                        data: [4500, 4700, 4600, 4900, 5200, 5300, 5200, 5500, 5600, 5700, 5900, 6000],
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2
                    }
                ]
            }
        };

        const lineCtx = document.getElementById('lineChart').getContext('2d');
        let lineChart = new Chart(lineCtx, {
            type: 'line',
            data: chartData.weekly,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            },
                            color: '#03121b'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#03121b',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        ticks: {
                            color: '#03121b',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('weekly').addEventListener('click', () => {
            document.querySelectorAll('.chart-filters button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('weekly').classList.add('active');
            lineChart.data = chartData.weekly;
            lineChart.update();
        });

        document.getElementById('monthly').addEventListener('click', () => {
            document.querySelectorAll('.chart-filters button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('monthly').classList.add('active');
            lineChart.data = chartData.monthly;
            lineChart.update();
        });

        document.getElementById('yearly').addEventListener('click', () => {
            document.querySelectorAll('.chart-filters button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('yearly').classList.add('active');
            lineChart.data = chartData.yearly;
            lineChart.update();
        });
    </script>
</body>
</html>
