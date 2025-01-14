<?php
// Include PHPExcel or PhpSpreadsheet library
require '../vendor/autoload.php'; // Use PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    // Database connection
    $servername = "localhost";
    $username = "root"; // Default XAMPP username
    $password = ""; // Default XAMPP password
    $dbname = "ayush_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle file upload
    $uploadedFile = $_FILES['excel_file']['tmp_name'];
    if (is_uploaded_file($uploadedFile)) {
        $spreadsheet = IOFactory::load($uploadedFile);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($sheetData as $index => $row) {
            if ($index == 1) continue; // Skip header row

            $tran_id = $row['B'];
            $value_date = DateTime::createFromFormat('d/M/Y', $row['C']);
            $transaction_date = DateTime::createFromFormat('d/M/Y', $row['D']);
            $transaction_posted_date = DateTime::createFromFormat('d/m/Y h:i:s A', $row['E']);

            $value_date = $value_date ? $value_date->format('Y-m-d') : null; // Convert to MySQL date
            $transaction_date = $transaction_date ? $transaction_date->format('Y-m-d') : null; // Convert to MySQL date
            $transaction_posted_date = $transaction_posted_date ? $transaction_posted_date->format('Y-m-d H:i:s') : null; // Convert to MySQL datetime
            $cheque_no_ref_no = $row['F'];
            $transaction_remarks = $row['G'];
            $withdrawal_amt = !empty($row['H']) ? floatval(str_replace(',', '', $row['H'])) : null;
            $deposit_amt = !empty($row['I']) ? floatval(str_replace(',', '', $row['I'])) : null;
            $balance = floatval(str_replace(',', '', $row['J']));




//start
  // Check if tran_id already exists in withdrawals or deposits table
  $checkQuery = "SELECT COUNT(*) as count FROM withdrawals WHERE tran_id = ? UNION ALL SELECT COUNT(*) FROM deposits WHERE tran_id = ?";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param("ss", $tran_id, $tran_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $exists = 0;
  while ($row = $result->fetch_assoc()) {
      $exists += $row['count'];
  }

  if ($exists > 0) {
      // Skip if tran_id already exists
      continue;
  }


  //end
            if ($withdrawal_amt !== null) {
                // Insert into withdrawals table
                $stmt = $conn->prepare("INSERT INTO withdrawals (tran_id, value_date, transaction_date, transaction_posted_date, cheque_no_ref_no, transaction_remarks, withdrawal_amt, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssdd", $tran_id, $value_date, $transaction_date, $transaction_posted_date, $cheque_no_ref_no, $transaction_remarks, $withdrawal_amt, $balance);
                $stmt->execute();
            
                // Update status in withdrawals table
                $query = "UPDATE withdrawals w
                          INNER JOIN vouchers_new v ON w.transaction_date = v.voucher_date AND w.withdrawal_amt = v.paid_amount
                          SET w.status = 'Matched'
                          WHERE w.tran_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $tran_id);
                $stmt->execute();
            
                // Update cash_status in vouchers_new table
                $query = "UPDATE vouchers_new
                          SET cash_status = 'Matched'
                          WHERE voucher_date = ? AND paid_amount = ? AND cash_status = 'Pending'
                          LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sd", $transaction_date, $withdrawal_amt);
                $stmt->execute();
            } elseif ($deposit_amt !== null) {
                // Insert into deposits table
                $stmt = $conn->prepare("INSERT INTO deposits (tran_id, value_date, transaction_date, transaction_posted_date, cheque_no_ref_no, transaction_remarks, deposit_amt, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssdd", $tran_id, $value_date, $transaction_date, $transaction_posted_date, $cheque_no_ref_no, $transaction_remarks, $deposit_amt, $balance);
                $stmt->execute();
            
                // Update status in deposits table
                $query = "UPDATE deposits d
                          INNER JOIN invoice i ON d.transaction_date = i.receipt_date AND d.deposit_amt = i.paid_amount
                          SET d.status = 'Matched'
                          WHERE d.tran_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $tran_id);
                $stmt->execute();
            
                // Update cash_status in invoice table
                $query = "UPDATE invoice
                          SET cash_status = 'Matched'
                          WHERE receipt_date = ? AND paid_amount = ? AND cash_status = 'Pending'
                          LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sd", $transaction_date, $deposit_amt);
                $stmt->execute();
            }
            
        }

        $conn->close();
        echo "Data uploaded and processed successfully!";
    } else {
        echo "File upload failed!";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Upload Excel File</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="excel_file">Choose Excel File:</label>
        <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required>
        <button type="submit">Upload and Process</button>
    </form>
    <div id="response"></div>

    <script>
        $(document).ready(function () {
            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#response').html('<p>' + response + '</p>');
                    },
                    error: function () {
                        $('#response').html('<p>There was an error uploading the file.</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>
