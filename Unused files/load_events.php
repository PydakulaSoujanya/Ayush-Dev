<?php
header('Content-Type: application/json');

// Database connection
include 'config.php';

// Fetch assignments
$sql = "SELECT trainee_name, customer_name, from_date, to_date FROM assignments";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => $row['trainee_name'] . " - " . $row['customer_name'], // Combining trainee and customer names
            'start' => $row['from_date'],
            'end' => $row['to_date']
        ];
    }
}

// Return events as JSON
echo json_encode($events);

$conn->close();
?>
