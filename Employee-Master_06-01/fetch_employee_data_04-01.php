<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $query = "SELECT * FROM emp_info WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $response = [];

        // Dynamically fetch all key-value pairs
        foreach ($employee as $key => $value) {
            if (in_array($key, ['police_verification_document', 'adhar_upload_doc', 'other_doc_name']) && $value) {
                // Include the field as-is for href links
                $response[$key] = $value;
            } else {
                $response[$key] = $value;
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Employee not found.']);
    }

    $stmt->close();
}
?>
