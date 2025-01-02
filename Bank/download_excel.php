<?php

// Include database configuration
include('../config.php');

// Include PhpSpreadsheet autoload
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Check if the download button was clicked
if (isset($_POST['download'])) {
    // Fetch data from expenses and emp_info tables using JOIN
    $query = "
        SELECT 
            e.`entity_name`,
            e.`amount`,
            e.`date_incurred`,
            emp.`phone`,
            emp.`email`,
            emp.`bank_account_no`,
            emp.`ifsc_code`
        FROM 
            `expenses` e
        INNER JOIN 
            `emp_info` emp
        ON 
            e.`entity_id` = emp.`id`
    ";
    $result = mysqli_query($conn, $query);

    // Check if data exists
    if (!$result) {
        echo "Error executing query: " . mysqli_error($conn);
        exit;
    }

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
            $sheet->setCellValue('D' . $row, htmlspecialchars($data['entity_name'])); // Escaped dynamic value

            // Format Bank Account Number and IFSC Code as Text
            $sheet->setCellValueExplicit('E' . $row, $data['bank_account_no'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $row, $data['ifsc_code'], DataType::TYPE_STRING);

            $sheet->setCellValue('G' . $row, $data['amount']); // Dynamic value
            $sheet->setCellValue('H' . $row, 'December Salary'); // Static value
            $sheet->setCellValue('I' . $row, ''); // Static value (Empty)
            $sheet->setCellValue('J' . $row, htmlspecialchars($data['phone'])); // Escaped dynamic value
            $sheet->setCellValue('K' . $row, htmlspecialchars($data['email'])); // Escaped dynamic value
            $sheet->setCellValue('L' . $row, 'Salary'); // Static value
            $sheet->setCellValue('M' . $row, date('d/m/Y', strtotime($data['date_incurred']))); // Dynamic date in DD/MM/YYYY format
            $sheet->setCellValue('N' . $row, 'REF123456789'); // Static value
            $row++;
        }

        // Auto-adjust column width for all headers and content
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
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
