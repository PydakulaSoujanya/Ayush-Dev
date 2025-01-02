<?php

// Include database configuration
include('../config.php');

// Include PhpSpreadsheet autoload
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Check if the download button was clicked
if (isset($_POST['download'])) {
    // Fetch Expense Claims data from the database
    $query = "SELECT entity_name, beneficiary_account_number, beneficiary_ifsc, 
                     amount, date_incurred 
              FROM expenses";
    $result = mysqli_query($conn, $query);

    // Check if data exists
    if (mysqli_num_rows($result) > 0) {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define the header row in the required order
        $headers = [
            'PYMT_PROD_TYPE_CODE', 'PYMT_MODE', 'DEBIT_ACC_NO', 'BNF_NAME',
            'BENE_ACC_NO', 'BENE_IFSC', 'AMOUNT', 'DEBIT_NARR',
            'CREDIT_NARR', 'MOBILE_NUM', 'EMAIL_ID', 'REMARK',
            'PYMT_DATE', 'REF_NO'
        ];

        // Add headers to the spreadsheet
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Add data to the spreadsheet
        $row = 2;
        while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, 'PAB_VENDOR'); // Static value
    $sheet->setCellValue('B' . $row, 'NEFT'); // Static value
    $sheet->setCellValue('C' . $row, '001234567891'); // Static value
    $sheet->setCellValue('D' . $row, $data['entity_name']); // Dynamic value from DB
    $sheet->setCellValue('E' . $row, '123456789012'); // Static value for beneficiary_account_number
    $sheet->setCellValue('F' . $row, 'IFSC0000123'); // Static value for beneficiary_ifsc
    $sheet->setCellValue('G' . $row, $data['amount']); // Dynamic value from DB
    $sheet->setCellValue('H' . $row, 'December Salary'); // Static value
    $sheet->setCellValue('I' . $row, ''); // Static value (Empty)
    $sheet->setCellValue('J' . $row, '9999999999'); // Static value
    $sheet->setCellValue('K' . $row, 'example@example.com'); // Static value
    $sheet->setCellValue('L' . $row, 'Salary'); // Static value
    $sheet->setCellValue('M' . $row, $data['date_incurred']); // Dynamic value from DB
    $sheet->setCellValue('N' . $row, 'REF123456789'); // Static value
    $row++;
}


        // Create a writer instance
        $writer = new Xlsx($spreadsheet);

        // Set the file name for the download
        $filename = 'expense_claims.xlsx';

        // Set headers to force the browser to download the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Save the file to the browser
        $writer->save('php://output');
        exit;
    } else {
        echo "No expense claims data found.";
        exit;
    }
}

?>