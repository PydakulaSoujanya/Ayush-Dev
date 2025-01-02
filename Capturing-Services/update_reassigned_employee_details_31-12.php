<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    require '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $oldEmployeeID = isset($_POST['oldEmployeeID']) ? $_POST['oldEmployeeID'] : '';
$serviceFromDate = isset($_POST['serviceFromDate']) ? $_POST['serviceFromDate'] : '';
$serviceEndDate = isset($_POST['serviceEndDate']) ? $_POST['serviceEndDate'] : '';


    $employeeId = $_POST['employee_id'];    
    $newEmployeeName = $_POST['new_employee'];  
    // $empId== $_POST['employee_id'];  
    //  $empName== $_POST['new_employee']; 

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
        
            
        
            // Insert a record into the emp_history table
            $historyQuery = "INSERT INTO emp_history (service_id, emp_id, employee_name, assignment_reason, assignment_date, from_date, end_date) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($historyQuery);
            $stmt->bind_param('iisssss', $serviceId, $employeeId, $newEmployeeName, $reason, $currentDate, $fromDate, $endDate);
            $stmt->execute();
        


        if ($stmt->affected_rows === 0) {
            throw new Exception("Error inserting into emp_history table");
        }


    //     $description="";
    //     $expense_type = "Employee Payout";
    //     $payment_status = "Pending";
    //     $expense_date = date('Y-m-d'); // Current date
    //     $additional_details = ""; // Use file name as additional details if uploaded
    //     $payment_status="Pending";
    //     $status="Pending";
    
    //     $expenseStmt = $conn->prepare("
    //     INSERT INTO Expenses (expense_type, entity_id, entity_name, status, payment_status, description, amount, date_incurred, additional_details, created_at, updated_at) 
    //     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    // ");
    
    //     $expenseStmt->bind_param(
    //       "sssssdsss", 
    //       $expense_type, $employeeId, $newEmployeeName, $status, $payment_status, $description, $total_amount, $expense_date, $additional_details
    //   );
     
    //     if ($expenseStmt->execute()) {
    //      // echo "<script>alert('Expense claim submitted successfully!');        </script>";
    //   } else {
    //       echo "<script>alert('Error: " . $expenseStmt->error . "'); 
        
    //     </script>";
    //   }

    //     // Commit the transaction if everything was successful
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
