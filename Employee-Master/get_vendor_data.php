<?php
header('Content-Type: application/json');

// Database connection
include 'config.php';
// Fetch vendor data
$result = $mysqli->query("SELECT id, vendor_name AS name, phone_number AS phone, bank_name, branch, account_number AS account_no, ifsc FROM vendors");

$vendors = [];
while ($row = $result->fetch_assoc()) {
    $vendors[] = $row;
}

// Return as JSON
echo json_encode(['vendors' => $vendors]);
?>
