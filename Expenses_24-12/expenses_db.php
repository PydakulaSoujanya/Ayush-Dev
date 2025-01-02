<?php
include('../config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get values from form
    $entity_name = $_POST['entity_name'];
    $expense_date = $_POST['expense_date'];
    $amount_claimed = isset($_POST['amount_claimed'])?$_POST['amount_claimed']:$_POST['amount_to_be_paid'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $entity_id=$_POST['entity_id'];
   // $attachment = $_FILES['attachment']['name'] ?? null;
   $expense_type = $_POST['expense_type'];
   $payment_status = isset($_POST['payment_status'])?$_POST['payment_status']:"Pending";
   

    // Prepare the insert statement
    $query = $conn->prepare("INSERT INTO Expenses (expense_type,entity_id, entity_name, description, amount, date_incurred, status, additional_details, created_at, updated_at) VALUES (?, ?, ?,?, ?, ?, ?, ?, NOW(), NOW())");
    
    $additional_details = $attachment; // Use file name as additional details if uploaded
    $query->bind_param("ssssdsss", $expense_type,$entity_id, $entity_name, $description, $amount_claimed, $expense_date, $status, $additional_details);

    // Execute the query
    if ($query->execute()) {
        echo "<script>alert('Expense claim submitted successfully!'); window.location.href='employee_expenditure_table.php';</script>";
    } else {
        $error_message = $query->error;
        echo "<script>alert('Error: $error_message'); window.location.href='expenses_claim_form.php';</script>";
    }
}
?>