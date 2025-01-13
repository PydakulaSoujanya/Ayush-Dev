<?php

ob_start(); // Start output buffering

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../config.php'; // Include your database configuration

    header('Content-Type: application/json');
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }
    
    try {
        // Fetch POST data
        $serviceId = $_POST['service_id']; 
        $oldEmployeeID = $_POST['oldEmployeeID'];
        $newEmployeeID = $_POST['employee_id'];    
        $newEmployeeName = $_POST['new_employee'];  
        $fromDate = $_POST['from_date']; 
        $endDate = $_POST['end_date']; 
        $service_duration = $_POST['service_duration'];  


      

        // Get the current date in the proper format
        $currentDate = date('Y-m-d H:i:s');

        // Step 1: Fetch service details using $serviceId
        $serviceDetailsStmt = $conn->prepare("
            SELECT id, customer_name, customer_id, service_type, from_date, end_date, total_days, 
                  total_price, assigned_employee, per_day_service_price, status 
            FROM service_requests 
            WHERE id = ?
        ");
        $serviceDetailsStmt->bind_param("i", $serviceId);
        if (!$serviceDetailsStmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch service details.']);
            exit;
        }
        $serviceDetails = $serviceDetailsStmt->get_result()->fetch_assoc();
        $serviceDetailsStmt->close();

        if (!$serviceDetails) {
            echo json_encode(['success' => false,  'message' => 'Service details not found.','error' => 'Service details not found.']);
            exit;
        }
    


    $total_price = $serviceDetails['total_price'];
        
        $perDayServicePrice = $serviceDetails['per_day_service_price'];
        $oldAssignedEmployee = $serviceDetails['assigned_employee'];

        
        $startDate = new DateTime($fromDate);
        $endDateObj = new DateTime($endDate);
        $totalDays = $startDate->diff($endDateObj)->days + 1; // Include both start and end dates
      
      
    

// SQL query to fetch the correct service rate for the old employee
$oldEmployeeQuery = "
SELECT 
    `id`, 
    CASE
        WHEN ? = 'daily_rate8' THEN `daily_rate8`
        WHEN ? = 'daily_rate12' THEN `daily_rate12`
        WHEN ? = 'daily_rate24' THEN `daily_rate24`
        ELSE NULL
    END AS `service_rate`
FROM `emp_info`
WHERE `id` = ?;
";

// Prepare and execute the query for the old employee
$oldEmployeeStmt = $conn->prepare($oldEmployeeQuery);
$oldEmployeeStmt->bind_param("sssi", $service_duration, $service_duration, $service_duration, $oldEmployeeID); // Binding parameters
if (!$oldEmployeeStmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch old employee service rate' .$service_duration. 'in databsase table']);
    exit;
}

// Get the result for old employee
$oldEmployeeResult = $oldEmployeeStmt->get_result();

// Fetch the row and get the service rate for the old employee
if ($row = $oldEmployeeResult->fetch_assoc()) {
    $oldEmployeeServiceRate = $row['service_rate'];  // Get the service rate for the old employee
    
// echo json_encode(['success' => true, 'message' => 'service rate found for old employee with id ' . $oldEmployeeID . '. ' .$service_duration. 'in databsase table']);
// exit;
}
       else
       {
        echo json_encode(['success' => false, 'message' => 'No service rate found for old employee with id ' . $oldEmployeeID . '.' .$service_duration. 'in databsase table']);
exit;
       }

       
        
$query = "
SELECT 
    `id`, 
    CASE
        WHEN ? = 'daily_rate8' THEN `daily_rate8`
        WHEN ? = 'daily_rate12' THEN `daily_rate12`
        WHEN ? = 'daily_rate24' THEN `daily_rate24`
        ELSE NULL
    END AS `service_rate`
FROM `emp_info`
WHERE `id` = ?;
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $service_duration, $service_duration, $service_duration, $newEmployeeID); // Binding parameters
$stmt->execute();

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch new employee service rate.']);
    exit;
}
// Get the result
$result = $stmt->get_result();

// Fetch the row and calculate the service rate
if ($row = $result->fetch_assoc()) {
$service_rate = $row['service_rate'];

// Calculate total service rate based on the number of days
$newemployeeServiceRate = intval($totalDays) * intval($service_rate);
// echo json_encode(['success' => true, 'message' => 'service rate found for new employee with id ' . $newEmployeeID . '.']);
// exit;

} else {

echo json_encode(['success' => false, 'message' => 'No service rate found for new employee with id ' . $newEmployeeID . '.']);
exit;
}

// Step 1: Query to get the old employee's expense data
$oldEmployeeSql = "SELECT * FROM `expenses` WHERE entity_id = ? AND service_id = ?";  // Use placeholders for security

$oldEmployeestmt2 = $conn->prepare($oldEmployeeSql);
$oldEmployeestmt2->bind_param('ii', $oldEmployeeID, $serviceId);

if (!$oldEmployeestmt2->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to execute query for fetching old employee expenses.']);
    exit;
}

$oldEmployeeResult = $oldEmployeestmt2->get_result()->fetch_assoc(); // Fetch the result

if ($oldEmployeeResult) {
    $oldAmount = intval($oldEmployeeResult['amount']);
    $oldEmployeeAmount = $oldAmount - (intval($totalDays) * intval($oldEmployeeServiceRate));

    // echo json_encode([
    //     'success' => true,
    //     'message' => 'Data found for the specified service_id - ' . $serviceId . ' and entity_id ' . $oldEmployeeID . '.',
    //     'oldEmployeeAmount' => $oldEmployeeAmount
    // ]);
    // exit;
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No data found for the specified service_id - ' . $serviceId . ' and entity_id ' . $oldEmployeeID . '.'
    ]);
    exit;
}


$description = "previously ".  $oldAmount ." - Adjusted due to employee replacement";

      // Prepare the UPDATE statement
$updateOldEmployeeStmt = $conn->prepare("
UPDATE Expenses 
SET amount = ?, status = 'Pending', updated_at = NOW(), 
    description = ?
WHERE entity_id = ? AND status = 'Pending'
");

// Bind the parameters
$updateOldEmployeeStmt->bind_param('dsi', $oldEmployeeAmount, $description, $oldEmployeeID); // Assuming $updatedAmount and $oldEmployeeID are set

    
        
        
        
        if (!$updateOldEmployeeStmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to update old employee expenses.']);
            exit;
        }
        else
        {
            // echo json_encode(['success' => true, 'message' => 'updated old employee expenses.']);
            // exit; 
        }
       
        $updateOldEmployeeStmt->close();


        $updateServiceStmt = $conn->prepare("
        UPDATE service_requests 
        SET assigned_employee = ?, emp_id =?, from_date = ?, end_date = ? 
        WHERE id = ?
    ");
    $updateServiceStmt->bind_param("sissi", $newEmployeeName,$newEmployeeID, $fromDate, $endDate, $serviceId);
    if (!$updateServiceStmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Failed to update service request with new employee.']);
        exit;
    }
    else
        {
            // echo json_encode(['success' => true, 'message' => 'updated service request with new employee.']);
            // exit; 
        }
    
   
    $updateServiceStmt->close();

    $expense_type = "Employee Payout";
    $payment_status = "Pending";
    $status = "Pending";
    $expense_date = date('Y-m-d');
    $description = "Payout for employee replacement from $fromDate to $endDate";
    $additional_details = "";
    
    // Prepare the SQL statement
    $newExpenseStmt = $conn->prepare("
        INSERT INTO Expenses 
        (expense_type, entity_id, service_id, entity_name, status, payment_status, description, amount, date_incurred, additional_details, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    if (!$newExpenseStmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }
    
    // Bind parameters
    $newExpenseStmt->bind_param(
        "siisssisss", 
        $expense_type,         // string
        $newEmployeeID,        // integer
        $serviceId,            // integer
        $newEmployeeName,      // string
        $status,               // string
        $payment_status,       // string
        $description,          // string
        $newemployeeServiceRate, // double
        $expense_date,         // string (Y-m-d)
        $additional_details    // string
    );
    
    // Execute the statement
    if (!$newExpenseStmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Failed to insert new expense for new employee: ' . $newExpenseStmt->error]);
       
        exit;
    }
    else
    {
        // echo json_encode(['success' => true, 'message' => 'Expense successfully added for new employee.']);
        // exit;
    }
    $newExpenseStmt->close();
    

    

        
        echo json_encode(['success' => true, 'message' => 'Employee replacement handled successfully.']);
        exit;
    } catch (Exception $e) {
        

        // Send a failure response with the error message
        echo json_encode(['success' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        exit;
    } finally {
        $conn->close();
    }
    ob_end_flush(); // Flush output buffer
}
?>
