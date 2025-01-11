<?php
include('../config.php');

if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);

    $query = "
        SELECT id, patient_name, emergency_contact_number 
        FROM customer_master_new 
        WHERE patient_name LIKE '%$search%' OR emergency_contact_number LIKE '%$search%'
    ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
        echo json_encode($customers);
    } else {
        echo json_encode([]); // No results found
    }
}
?>
