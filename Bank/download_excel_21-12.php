<?php

// Include PhpSpreadsheet autoload (make sure to adjust the path if you installed it manually)
require '../vendor/autoload.php'; // For Composer users

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Sample data to export (you can replace it with dynamic data from your database)
$vendorData = [
    ['S.no', 'Vendor Name', 'Phone Number', 'Email', 'Vendor Type'],
    [1, 'Savitha', '9523352352', 'savitha@gmail.com', 'Supplier'],
    [2, 'Venu', '987654321', 'venu@gmail.com', 'Supplier'],
    [3, 'Krishna', '8665582203', 'krishna@gmail.com', 'Supplier'],
    // Add more vendor details here
];

// Check if the download button was clicked
if (isset($_POST['download'])) {
    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Populate the spreadsheet with the vendor data
    $row = 1;
    foreach ($vendorData as $index => $transaction) {
        $col = 'A';
        foreach ($transaction as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }

    // Create a writer instance
    $writer = new Xlsx($spreadsheet);

    // Set the file name for the download
    $filename = 'vendor_data.xlsx';

    // Set headers to force the browser to download the file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Save the file to the browser
    $writer->save('php://output');
    exit;
}
?>
