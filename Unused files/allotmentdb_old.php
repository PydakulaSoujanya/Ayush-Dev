<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include "config.php"; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $employee_id = $_POST['employee_name']; // 'employee_name' contains employee_id from the form
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : null; // Ensure patient_id is retrieved
    $patient_name = isset($_POST['patient_name']) ? $_POST['patient_name'] : null; // Ensure patient_name is retrieved
    $service_type = $_POST['service_type'];
    $shift = $_POST['shift'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $no_of_hours = $_POST['no_of_hours'];

    // Validate inputs (example: make sure patient_id and patient_name are not null)
    // if (empty($patient_id) || empty($patient_name) || empty($employee_id) || empty($start_date) || empty($end_date)) {
    //     echo "<script>
    //         alert('Please fill in all required fields!');
    //         window.history.back();
    //     </script>";
    //     exit;
    // }

    // Fetch the employee name based on the selected employee ID
    $sql = "SELECT name FROM emp_info WHERE id = ? AND status = 'Active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $employee_name = $row['name'];
    } else {
        echo "<script>
            alert('Invalid employee selected!');
            window.history.back();
        </script>";
        exit;
    }

    $stmt->close();

    // Insert into the allotment table
    $sql = "INSERT INTO allotment (employee_id, name, patient_id, patient_name, service_type, shift, start_date, end_date, status, no_of_hours)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isisssssss",
        $employee_id,
        $employee_name,
        $patient_id,
        $patient_name,
        $service_type,
        $shift,
        $start_date,
        $end_date,
        $status,
        $no_of_hours
    );

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
