<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ayush_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$amount = $_GET['amount'];

$sql = "SELECT v.voucher_number, v.purchase_invoice_number, v.vendor_id, vend.vendor_name 
        FROM vouchers_new v
        INNER JOIN vendors vend ON v.vendor_id = vend.id
        WHERE v.paid_amount = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("d", $amount);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'voucher_number' => $row['voucher_number'],
        'purchase_invoice_number' => $row['purchase_invoice_number'],
        'vendor_name' => $row['vendor_name']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No records found']);
}

$stmt->close();
$conn->close();
?>
