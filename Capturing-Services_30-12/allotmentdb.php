<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include "../config.php"; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if form data is set before accessing
    $employee_id = isset($_POST['name']) ? $_POST['name'] : null;
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : null;
    $patient_name = isset($_POST['patient_name']) ? $_POST['patient_name'] : null;
    $service_type = isset($_POST['service_type']) ? $_POST['service_type'] : null;
    $shift = isset($_POST['shift']) ? $_POST['shift'] : null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $no_of_hours = isset($_POST['no_of_hours']) ? $_POST['no_of_hours'] : null;

    // Validate employee_id
    if ($employee_id === null) {
        echo "<script>alert('Employee ID is missing!'); window.history.back();</script>";
        exit;
    }

    // Validate date inputs
    if ($start_date === null || $end_date === null) {
        echo "<script>alert('Start date or end date is missing!'); window.history.back();</script>";
        exit;
    }

    // Calculate the day range based on start_date and end_date
    $startDate = new DateTime($start_date);
    $endDate = new DateTime($end_date);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($startDate, $interval, $endDate->modify('+1 day')); // Include the end date

    // Prepare the day columns for storage
    $allotmentDays = array_fill(1, 31, ''); // Default array with empty values for day_1 to day_31

    foreach ($dateRange as $date) {
        $day = $date->format('j'); // Extract the day from the date (e.g., 6, 7, 8)
        if ($day >= 1 && $day <= 31) {
            $allotmentDays["day_$day"] = 'yes'; // Mark the day as "yes"
        }
    }

    // Prepare the SQL for inserting the record into the allotment table
    $sql = "INSERT INTO allotment (
        employee_id, patient_id, patient_name, service_type, shift, 
        start_date, end_date, status, no_of_hours, 
        day_1, day_2, day_3, day_4, day_5, day_6, day_7, day_8, day_9, day_10, 
        day_11, day_12, day_13, day_14, day_15, day_16, day_17, day_18, day_19, 
        day_20, day_21, day_22, day_23, day_24, day_25, day_26, day_27, day_28, 
        day_29, day_30, day_31
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

     $bindParams = "isisssssss" .
        "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" .
        "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" .
        "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" . "s" .
        "s"; // 31 placeholders for day_1 to day_31
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    $stmt->bind_param(
        $bindParams, 
        $employee_id,       // employee_id (integer)
        $patient_id,        // patient_id (string or null)
        $patient_name,      // patient_name (string or null)
        $service_type,      // service_type (string)
        $shift,             // shift (string)
        $start_date,        // start_date (string)
        $end_date,          // end_date (string)
        $status,            // status (string or null)
        $no_of_hours,       // no_of_hours (string)
        // Explicitly passing each day value for day_1 to day_31
        $allotmentDays["day_1"], $allotmentDays["day_2"], $allotmentDays["day_3"], $allotmentDays["day_4"], 
        $allotmentDays["day_5"], $allotmentDays["day_6"], $allotmentDays["day_7"], $allotmentDays["day_8"], 
        $allotmentDays["day_9"], $allotmentDays["day_10"], $allotmentDays["day_11"], $allotmentDays["day_12"], 
        $allotmentDays["day_13"], $allotmentDays["day_14"], $allotmentDays["day_15"], $allotmentDays["day_16"], 
        $allotmentDays["day_17"], $allotmentDays["day_18"], $allotmentDays["day_19"], $allotmentDays["day_20"], 
        $allotmentDays["day_21"], $allotmentDays["day_22"], $allotmentDays["day_23"], $allotmentDays["day_24"], 
        $allotmentDays["day_25"], $allotmentDays["day_26"], $allotmentDays["day_27"], $allotmentDays["day_28"], 
        $allotmentDays["day_29"], $allotmentDays["day_30"], $allotmentDays["day_31"]
    );

    // Execute the statement and handle success/failure
    if ($stmt->execute()) {
        echo "<script>
            alert('Allotment successfully created!');
            window.location.href = 'emp_allotment.php'; // Redirect to a specific page after success
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $conn->error . "');
            window.history.back(); // Go back to the previous page
        </script>";
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request method!";
    exit;
}
?>
