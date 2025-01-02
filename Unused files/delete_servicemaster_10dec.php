<?php
// Include the database configuration file
include 'config.php';

// Check if the ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitize the input to prevent SQL injection (using intval for numeric input)
    $invoiceId = intval($_GET['id']);

    // Check if the ID is a valid number (you can add more validation if needed)
    if ($invoiceId > 0) {
        // SQL query to delete the record
        $deleteQuery = "DELETE FROM service_master WHERE id = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($deleteQuery)) {
            // Bind the ID parameter to the query
            $stmt->bind_param("i", $invoiceId);

            // Execute the query
            if ($stmt->execute()) {
                // Successfully deleted, redirect back to the table page with a success message
                header("Location: view_servicemaster.php?msg=Record deleted successfully");
                exit; // Make sure to exit after the redirect
            } else {
                // Error executing the query
                echo "Error deleting record: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error preparing the query
            echo "Error preparing the query: " . $conn->error;
        }
    } else {
        echo "Invalid invoice ID.";
    }
} else {
    // No ID provided, show error message
    echo "Invalid request. No ID provided.";
}

// Close the database connection
$conn->close();
?>
