<?php


// Database connection
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_name = $_POST['account_name'] ?? '';
    $bank_account_no = $_POST['bank_account_no'] ?? '';
    $ifsc_code = $_POST['ifsc_code'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $account_type = $_POST['account_type'] ?? '';
    $status = $_POST['status'] ?? '';

    // Sanitize and validate inputs
    $account_name = $conn->real_escape_string($account_name);
    $bank_account_no = $conn->real_escape_string($bank_account_no);
    $ifsc_code = $conn->real_escape_string($ifsc_code);
    $bank_name = $conn->real_escape_string($bank_name);
    $account_type = $conn->real_escape_string($account_type);
    $status = $conn->real_escape_string($status);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing record
        $id = (int)$_POST['id'];
        $query = "UPDATE account_config SET account_name='$account_name', bank_account_no='$bank_account_no', ifsc_code='$ifsc_code', bank_name='$bank_name', account_type='$account_type', status='$status' WHERE id=$id";
    } else {
        // Insert new record
        $query = "INSERT INTO account_config (account_name, bank_account_no, ifsc_code, bank_name, account_type, status) VALUES ('$account_name', '$bank_account_no', '$ifsc_code', '$bank_name', '$account_type', '$status')";
    }

    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Record saved successfully.";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    header("Location: manage_accountconfig.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM account_config WHERE id=$id";

    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Record deleted successfully.";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    header("Location: manage_accountconfig.php");
    exit;
}
?>
