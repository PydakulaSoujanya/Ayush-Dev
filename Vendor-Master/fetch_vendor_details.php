<?php


include("../config.php");

$id = $_GET['id'] ?? null;
if ($id === null) {
    echo json_encode(['error' => 'Vendor ID is required.']);
    exit;
}

// Prepare to call the stored procedure
$sql = $conn->prepare("CALL GetVendorById(?)");
if (!$sql) {
    echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
    exit;
}

$sql->bind_param("i", $id);
if (!$sql->execute()) {
    echo json_encode(['error' => 'Failed to execute SQL query: ' . $conn->error]);
    exit;
}

$result = $sql->get_result();
if (!$result) {
    echo json_encode(['error' => 'Error fetching result from the database.']);
    exit;
}

$data = $result->fetch_assoc();

if ($data) {
    // Return vendor data as JSON
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'No vendor found with the given ID.']);
}

$sql->close();
$conn->close();
?>
