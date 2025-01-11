<?php

require '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    $role = isset($input['role']) ? $input['role'] : null;
    $from_date = isset($input['from_date']) ? $input['from_date'] : null;
    $end_date = isset($input['end_date']) ? $input['end_date'] : null;
    $exclude_employee_id = isset($input['exclude_employee_id']) ? $input['exclude_employee_id'] : null;
   
   
    
    $query = "
    SELECT e.id, e.name 
    FROM emp_info e 
    WHERE e.role = ? 
      AND e.id != ? 
      AND e.id NOT IN (
        SELECT a.emp_id 
        FROM service_requests a
        WHERE 
            a.service_type = e.role AND
            (
                (a.from_date <= ? AND a.end_date >= ?) OR
                (a.from_date <= ? AND a.end_date >= ?) OR
                (a.from_date >= ? AND a.end_date <= ?)
            )
    );";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssssss",
        $role,
        $exclude_employee_id,
        $from_date, $from_date,
        $end_date, $end_date,
        $from_date, $end_date
    );

    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        die("Error executing query: " . $stmt->error);
    }
    
    if ($result->num_rows > 0) {
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
           // echo "Employee found: " . print_r($row, true) . "<br>";
        }
    } else {
      //  echo "No employees found.";
    }
    
  

    echo json_encode($employees);
}  

?>
