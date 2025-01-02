<?php
// include('navbar.php');
include('../config.php'); // Include your database connection file

// Handle month and year selection
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

// Fetch employees from emp_info table
$employees = [];
$employeeQuery = "SELECT id, name FROM emp_info WHERE status = 'active'";
$result = $conn->query($employeeQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[$row['id']] = $row['name']; // Store as key-value pair for faster lookup
    }
}

// Fetch customers from customer_master table
$customers = [];
$customerQuery = "SELECT id, patient_name FROM customer_master";
$result = $conn->query($customerQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[$row['id']] = $row['patient_name']; // Store as key-value pair for faster lookup
    }
}

// Fetch all allotments data
$allotments = [];
$allotmentQuery = "
    SELECT employee_id, patient_name, no_of_hours, day_1, day_2, day_3, day_4, day_5, day_6, day_7, 
           day_8, day_9, day_10, day_11, day_12, day_13, day_14, day_15, day_16, day_17, 
           day_18, day_19, day_20, day_21, day_22, day_23, day_24, day_25, day_26, 
           day_27, day_28, day_29, day_30, day_31 
    FROM allotment";
$result = $conn->query($allotmentQuery);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allotments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Calendar</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Background colors for each type of work hours */
        .day-8 {
            background-color: #c8e6c9; /* Light green for 8 hours */
        }

        .day-12 {
            background-color: #ffe0b2; /* Light orange for 12 hours */
        }

        .day-24 {
            background-color: #bbdefb; /* Light blue for 24 hours */
        }
    </style>
</head>
<body>

<?php include('../navbar.php'); ?>

<div class="container mt-7">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-center mb-0">Employee Calendar - <?= date('F Y', strtotime("$selectedYear-$selectedMonth-01")) ?></h3>
        <a href="allotment_form.php" class="btn btn-primary">New Allotment</a>
    </div>

    <!-- Month and Year Selection -->
    <!-- <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="col-md-5">
                <select name="month" class="form-control">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 1));
                        $selected = ($m == $selectedMonth) ? 'selected' : '';
                        echo "<option value=\"$m\" $selected>$monthName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-5">
                <select name="year" class="form-control">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++) {
                        $selected = ($y == $selectedYear) ? 'selected' : '';
                        echo "<option value=\"$y\" $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">View</button>
            </div>
        </div>
    </form> -->

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Emp Name</th>
                    <th>Customer Name</th>
                    <?php
                    // Generate table headers for the days of the selected month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        echo "<th>$day</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
foreach ($allotments as $allotment) {
    $employeeName = isset($employees[$allotment['employee_id']]) ? $employees[$allotment['employee_id']] : 'Unknown';
    $customerName = isset($customers[$allotment['patient_name']]) ? $customers[$allotment['patient_name']] : 'Unknown';

    echo "<tr>";
    echo "<td>$employeeName</td>";
    echo "<td>$customerName</td>";

    // Display day-wise data
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $columnName = "day_$day";
        $dayValue = isset($allotment[$columnName]) ? $allotment[$columnName] : '';
        $hourClass = ''; // Default to no color

        // Apply background color based on no_of_hours if day is allotted
        if ($dayValue === 'yes') {
            $dayValue = $allotment['no_of_hours']; // Display the number of hours instead of 'yes'

            if ($allotment['no_of_hours'] == 8) {
                $hourClass = 'day-8'; // Light green for 8 hours
            } elseif ($allotment['no_of_hours'] == 12) {
                $hourClass = 'day-12'; // Light orange for 12 hours
            } elseif ($allotment['no_of_hours'] == 24) {
                $hourClass = 'day-24'; // Light blue for 24 hours
            }
        }

        echo "<td class=\"$hourClass\">$dayValue</td>";
    }

    echo "</tr>";
}
?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>
