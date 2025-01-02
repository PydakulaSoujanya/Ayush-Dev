<?php


// Database connection
include('../config.php');

// Check if the id is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No ID specified for deletion.";
    header("Location: manage_accountconfig.php");
    exit;
}

$id = (int)$_GET['id'];
$query = "DELETE FROM account_config WHERE id=$id";

if ($conn->query($query) === TRUE) {
    $_SESSION['message'] = "Record deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting record: " . $conn->error;
}

header("Location: manage_accountconfig.php");
exit;
?>
