<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "transaction";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$customer = $_POST['customer'];
$invoice = $_POST['invoice'];
$receipt = $_POST['receipt'];
$amount = $_POST['amount'];

// Insert into database
$sql = "INSERT INTO matched (customer, invoice, receipt, amount) VALUES ('$customer', '$invoice', '$receipt', '$amount')";
if ($conn->query($sql) === TRUE) {
    echo "Record added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
