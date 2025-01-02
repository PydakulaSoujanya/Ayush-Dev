<?php
include('../config.php');

$search = $_GET['search'] ?? '';
$response = ['success' => false, 'data' => []];

if ($search) {
  $query = "SELECT id, name, phone FROM emp_info WHERE name LIKE '%$search%' OR phone LIKE '%$search%'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $response['data'][] = $row;
    }
    $response['success'] = true;
  }
}

header('Content-Type: application/json');
echo json_encode($response);
?>