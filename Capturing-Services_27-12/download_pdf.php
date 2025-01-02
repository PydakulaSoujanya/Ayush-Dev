<?php
// Make sure the user is requesting a valid invoice_id
if (isset($_GET['invoice_id']) || isset($_GET['id'])) {
    // Get the invoice_id or id from the URL
    $invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : $_GET['id'];

    // Establish your database connection
    include '../config.php'; // Assuming you have this for your DB connection

    // Query to get the PDF path based on either 'invoice_id' or 'id'
    $query = "SELECT pdf_invoice_path FROM invoice WHERE invoice_id = ? OR id = ?";

    // Prepare the query
    $stmt = $conn->prepare($query);
    
    // Bind the parameters
    $stmt->bind_param("ss", $invoice_id, $invoice_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pdf_invoice_path);
//     if ($stmt->fetch()) {
//     echo "PDF Invoice Path: " . $pdf_invoice_path;
// } else {
//     echo "No results found.";
// }
    // Check if the invoice exists 
    if ($stmt->num_rows >0) {
        $stmt->fetch(); // Get the pdf path

        // Check if the PDF file exists on the server
        if (file_exists($pdf_invoice_path)) {
            echo "yes";
            // Set headers to force the download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdf_invoice_path) . '"');
            header('Content-Length: ' . filesize($pdf_invoice_path));
            
            // Read and output the file content
            readfile($pdf_invoice_path);
            exit;
        } else {
            echo "The requested PDF file does not exist.";
        }
    } else {
        echo "Invoice not found.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
