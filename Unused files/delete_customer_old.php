<?php
// Include database configuration
include 'config.php';

// Check if the 'id' parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $customerId = intval($_GET['id']);  // Sanitize the input to prevent SQL injection

    // Prepare the DELETE query
    $sql = "DELETE FROM customer_master WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $customerId);
        
        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the main page with a success message
            header("Location: customer_table.php.?message=Record deleted successfully");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }
}

// Close the connection
$conn->close();
?>