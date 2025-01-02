<?php
include('../config.php'); // Ensure this includes database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $employeeId = intval($_POST['id']); // Validate and sanitize the input

    // Fetch employee details
    $query = "SELECT * FROM emp_info WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Fetch associated documents
        $docQuery = "SELECT file_type, file_path FROM emp_documents WHERE emp_id = ?";
        $docStmt = $conn->prepare($docQuery);
        $docStmt->bind_param("i", $employeeId);
        $docStmt->execute();
        $docResult = $docStmt->get_result();

        // Collect documents with their file_type as keys
        while ($row = $docResult->fetch_assoc()) {
            // Store each document under its file_type
            $data[$row['file_type']] = $row['file_path'];
        }

        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Employee not found.']);
    }

    // Close statements
    $stmt->close();
    $docStmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}

// Close connection
$conn->close();
?>
