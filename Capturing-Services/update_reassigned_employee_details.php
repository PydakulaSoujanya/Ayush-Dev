<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../config.php'; // Include your database configuration

    header('Content-Type: application/json');

    try {
        // Fetch POST data
        $serviceId = $_POST['service_id']; 
        $oldEmployeeID = $_POST['oldEmployeeID'];
        $newEmployeeID = $_POST['employee_id'];    
        $newEmployeeName = $_POST['new_employee'];  
        $fromDate = $_POST['from_date']; 
        $endDate = $_POST['end_date'];  

        // Get the current date in the proper format
        $currentDate = date('Y-m-d H:i:s');

        // Step 1: Fetch service details using $serviceId
        $serviceDetailsStmt = $conn->prepare("
            SELECT id, customer_name, customer_id, service_type, from_date, end_date, total_days, 
                   service_price, assigned_employee, per_day_service_price, status 
            FROM service_requests 
            WHERE id = ?
        ");
        $serviceDetailsStmt->bind_param("i", $serviceId);
        $serviceDetailsStmt->execute();
        $serviceDetails = $serviceDetailsStmt->get_result()->fetch_assoc();
        $serviceDetailsStmt->close();

        if (!$serviceDetails) {
            echo json_encode(['success' => false,  'message' => 'Service details not found.','error' => 'Service details not found.']);
            exit;
        }

        // Extract details
        $perDayServicePrice = $serviceDetails['per_day_service_price'];
        $oldAssignedEmployee = $serviceDetails['assigned_employee'];

        // Step 2: Calculate total days for the new employee based on selected dates
        $startDate = new DateTime($fromDate);
        $endDateObj = new DateTime($endDate);
        $totalDays = $startDate->diff($endDateObj)->days + 1; // Include both start and end dates
        $newAmount = $totalDays * $perDayServicePrice;

      
        // Step 3: Update the old employee's record
        $updateOldEmployeeStmt = $conn->prepare("
            UPDATE Expenses 
            SET status = 'Completed', updated_at = NOW(), description = CONCAT(description, ' - Adjusted due to employee replacement') 
            WHERE entity_id = ? AND status = 'Pending'
        ");
        $updateOldEmployeeStmt->bind_param("s", $oldEmployeeID);
        $updateOldEmployeeStmt->execute();
        $updateOldEmployeeStmt->close();

        // Step 4: Assign the new employee in the service request
        $updateServiceStmt = $conn->prepare("
            UPDATE service_requests 
            SET assigned_employee = ?, emp_id =?, from_date = ?, end_date = ? 
            WHERE id = ?
        ");
        $updateServiceStmt->bind_param("ssssi", $newEmployeeName,$newEmployeeID, $fromDate, $endDate, $serviceId);
        $updateServiceStmt->execute();
        $updateServiceStmt->close();

        // Step 5: Insert a new expense record for the new employee
        $expense_type = "Employee Payout";
        $payment_status = "Pending";
        $status = "Pending";
        $expense_date = date('Y-m-d');
        $description = "Payout for employee replacement from $fromDate to $endDate";
        $additional_details = "";

        $newExpenseStmt = $conn->prepare("
            INSERT INTO Expenses (expense_type, entity_id, entity_name, status, payment_status, description, amount, date_incurred, additional_details, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $newExpenseStmt->bind_param(
            "sssssdsss", 
            $expense_type, $newEmployeeID, $newEmployeeName, $status, $payment_status, $description, $newAmount, $expense_date, $additional_details
        );

        if (!$newExpenseStmt->execute()) {
            throw new Exception("Error: " . $newExpenseStmt->error);
        }
        $newExpenseStmt->close();

        // Commit the transaction
        $conn->commit();

        
        echo json_encode(['success' => true, 'message' => 'Employee replacement handled successfully.']);

    } catch (Exception $e) {
        

        // Send a failure response with the error message
        echo json_encode(['success' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
}
?>
