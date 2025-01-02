<?php
include("../config.php");

// Check if the 'id' parameter is set via POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize the input to prevent SQL injection

    // Prepare the SQL query to call the stored procedure
    $query = "CALL delete_vendor(?)";

    // Initialize prepared statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to prepare the statement: " . $conn->error
        ]);
        exit;
    }

    // Bind the 'id' parameter to the stored procedure
    $stmt->bind_param("i", $id);

    // Execute the stored procedure
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Vendor deleted successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete the vendor. Please try again."
        ]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request. Vendor ID is missing."
    ]);
}
?>
