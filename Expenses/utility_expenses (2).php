<?php
include('../config.php');

// Retrieve the search query
$query = $_GET['query'] ?? '';

if ($query) {
    // Query the database for matching vendors
    $sql = "SELECT id, vendor_name, phone_number FROM vendors 
            WHERE vendor_name LIKE ? OR phone_number LIKE ?
            LIMIT 10";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggestion-item' onclick=\"selectVendor('{$row['id']}', '{$row['vendor_name']}', '{$row['phone_number']}')\">" .
                 htmlspecialchars($row['vendor_name'] . " - " . $row['phone_number']) .
                 "</div>";
        }
    } else {
        echo "<div class='suggestion-item'>No results found</div>";
    }

    $stmt->close();
}
$conn->close();
?>
