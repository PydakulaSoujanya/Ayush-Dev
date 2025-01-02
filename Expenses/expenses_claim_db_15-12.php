<?php
include('../config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_name = $_POST['employee_name']; // Get the employee name
    $expense_category = $_POST['expense_category'];
    $expense_date = $_POST['expense_date'];
    $amount_claimed = $_POST['amount_claimed'];
    $attachment = $_FILES['attachment']['name'];
    $status = $_POST['status'];
    $rejection_reason = $_POST['rejection_reason'] ?? null;
    $submitted_date = $_POST['submitted_date'];
    $approved_date = $_POST['approved_date'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;
    $description = $_POST['description'];
    $transaction_id = $_POST['transaction_id'];
    $payment_mode = $_POST['payment_mode'];
    $card_reference_number = $_POST['card_reference_number'];
    $bank_name = $_POST['bank_name'];


    // Handle file upload
    if (!empty($attachment)) {
        $upload_dir = 'uploads/';
        $target_file = $upload_dir . basename($_FILES['attachment']['name']);
        move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file);
    }

    // Insert into database
    $query = "INSERT INTO expenses_claim 
              (employee_name, expense_category, expense_date, amount_claimed, attachment, status, rejection_reason, submitted_date, approved_date, payment_date, description, transaction_id, payment_mode, card_reference_number, bank_name) 
              VALUES 
              ('$employee_name', '$expense_category', '$expense_date', '$amount_claimed', '$attachment', '$status', '$rejection_reason', '$submitted_date', '$approved_date', '$payment_date', '$description', '$transaction_id', '$payment_mode', '$card_reference_number', '$bank_name')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Expense claim submitted successfully!'); window.location.href='employee_expenditure_table.php';</script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>alert('Error: $error_message'); window.location.href='expenses_claim_form.php';</script>";
    }
}
?>
