<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Allow all HTTP methods
header('Access-Control-Allow-Methods:  POST, PUT');

// Allow all headers
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $customer_name = $_POST['customer_name'];
    $mobile_number = $_POST['mobile_number'];
    $customer_email = $_POST['customer_email'];
    $total_amount = $_POST['total_amount'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $sql = "UPDATE invoice SET 
                customer_name = ?, 
                mobile_number = ?, 
                customer_email = ?, 
                total_amount = ?, 
                due_date = ?, 
                status = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $customer_name, $mobile_number, $customer_email, $total_amount, $due_date, $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
