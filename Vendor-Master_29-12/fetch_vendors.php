<?php
include("../config.php");

// Get pagination and search parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$size = isset($_GET['size']) ? (int)$_GET['size'] : 10;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

// Calculate offset
$offset = ($page - 1) * $size;

// Prepare the SQL query with ordering and search functionality
$sql = "
    SELECT * FROM vendors 
    WHERE vendor_name LIKE ? 
    ORDER BY created_at DESC, id DESC 
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare SQL statement.']);
    exit;
}

$searchParam = "%$search%";
$stmt->bind_param("sii", $searchParam, $size, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

// Get total row count for pagination
$totalCountSql = "SELECT COUNT(*) AS total FROM vendors WHERE vendor_name LIKE ?";
$totalStmt = $conn->prepare($totalCountSql);
$totalStmt->bind_param("s", $searchParam);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalCount = $totalResult->fetch_assoc()['total'];

$totalPages = ceil($totalCount / $size);

// Return JSON response
echo json_encode([
    'rows' => $rows,
    'totalPages' => $totalPages,
    'totalCount' => $totalCount,
]);

$stmt->close();
$conn->close();
?>