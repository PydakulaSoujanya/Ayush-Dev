<?php
session_start(); // Start session

// Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     // User is not logged in, redirect to login page
//     header("Location: login.php");
//     exit;
// }

// Your protected page content goes here
?>
<?php
include("../config.php");
$id = $_GET['id'];
$sql = "SELECT * FROM sp_vendors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
$stmt->close();
$conn->close();
?>
