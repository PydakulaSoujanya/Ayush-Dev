<?php
include '../config.php'; 

header('Content-Type: application/json');

$sql = "SELECT id, vendor_name, phone_number, bank_name, branch, account_number, ifsc FROM vendors";
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
mysqli_close($conn);
?>