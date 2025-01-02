<?php
include('../config.php');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT name, phone FROM emp_info WHERE name LIKE '%$search%' OR phone LIKE '%$search%' LIMIT 10";
    $result = mysqli_query($conn, $query);

    $employees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = [
            'id' => $row['name'], // Use `name` as the ID
            'text' => $row['name'] . " (" . $row['phone'] . ")"
        ];
    }

    echo json_encode($employees);
}
?>