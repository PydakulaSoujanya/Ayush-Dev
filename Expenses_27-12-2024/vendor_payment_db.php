<?php
include('../config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billId = mysqli_real_escape_string($conn, $_POST['bill_id']);
    $vendorId = mysqli_real_escape_string($conn, $_POST['vendor_id']); // Capture vendor_id
    $invoiceAmount = mysqli_real_escape_string($conn, $_POST['invoice_amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Fetch vendor_name based on vendor_id
    $vendorNameQuery = "SELECT vendor_name FROM vendors WHERE id = '$vendorId'";
    $vendorNameResult = mysqli_query($conn, $vendorNameQuery);
    $vendorNameRow = mysqli_fetch_assoc($vendorNameResult);
    $vendorName = $vendorNameRow['vendor_name'];

    // Handle file upload
    $uploadDir = 'vendor_bills/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $billFile = $_FILES['bill_file'];
    $fileName = time() . '_' . basename($billFile['name']); // Prevent file name conflicts
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($billFile['tmp_name'], $targetFilePath)) {
        // Generate the next purchase_invoice_number
        $query = "SELECT MAX(purchase_invoice_number) AS last_invoice FROM vendor_payments_new";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row['last_invoice']) {
            $lastNumber = intval(substr($row['last_invoice'], 2));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        $purchaseInvoiceNumber = 'PI' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Insert into database with both vendor_id and vendor_name
        $query = "INSERT INTO vendor_payments_new (purchase_invoice_number, bill_id, vendor_id, vendor_name, invoice_amount, description, bill_file_path, created_at) 
                  VALUES ('$purchaseInvoiceNumber', '$billId', '$vendorId', '$vendorName', '$invoiceAmount', '$description', '$targetFilePath', NOW())";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Vendor payment record submitted successfully!'); window.location.href='vendor_expenditure_table.php';</script>";
        } else {
            $error_message = mysqli_error($conn);
            echo "<script>alert('Error: $error_message'); window.location.href='vendor_billing_and_payouts.php';</script>";
        }
    } else {
        echo "<script>alert('Error: Failed to upload the bill file.'); window.location.href='vendor_billing_and_payouts.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='vendor_billing_and_payouts.php';</script>";
}
?>
