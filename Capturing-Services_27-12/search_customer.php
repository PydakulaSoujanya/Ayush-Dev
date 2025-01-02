<?php
// Database connection (config.php includes DB connection setup)
require '../config.php';

// Default response
$response = [
    'success' => false,
    'message' => 'Error preparing SQL statement.'
];

if (isset($_GET['search'])) {
    // Get the search term
    $search = mysqli_real_escape_string($conn, $_GET['search']); // Escaping search input to prevent SQL injection

    // SQL query using escaped search term
    $sql = "SELECT customer_name, emergency_contact_number, patient_name, relationship
            FROM customer_master
            WHERE emergency_contact_number LIKE '%$search%' OR customer_name LIKE '%$search%'";

    // Execute the query
    $queryResult = mysqli_query($conn, $sql);

    if ($queryResult) {
        $results = [];

        // Fetch all results
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $results[] = $row;
        }

        // Return a successful response with the customer data
        $response = [
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $results
        ];
    } else {
        $response['message'] = 'Error executing SQL query: ' . mysqli_error($conn);
    }
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
