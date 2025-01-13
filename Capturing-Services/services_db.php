<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

include '../config.php'; // Include your database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch customer-related data
    $customer_name = $_POST['customer_name'] ?? null;
    $contact_no = $_POST['emergency_contact_number'] ?? null;
    $patient_name = $_POST['patient_name'] ?? null;
    $relationship = $_POST['relationship'] ?? null;
    $enquiry_date = $_POST['enquiry_date'] ?? null;
    $enquiry_time = $_POST['enquiry_time'] ?? null;
    $enquiry_source = $_POST['enquiry_source'] ?? null;
    $priority_level = $_POST['priority_level'] ?? null;
    $status = $_POST['status'] ?? null;
    $request_details = $_POST['request_details'] ?? null;
    $resolution_notes = $_POST['resolution_notes'] ?? null;
    $comments = $_POST['comments'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;
    $email = $_POST['email'] ?? null; // Fetch email from the form
    $service_duration=$_POST['service_duration']??[];

    // Validate required fields
    if (!$customer_name || !$contact_no || !$enquiry_date || !$email) {
        die("Please fill in all required fields.");
    }
    if (!DateTime::createFromFormat('Y-m-d', $enquiry_date)) {
        die("Invalid enquiry date. Please provide a valid date in YYYY-MM-DD format.");
    }
    $enquiry_date = DateTime::createFromFormat('Y-m-d', $enquiry_date)->format('d-m-Y'); // For display
    $enquiry_date_db = $enquiry_date_raw; // Keep raw date (Y-m-d) for DB storage
    // Fetch service-related data
    $service_types = $_POST['service_type'] ?? [];
    $per_day_service_prices = $_POST['per_day_service_price'] ?? [];
    $from_dates = $_POST['from_date'] ?? [];
    $end_dates = $_POST['end_date'] ?? [];
    $total_days_list = $_POST['total_days'] ?? [];
    $service_prices = $_POST['service_price'] ?? [];
    $discount_prices = $_POST['discount_price'] ?? [];
    $total_service_prices = $_POST['total_service_price'] ?? [];

    $errors = [];
    foreach ($service_types as $index => $service_type) {
        // Validate data for each service
        if (!isset($service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], $total_days_list[$index], $service_prices[$index])) {
            $errors[] = "Incomplete data for service at index $index.";
            continue;
        }

        $discount_price = isset($discount_prices[$index]) && !empty($discount_prices[$index]) 
            ? $discount_prices[$index] 
            : 0;

        $total_price = $service_prices[$index] - $discount_price;

        // Insert into `service_requests` table
        $sql = "INSERT INTO service_requests (
            service_duration, customer_name, contact_no, patient_name, customer_id, relationship, enquiry_date, enquiry_time, 
            service_type, per_day_service_price, from_date, end_date, total_days, service_price, 
            discount_price, total_service_price, total_price, enquiry_source, priority_level, status, 
            request_details, resolution_notes, comments
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL preparation failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssssssssssss",
            $service_duration[$index], $customer_name, $contact_no, $patient_name, $customer_id, $relationship, $enquiry_date, $enquiry_time,
            $service_type, $per_day_service_prices[$index], $from_dates[$index], $end_dates[$index], 
            $total_days_list[$index], $service_prices[$index], 
            $discount_price, $total_service_prices[$index], $total_price, $enquiry_source, 
            $priority_level, $status, $request_details, $resolution_notes, $comments
        );

        if (!$stmt->execute()) {
            $errors[] = "Error inserting service at index $index: " . $stmt->error;
        }

        $stmt->close();
    }

    // Send email to the customer
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'uppalahemanth4@gmail.com'; // Your email
        $mail->Password = 'oimoftsgtwradkux'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@yourwebsite.com', 'Ayush Home Health Care');
        $mail->addAddress($email, $customer_name); // Send to the customer email fetched from form
// Generate service details table
$service_details_table = "";
foreach ($service_types as $index => $service_type) {
    $from_date = $from_dates[$index];
    $end_date = $end_dates[$index];
    $total_days = $total_days_list[$index];
    $total_price = $total_service_prices[$index] ?? 0; // Ensure total price is fetched

    $service_details_table .= "
        <tr>
            <td>{$service_type}</td>
            <td>{$from_date}</td>
            <td>{$end_date}</td>
            <td>{$total_days}</td>
            <td>{$total_price}</td>
        </tr>
    ";
}

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Service Request Confirmation';
        $mail->Body = "
        <h1>Service Request Submitted Successfully</h1>
        <p>Dear {$customer_name},</p>
        <p>Thank you for choosing Ayush Home Health Care. Below are your service request details:</p>
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
            <tr><th>Customer Name</th><td>{$customer_name}</td></tr>
            <tr><th>Contact Number</th><td>{$contact_no}</td></tr>
            <tr><th>Patient Name</th><td>{$patient_name}</td></tr>
            <tr><th>Relationship</th><td>{$relationship}</td></tr>
            <tr><th>Enquiry Date</th><td>{$enquiry_date}</td></tr>
            <tr><th>Status</th><td>{$status}</td></tr>
            <tr><th>Priority Level</th><td>{$priority_level}</td></tr>
        </table>
        <h2>Service Details</h2>
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
            <tr>
                <th>Service Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Days</th>
                <th>Total Price</th>
            </tr>
            {$service_details_table}
        </table>
        <p>If you have any questions, feel free to contact us.</p>
        <p>Best regards,</p>
        <p>Ayush Home Health Care</p>
    ";
        $mail->send();
        echo "<script>alert('Service request saved and email sent successfully!'); window.location.href='view_services.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}'); window.location.href='view_services.php';</script>";
    }
}

// Close the database connection
$conn->close();
?>
