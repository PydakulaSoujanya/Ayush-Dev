<?php
include 'config.php';
header('Content-Type: application/json'); // Ensure JSON response

if (isset($_GET['reference']) && $_GET['reference'] === 'vendors') {
    $sql = "SELECT id, vendor_name, phone_number FROM vendors";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['error' => 'SQL Error: ' . mysqli_error($conn)]);
        exit;
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid reference or no reference provided']);
}

mysqli_close($conn);
