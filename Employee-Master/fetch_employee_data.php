<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $employeeId = intval($_POST['id']); // Sanitize input
    $stmt = $conn->prepare("SELECT * FROM emp_info WHERE id = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Employee not found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
$conn->close();
?>