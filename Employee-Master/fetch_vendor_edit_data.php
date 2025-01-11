<?php
include '../config.php'; 

header('Content-Type: application/json');

if (isset($_GET['vendor_id'])) {
    $vendor_id = intval($_GET['vendor_id']);
    $sql = "SELECT id, vendor_name, phone_number, beneficiary_name, bank_name, branch, account_number, ifsc FROM vendors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $vendor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $sql = "SELECT id, vendor_name, phone_number, beneficiary_name, bank_name, branch, account_number, ifsc FROM vendors";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
mysqli_close($conn);
?>
