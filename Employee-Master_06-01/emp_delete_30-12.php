<?php
include('../config.php');

// Get employee ID from URL and validate it
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Prepare the delete query using prepared statements
    $query = "DELETE FROM emp_info WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    // Bind the parameter
    $stmt->bind_param("i", $id);
    
    // Execute the query
    if ($stmt->execute()) {
        // Redirect to table.php with a success message
        header("Location: table.php?message=Employee deleted successfully.");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to table.php with an error message
    header("Location: table.php?message=Invalid employee ID.");
    exit;
}

// Close the connection
$conn->close();
?>
