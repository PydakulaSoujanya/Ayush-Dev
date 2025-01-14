<?php
include("config.php"); // Include your database connection file

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
    <link rel="stylesheet" href="/Users/hackercode/Downloads/tui.calendar-main/apps/calendar/src/css/layout.css"> <!-- Local TUI Calendar CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
 
</head>
<body>


    <div class="container-fluid dashboard mt-7">
        <!-- Left Section -->
        <div class="left-section">
                <div class="card">
    <div class="card-title">Employees<br>(Ayush)</div>
    <div class="card-body"><?php echo $ayush_count; ?></div>
</div>
            
<div class="card">
    <div class="card-title">Employees (Vendors)</div>
    <div class="card-body"><?php echo $vendors_count; ?></div>
</div>
<div class="card">
    <div class="card-title" style="line-height: 3.5rem;">Vendors</div>
    <div class="card-body">
        <?php echo ($vendor_count >= 10) ? str_pad($vendor_count, 2, '0', STR_PAD_LEFT) : $vendor_count; ?>
    </div>
</div>

<div class="card">
    <div class="card-title" style="line-height: 3.5rem;">Patients</div>
    <div class="card-body">
        <?php echo ($patient_count >= 10) ? str_pad($patient_count, 2, '0', STR_PAD_LEFT) : $patient_count; ?>
    </div>
</div>

<div class="card">
    <div class="card-title">Fully Trained Nurses</div>
    <div class="card-body">
        <?php echo ($nurse_count >= 10) ? str_pad($nurse_count, 2, '0', STR_PAD_LEFT) : $nurse_count; ?>
    </div>
</div>
            <div class="card">
                <div class="card-title" >Semi Trained Nurses</div>
                <div class="card-body" >07</div>
            </div>
            <div class="card">
                <div class="card-title" style="line-height: 3.5rem;">Caretakers </div>
                <div class="card-body">03</div>
            </div>
            <div class="card">
                <div class="card-title" style="line-height: 3.5rem;">Naanies</div>
                <div class="card-body">03</div>
            </div>
            <div class="card wide">
                <div class="card-title">Accounts Receivables</div>
                <div class="card-body" style="color: green;">3141</div>
            </div>
            <div class="card wide">
                <div class="card-title">Account Payables</div>
                <div class="card-body" style="color: red;">5161</div>
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
