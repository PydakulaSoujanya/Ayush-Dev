<?php
include('../config.php'); // Include your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $employeeId = intval($_POST['id']); // Sanitize input

    // Query to fetch employee data from emp_info table
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

        while ($row = $docResult->fetch_assoc()) {
            $data[$row['file_type']] = $row['file_path'];
        }

        echo json_encode($data); // Return data as JSON
    } else {
        echo json_encode(['error' => 'Employee not found.']); // Error if no employee found
    }

    // Close statements
    $stmt->close();
    $docStmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}

$conn->close(); // Close the DB connection
?>
