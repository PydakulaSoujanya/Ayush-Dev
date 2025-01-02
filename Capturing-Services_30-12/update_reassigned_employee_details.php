<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    require '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $employeeId = $_POST['employee_id'];    
    $newEmployeeName = $_POST['new_employee'];  // Assuming 'new_employee' is the employee's name, not ID.
    $reason = $_POST['reason'];              
    $serviceId = $_POST['service_id']; 
    $fromDate = $_POST['from_date']; 
    $endDate = $_POST['end_date'];      

    // Get the current date in the proper format
    $currentDate = date('Y-m-d H:i:s');

    // Start a transaction to ensure both operations succeed or fail together
    $conn->begin_transaction();

    try {
       
        $updateQuery = "UPDATE service_requests 
                         SET emp_id = ?, assigned_employee = ?
                        WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('isi', $employeeId, $newEmployeeName, $serviceId); // 'i' for integer, 's' for string
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Error updating service_request table");
        }
        
            // $updateQuery = "UPDATE service_requests 
            //                 SET emp_id = ?, assigned_employee = ?, from_date = ?, end_date = ?
            //                 WHERE id = ?";
            // $stmt = $conn->prepare($updateQuery);
            // $stmt->bind_param('isssi', $employeeId, $newEmployeeName, $fromDate, $endDate, $serviceId); // 'i' for integer, 's' for string
            // $stmt->execute();
        
            // if ($stmt->affected_rows === 0) {
            //     throw new Exception("Error updating service_request table");
            // }
        
            // Insert a record into the emp_history table
            $historyQuery = "INSERT INTO emp_history (service_id, emp_id, employee_name, assignment_reason, assignment_date, from_date, end_date) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($historyQuery);
            $stmt->bind_param('iisssss', $serviceId, $employeeId, $newEmployeeName, $reason, $currentDate, $fromDate, $endDate);
            $stmt->execute();
        
        
       
      

        // $historyQuery = "INSERT INTO emp_history (service_id, emp_id, employee_name, assignment_reason, assignment_date) 
        //                  VALUES (?, ?, ?, ?, ?)";
        // $stmt = $conn->prepare($historyQuery);
        // $stmt->bind_param('iisss', $serviceId, $employeeId, $newEmployeeName, $reason, $currentDate);
        // $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Error inserting into emp_history table");
        }

        // Commit the transaction if everything was successful
        $conn->commit();

        // Send a success response as JSON
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        // Rollback the transaction if there was an error
        $conn->rollback();

        // Send a failure response with the error message
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
}
?>
