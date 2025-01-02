<?php
// Include database connection
include('../config.php');

// Retrieve request parameters sent by DataTables
$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = $_POST['search']['value']; // Search value

// Query to count total records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM vendors";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Query to filter records based on search
$searchQuery = "";
if (!empty($searchValue)) {
    $searchQuery = "WHERE vendor_name LIKE '%$searchValue%' 
                    OR phone_number LIKE '%$searchValue%' 
                    OR email LIKE '%$searchValue%' 
                    OR vendor_type LIKE '%$searchValue%'";
}

// Query to fetch filtered data with pagination
$vendorQuery = "SELECT * FROM vendors $searchQuery ORDER BY created_at DESC LIMIT $start, $length";
$vendorResult = $conn->query($vendorQuery);

$vendors = [];
while ($row = $vendorResult->fetch_assoc()) {
    $vendors[] = $row;
}

// Count filtered records
$filteredRecordsQuery = "SELECT COUNT(*) AS total FROM vendors $searchQuery";
$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$filteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Prepare response
$response = [
    'draw' => $draw,
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $filteredRecords,
    'data' => $vendors
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
