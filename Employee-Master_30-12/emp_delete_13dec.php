<?php
include('../config.php');

// Get employee ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Delete query
    $query = "DELETE FROM emp_info WHERE id = $id";

    if ($conn->query($query)) {
        header("Location: manage_employee.php?message=Employee deleted successfully.");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: manage_employee.php?message=Invalid employee ID.");
    exit;
}
?>
