<?php
// Connect to the database
include '../config.php';
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
require_once  '../vendor/autoload.php';




    use setasign\fpdf\fpdf;
    use setasign\Fpdi\Fpdi;
    // use setasign\FpdiProtection\FpdiProtection;
    
// Fetch employees from the emp_info table
$empSql = "SELECT id, name FROM emp_info";
$empResult = $conn->query($empSql);

// Store employees in an array
$employees = [];
if ($empResult->num_rows > 0) {
    while ($empRow = $empResult->fetch_assoc()) {
        $employees[] = $empRow;
    }
}
// Pagination Variables
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;
$pageIndex = isset($_GET['pageIndex']) ? intval($_GET['pageIndex']) : 0;
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';


// Calculate the starting row
$start = $pageIndex * $pageSize;

// SQL Query for Paginated and Filtered Results
$sql = "SELECT * FROM service_requests 
        WHERE customer_name LIKE '%$searchTerm%' 
        ORDER BY id DESC 
        LIMIT $start, $pageSize";
$result = $conn->query($sql);

// Get Total Records for Pagination
$countSql = "SELECT COUNT(*) as total FROM service_requests 
             WHERE customer_name LIKE '%$searchTerm%'";
$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $pageSize);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_service'])) {
    
    $serviceId = intval($_POST['service_id']);
    $cancelSql = "UPDATE service_requests SET status = 'Cancelled',assigned_employee = '', emp_id = '' WHERE id = $serviceId";
     
     $stmt = $conn->prepare($cancelSql);
     $stmt->bind_param('i', $row['id']);
     $stmt->execute();
 
    
    if (mysqli_query($conn, $cancelSql)) {
      
         echo "<script>
                        alert('Service cancelled successfully!');
                        window.location.href = 'view_services.php';
                    </script>";
    } else {
        echo "Error: " . mysqli_error($conn);

    }

}

// Check if the employee is already assigned to a service request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_employee'])) {
    $serviceId = $_POST['service_id'];  // This should be the correct column name
    $empId = $_POST['emp_id'];

    // Fetch employee name based on emp_id
    $empNameSql = "SELECT name FROM emp_info WHERE id = '$empId'";
    $empNameResult = $conn->query($empNameSql);

    if ($empNameResult->num_rows > 0) {
        $empRow = $empNameResult->fetch_assoc();
        $empName = $empRow['name'];

        // Check if the employee is already assigned to a service request
        $checkSql = "SELECT * FROM service_requests WHERE assigned_employee = '$empId'";
        $checkResult = $conn->query($checkSql);
        $servcieduraitonSql = "SELECT * FROM service_requests WHERE id = '$serviceId'";
        $servcieduraitonResult = $conn->query($servcieduraitonSql);

        
if ($servcieduraitonResult->num_rows > 0) {
    // Fetch the row data
    $row = $servcieduraitonResult->fetch_assoc();
    
    
    $serviceDuration = 'daily_rate' . $row['service_duration'];  // Concatenate string
    $serviceTotalDays = $row['total_days'];  // Concatenate string
    
   // echo "<script type='text/javascript'>alert('Service Duration with Prefix: " . $serviceDuration . "');</script>";
} else {
  echo "<script type='text/javascript'>alert('Service Duration with Prefix: no rive dutraion');</script>";
}
       
$empServiceRateHourSql = "SELECT daily_rate8, daily_rate12, daily_rate24 FROM emp_info WHERE id = '$empId'";
$empServiceRateHourResult = $conn->query($empServiceRateHourSql);

if ($empServiceRateHourResult->num_rows > 0) {
    // Fetch the row data
    $row = $empServiceRateHourResult->fetch_assoc();
    
    // Check which column matches the serviceDuration
    if ($serviceDuration === 'daily_rate8') {
      $rate = intval($row['daily_rate8']) * intval($serviceTotalDays);

    } elseif ($serviceDuration === 'daily_rate12') {
      $rate = intval($row['daily_rate12']) * intval($serviceTotalDays);

    } elseif ($serviceDuration === 'daily_rate24') {
      $rate = intval($row['daily_rate24']) * intval($serviceTotalDays);

    } else {
        // Handle invalid or unexpected serviceDuration values
        $rate = "Rate not found"; // You can set this to a default value or handle as needed
    }

    

//echo "<script type='text/javascript'>alert('Service Duration with Prefix: " . $rate . "');</script>";
} else {
  echo "<script type='text/javascript'>alert('Service Duration with Prefix: no rive dutraion');</script>";
}
        
        {
            //  Assign the employee name to the service request
            $assignSql = "UPDATE service_requests SET emp_id='$empId',assigned_employee = '$empName' WHERE id = '$serviceId'";
            if ($conn->query($assignSql) === TRUE) {
                // Change the status to "Confirmed"
                $statusSql = "UPDATE service_requests SET status = 'Confirmed' WHERE id = '$serviceId'";
                if ($conn->query($statusSql) === TRUE) {
                    
$maxInvoiceIdQuery = "SELECT MAX(CAST(SUBSTRING(invoice_id, 4) AS UNSIGNED)) AS max_invoice_id FROM invoice";
$result = $conn->query($maxInvoiceIdQuery);
$row = $result->fetch_assoc();
$maxInvoiceId = $row['max_invoice_id'];


$newInvoiceId = 'INV' . str_pad($maxInvoiceId + 1, 7, '0', STR_PAD_LEFT);

$invoiceSql = "
    INSERT INTO invoice (invoice_id, customer_id, service_id, customer_name, mobile_number, customer_email, total_amount, due_date, status, created_at)
    SELECT 
        '$newInvoiceId', 
        '', sr.id, sr.customer_name, sr.contact_no, sr.email, sr.total_price, 
        DATE_ADD(NOW(), INTERVAL 7 DAY), 'Pending', NOW()
    FROM service_requests sr
    WHERE sr.id = '$serviceId'
";


                   
                    
                if ($conn->query($invoiceSql) === TRUE) {
    // Fetch the generated invoice details
    $invoiceDetailsSql = "SELECT * FROM invoice WHERE service_id = '$serviceId'";
    $invoiceDetailsResult = $conn->query($invoiceDetailsSql);
    $invoiceDetails = $invoiceDetailsResult->fetch_assoc();
    
    // Directly get values from the invoice table
    $customer_name = $invoiceDetails['customer_name'];  // Assuming 'customer_name' is in the 'invoice' table
    $mobile_number = $invoiceDetails['mobile_number'];  // Assuming 'mobile_number' is in the 'invoice' table
    $total_amount = $invoiceDetails['total_amount'];    // Assuming 'total_amount' is in the 'invoice' table

    $description="";
    $expense_type = "Employee Payout";
    $payment_status = "Pending";
    $expense_date = date('Y-m-d'); // Current date
    $additional_details = ""; // Use file name as additional details if uploaded
    $payment_status="Pending";
    $status="Pending";

    $expenseStmt = $conn->prepare("
    INSERT INTO Expenses (expense_type, entity_id, service_id,entity_name, status, payment_status, description, amount, date_incurred, additional_details, created_at, updated_at) 
    VALUES (?, ?, ?,?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

    $expenseStmt->bind_param(
      "ssssssdsss", 
      $expense_type, $empId,$serviceId, $empName, $status, $payment_status, $description, $rate, $expense_date, $additional_details
  );
  
    if ($expenseStmt->execute()) {
     // echo "<script>alert('Expense claim submitted successfully!');        </script>";
  } else {
      echo "<script>alert('Error: " . $expenseStmt->error . "'); 
    
    </script>";
  }
// Replace the invoice query with serviceId query
$serviceIdSql = "SELECT * FROM service_requests WHERE id = '$serviceId'";

// Execute the query and fetch the result
$serviceIdResult = mysqli_query($conn, $serviceIdSql);

// Check if results exist
if ($serviceIdResult && mysqli_num_rows($serviceIdResult) > 0) {
    // Fetch the row
    $servicerow = mysqli_fetch_assoc($serviceIdResult);
 
} else {
    echo "No records found.";
}

$customersql = "SELECT * FROM `customer_master` WHERE `id` = ?";
$customerstmt = $conn->prepare($customersql);
$customerstmt->bind_param("i", $servicerow['customer_id']);
$customerstmt->execute();
$result = $customerstmt->get_result();
//$address = $result->fetch_assoc()['address'];
$emergency_contact_number= $result->fetch_assoc()['emergency_contact_number'];
//echo "<script>alert('Emergency Contact Number: " . htmlspecialchars($emergency_contact_number) . "');</script>";

$fromDateFormatted = date('d/m/Y', strtotime($servicerow['from_date']));
$endDateFormatted = date('d/m/Y', strtotime($servicerow['end_date']));


$ttoaday = (strtotime($servicerow['end_date']) - strtotime($servicerow['from_date'])) / (60 * 60 * 24) + 1 ;

$addressSql = "SELECT `pincode`, `address_line1`, `address_line2`, `landmark`, `city`, `state` FROM `customer_addresses` WHERE `customer_id` = ?";
$addressStmt = $conn->prepare($addressSql);

// Bind the customer_id to the query
$addressStmt->bind_param("i", $servicerow['customer_id']);
$addressStmt->execute();

// Fetch the results
$addressResult = $addressStmt->get_result();
$address = $addressResult->fetch_assoc();  // Now the address is fetched properly


if ($address) {
  // Concatenate the address fields
  $address = $address['address_line1'] . ' ' . $address['address_line2'] . ' ' . $address['landmark'] . ' ' . $address['city'] . ' ' . $address['state'] . ' ' . $address['pincode'];
} else {
  // Handle the case where no address data is returned
  $address = "No address available";  // Or any fallback text
}
echo "Full Address: " . $address;

class PDF extends Fpdi
{  
    private $invoiceId; // Add a property to store invoice_id

    function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }
    function Header()
    {
        $logoPath = '../assets/images/ayush_logo.jpg';
        $logoWidth = 40;
        $logoHeight = 30;
        $pageWidth = $this->GetPageWidth();
        $logoX = ($pageWidth - $logoWidth) / 2;
        $logoY = 5;
        $this->Image($logoPath, $logoX, $logoY, $logoWidth, $logoHeight);
        $this->Ln(30);
        $this->SetFont('Arial', '', 8);
        $leftWidth = 120;
        $rightWidth = 70;
        $lineHeight = 5;
        $currentDate = date('d/m/Y');

       // $this->Cell($leftWidth, $lineHeight * 3, "Date: $currentDate", 1, 0);
       $this->Cell($leftWidth, $lineHeight * 3, 'Chikka Nanjunda Reddy Layout', 1, 1); // Place layout and move to next line
       $this->Cell($leftWidth, $lineHeight, 'Address: 123 Main Street, City, Country, Bangalore, Karnataka-560043', 'R', 0);
       $this->Cell($rightWidth, $lineHeight, "Date: $currentDate", 0, 1, 'R'); // Move to next line
       $this->Cell($leftWidth, $lineHeight, 'Phone: +1234567890', 'R', 0);
       $this->Cell($rightWidth, $lineHeight, 'Invoice No:'. $this->invoiceId, 0, 1, 'R'); // Move to next line
       $this->Cell($leftWidth, $lineHeight, 'Email: info@example.com', 'R', 0);
       $this->Cell($rightWidth, $lineHeight, 'GST IN: 29ATAPS5160J1Z', 0, 1, 'R'); // Move to next line
       
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function InvoiceTable($clientInfo, $items)
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 8, 'INVOICE TO:', 1, 1, 'L', true);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 5, "Name: {$clientInfo['name']}\nAddress: {$clientInfo['address']}\nPhone: {$clientInfo['phone']}", 1, 'L');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(192, 192, 192);
        $this->Cell(120, 8, 'DESCRIPTION', 1, 0, 'C', true);
        $this->Cell(20, 8, 'RATE', 1, 0, 'C', true);
        $this->Cell(20, 8, 'DAYS', 1, 0, 'C', true);
        $this->Cell(30, 8, 'AMOUNT', 1, 1, 'C', true);
        $this->Ln(5);
        $this->SetFont('Arial', '', 9);
        $totalAmount = 0;
        foreach ($items as $item) {
            $this->Cell(120, 5, $item['description'], 1);
            $this->Cell(20, 5, $item['rate'], 1, 0, 'C');
            $this->Cell(20, 5, $item['days'], 1, 0, 'C');
            $this->Cell(30, 5, $item['amount'], 1, 1, 'R');
            
        }
        for ($i = 0; $i < 10; $i++) {
            $this->Cell(120, 5, '', 1);
            $this->Cell(20, 5, '', 1, 0, 'C');
            $this->Cell(20, 5, '', 1, 0, 'C');
            $this->Cell(30, 5, '', 1, 1, 'R');
        }
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(120, 8, 'TOTAL', 'T', 0, 'R');
        $this->Cell(20, 8, '', 0, 0, 'C');
        $this->Cell(20, 8, '', 0, 0, 'C');
        $this->Cell(30, 8, $item['amount'], 'T', 1, 'R');
        $this->Ln(1);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(130, 8, 'OTHER COMMENTS', 1, 0, 'L', true);
        $this->SetFont('Arial', '', 9);
        $this->Cell(30, 8, 'IGST', 1, 0, 'C');
        $this->Cell(30, 8, 'Nil', 1, 1, 'C');
        $this->MultiCell(130, 6, "Thank you for giving us an opportunity to serve you.\nIt's a system-generated invoice and doesn't need a signature.\nPlease visit our website - www.aayushhomehealth.com", 1, 'L');
        $this->SetY($this->GetY() - 18);
        $this->SetX(140);
        $this->Cell(30, 8, 'GST', 1, 0, 'C');
        $this->Cell(30, 8, 'Nil', 1, 1, 'C');
        $this->SetX(140);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(60, 10, 'Due: '.$item['amount'], 1, 1, 'C');
    }

    function FullPageOutline()
    {
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0, 0, 0);
        $this->Rect(10, 5, 190, 191);
    }
}



$items = [
    [
        'description' => "01 {$servicerow['service_type']} provided for -- Hrs ($fromDateFormatted - $endDateFormatted)",
        'rate' => $servicerow['per_day_service_price'],
        'days' => $ttoaday,
        'amount' => $servicerow['total_price'],
    ]
];

$clientInfo = [
    'name' => $customer_name,
    'address' => $address,
    'phone' => "+91 ". $emergency_contact_number,
];





$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->setInvoiceId($invoiceDetails['invoice_id']);
$pdf->AddPage();
$pdf->FullPageOutline();
$pdf->InvoiceTable($clientInfo, $items);


$invoicesFolder = 'invoices';

if (!file_exists($invoicesFolder)) {
    mkdir($invoicesFolder, 0777, true);
}

$pdfFileName = $invoicesFolder . '/invoice_' . $invoiceDetails['invoice_id'] . '.pdf';

$pdf->Output('F', $pdfFileName);


$pdf_path_query = "UPDATE `invoice` SET `pdf_invoice_path` = ? WHERE `service_id` = ?";


$pdf_path_stmt = $conn->prepare($pdf_path_query);

$pdf_path_stmt->bind_param("ss", $pdfFileName, $serviceId);


if ($pdf_path_stmt->execute()) {
//     echo "Invoice path updated successfully.";
//     echo "PDF Path: " . $pdfFileName . "<br>";
// echo "Service ID: " . $serviceId . "<br>";
} else {
    echo "Error updating invoice path: " . $pdf_path_stmt->error;
}

// Close the statement
$pdf_path_stmt->close();


                    echo "<script>
                        alert('Employee allocated successfully, service request Confirmed, invoice generated, and PDF created!');
                       window.location.href = 'pdf.php';
                    </script>";
                    }
                     
                    else {
                        echo "<script>
                            alert('Employee allocated and service Confirmed, but failed to generate invoice: " . $conn->error . "');
                            window.location.href = 'view_services.php';
                        </script>";
                    }
                } 
                else {
                    echo "<script>
                        alert('Employee allocated successfully, but failed to update status: " . $conn->error . "');
                        window.location.href = 'view_services.php';
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Error allocating employee: " . $conn->error . "');
                    window.location.href = 'view_services.php';
                </script>";
            }
        }
    } else {
        echo "<script>alert('Employee not found!');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Include Font Awesome -->
    <link rel="stylesheet" href="../assets/css/style.css">

  <title>Services</title>
  <style>
    .dataTable_wrapper {
      padding: 20px;
    }

    .dataTable_search input {
      max-width: 200px;
    }

    .dataTable_headerRow th,
    .dataTable_row td {
      border: 1px solid #dee2e6; /* Add borders for columns */
    }

    .dataTable_headerRow {
      background-color: #f8f9fa;
      font-weight: bold;
    }

    .dataTable_row:hover {
      background-color: #f1f1f1;
    }

    .dataTable_card {
      border: 1px solid #ced4da; /* Add card border */
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dataTable_card .card-header {
      background-color:  #A26D2B;
      color: white;
      font-weight: bold;
    }
    .action-icons i {
      color: black;
      cursor: pointer;
      margin-right: 10px;
    }
  </style>
</head>
<body>
 <?php
  include '../navbar.php';
  ?>   
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  
  <div class="container  mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header"> Capturing Services Table</div>

      <!-- Card Body -->
      <div class="card-body">
        <!-- Search Input -->
        <div class="dataTable_search mb-3 d-flex justify-content-between">
        <form class="d-flex w-75">
    <input type="text" class="form-control" id="globalSearch" placeholder="Search..." oninput="performSearch()">
</form>

    <a href="services.php" class="btn btn-success">+ Capture Service</a>
</div>


        <!-- Table -->
        <div class="table-responsive">
        <table class="table table-striped">
            
    <thead>
        <tr></tr>
            <th class="s_th">S.no</th>
            <th class="customer_info_th">Customer Info</th>
            <th class="details_th">Details Date</th>
            <th class="total_days_th">Total Days & Service </th> 
            <th class="payment_details_th">Payment Details</th>
            <th class="total_price_th">Total Price</th>
            <th>Status</th>
            <th>Invoice ID</th>
            
            <th>Assign Employee</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$sql1 = "SELECT * FROM service_requests ORDER BY created_at DESC";
        $result1 = mysqli_query($conn, $sql1);


if ($result1->num_rows > 0) {
    $serial = $start + 1; // Assuming $start is defined elsewhere
   while ($row = mysqli_fetch_assoc($result1)) {
        $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';


    // Fetch invoice ID for this specific row (service request)
    $serviceId = $row['id'];
    $invoiceQuery = "SELECT invoice_id FROM invoice WHERE service_id = ?";
    $stmt = $conn->prepare($invoiceQuery);
    $stmt->bind_param("i", $serviceId);  // Assuming `id` and `service_id` are integers
    $stmt->execute();
    $invoiceResult = $stmt->get_result();

    // Fetch the invoice ID if it exists
    $invoiceId = null;
    if ($invoiceResult->num_rows > 0) {
        $invoiceRow = $invoiceResult->fetch_assoc();
        $invoiceId = $invoiceRow['invoice_id'];
    }
        echo "<tr>
                <td>{$serial}</td>
                <td>
                  <strong>Name:</strong> " . htmlspecialchars($row['customer_name']) . "<br>
                  <strong>Phone:</strong> " . htmlspecialchars($row['contact_no']) . "
                </td>
                <td>
                  <strong>Start Date:</strong> " . htmlspecialchars($row['from_date']) . "<br>
                  <strong>End Date:</strong> " . htmlspecialchars($row['end_date']) . "
                </td>
                <!-- Merged Total Days and Service Type column -->
                <td>
                  <strong>Total Days:</strong> {$row['total_days']}<br>
                  <strong>Service Type:</strong> {$row['service_type']}
                </td>
               <td>
                  <strong>Status:</strong> Fully paid<br>
                  <strong>Amount Paid:</strong> 2500
                </td>
                <td>{$row['total_price']}</td>
                
                <!-- Status Column with dropdown -->
                <td>
                   
                    {$row['status']}
                </td>
               
<td onclick=\"window.location.href='view_single_invoice.php?invoice_id=" . $invoiceId . "';\" 
    style=\"cursor: pointer; color: blue; text-decoration: underline;\">
   $invoiceId
</td>


                <td>";

                if ($row['status'] === 'Cancelled') {
                    
                        echo "Service Cancelled";
                     

                }               
                
        elseif (!empty($row['assigned_employee'])) {
            // Show the assigned employee's name
            echo "<span class='text-success'>" . htmlspecialchars($row['assigned_employee']) . "</span>";
            ?>
            <button 
                id="reassignEmployee" 
                class="btn btn-warning btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#reassignEmployeePopupModal"
                data-employee-id="<?= htmlspecialchars($row['emp_id'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-employee-name="<?= htmlspecialchars($row['assigned_employee'], ENT_QUOTES, 'UTF-8'); ?>"
                data-role="<?= htmlspecialchars($row['service_type'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-from-date="<?= htmlspecialchars($row['from_date'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-end-date="<?= htmlspecialchars($row['end_date'], ENT_QUOTES, 'UTF-8'); ?>"
                data-service-id="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" 
               data-service-duration="<?= htmlspecialchars('daily_rate' . $row['service_duration'], ENT_QUOTES, 'UTF-8'); ?>"
                >
                
                Reassign Employee
            </button>
            
            <?php
        } 
      else {
    // Query to fetch unassigned employees for the given service duration
   $query = "
    SELECT e.id, e.name 
    FROM emp_info e 
    WHERE e.role = ? -- This ensures only employees with the matching role are considered
      AND e.id NOT IN (
        SELECT a.employee_id 
        FROM allotment a
        WHERE 
            a.service_type = e.role AND -- Ensure the role and service type match
            (
                (a.start_date <= ? AND a.end_date >= ?) OR -- Overlapping period check
                (a.start_date <= ? AND a.end_date >= ?) OR
                (a.start_date >= ? AND a.end_date <= ?)
            )
    );";


        ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
    "sssssss",
    $row['service_type'], // Added this parameter to bind the role
    $row['from_date'], $row['from_date'],
    $row['end_date'], $row['end_date'],
    $row['from_date'], $row['end_date']
);




    $stmt->execute();
    $result = $stmt->get_result();

    // Dropdown for assigning an employee
    echo "<form method='POST' action=''>
            <select name='emp_id' required>
                <option value=''>Select Employee</option>";
    
    // Populate the dropdown with unassigned employees
    while ($employee = $result->fetch_assoc()) {
        echo "<option value='" . $employee['id'] . "'>" . htmlspecialchars($employee['name']) . "</option>";
    }

    echo "    </select>
            <input type='hidden' name='service_id' value='{$row['id']}'>
            <button type='submit' name='assign_employee' style='border: black; cursor: pointer;' title='Allocate'>
                Assign Employee
            </button>
          </form>";
}

       echo "
       
    </td>";
     if ($row['status'] === 'Cancelled') {
                    
                        echo "<td>Service Cancelled</td>";
                } 
    else{

    echo "<td class='action-icons'>
        <form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to cancel this service?\")'>
            <input type='hidden' name='service_id' value='" . $row['id'] . "'>
            <button type='submit' name='cancel_service' class='btn btn-warning btn-sm'>Cancel</button>
        </form>
    </td>";
    }
echo "</tr>";

        $serial++;
    }
} else {
    echo "<tr><td colspan='8'>No data available</td></tr>"; // Adjusted to show all columns
}
?>
    </tbody>
</table>
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewInvoiceModalLabel">Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="invoiceDetails">
                <!-- Invoice details will be dynamically inserted here -->
                <form id="invoiceDetailsForm">
                    <div class="mb-3">
                        <label for="invoice_id" class="form-label">Invoice ID</label>
                        <input type="text" class="form-control" id="invoice_id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="mobile_number" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile_number" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Customer Email</label>
                        <input type="email" class="form-control" id="customer_email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="total_amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="text" class="form-control" id="due_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Created At</label>
                        <input type="text" class="form-control" id="created_at" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="updated_at" class="form-label">Updated At</label>
                        <input type="text" class="form-control" id="updated_at" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add these in your HTML -->


        </div>

        <script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('reassignEmployeePopupModal');

    modal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extract data from the button
        const employeeId = button.getAttribute('data-employee-id');
        const employeeName = button.getAttribute('data-employee-name');
        const role = button.getAttribute('data-role');
        const fromDate = button.getAttribute('data-from-date');
        const endDate = button.getAttribute('data-end-date');
        const serviceId = button.getAttribute('data-service-id');

        const serviceDuration = button.getAttribute('data-service-duration');  // Added this line

// Update modal content for service ID and service duration
modal.querySelector('#modalServiceId').value = serviceId;
modal.querySelector('#modalServiceDuration').value = serviceDuration;  
        modal.querySelector('#modalEmployeeId').value = employeeId;
        modal.querySelector('#modalEmployeeName').textContent = employeeName;
        modal.querySelector('#modalEmployeeRole').value = role;
modal.querySelector('#modalFromDate').value = fromDate;
modal.querySelector('#modalEndDate').value = endDate;

const alertMessage = `
Service ID: ${serviceId}
Employee ID: ${employeeId}
Employee Name: ${employeeName}
Role: ${role}
From Date: ${fromDate}
End Date: ${endDate}
`;


        
        fetch('exclude_assigned_employee.php', {
        //  fetch('role.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                role: role,
                from_date: fromDate,
                end_date: endDate,
                exclude_employee_id: employeeId
            })
        })
        .then(response => response.json())
        .then(data => {
          
            const select = modal.querySelector('#newEmployee');
            select.innerHTML = '<option value="">Select Employee</option>';
            data.forEach(employee => {
                const option = document.createElement('option');
                option.value = employee.id;
                option.textContent = employee.name;
                select.appendChild(option);
            });
        })
        .catch(error => {
            alert("ERROR fetching emp details");
          //  console.error('Error fetching employees:', error);
        });
    });
});

</script>

            
              
                

<div id="reassignEmployeePopupModal" class="modal fade" tabindex="-1" aria-labelledby="reassignEmployeePopupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reassignEmployeePopupModalLabel">Reassign Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reassignEmployeeForm" method="POST" action="reassign_employee_handler.php">
                    <!-- Hidden field to hold the current employee ID -->
                    <input type="text" id="modalEmployeeId" name="employee_id" hidden/>
                    <input type="text" id="modalEmployeeRole" name="role" readonly class="form-control mb-2" hidden/>
<input type="text" id="modalFromDate" name="from_date" readonly class="form-control mb-2" />
<input type="text" id="modalEndDate" name="end_date" readonly class="form-control mb-2" />



                    <!-- Display currently assigned employee -->
                    <p>Currently assigned to: <span id="modalEmployeeName" class="fw-bold"></span></p>

                    <!-- Reason for reassignment -->
                    <div class="form-group mb-3">
                        <label for="reason">Reason for Reassignment</label>
                        <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Enter the reason for changing the employee" >
</textarea>
                    </div>
                    <div class="form-group mb-3">
    <label for="from_date">From Date</label>
    <input type="date" id="from_date" name="from_date" class="form-control"  required>
</div>


<!-- End Date Picker -->
<div class="form-group mb-3">
    <label for="end_date">End Date</label>
    <input type="date" id="end_date" name="end_date" class="form-control" required>
</div>

<script>
  const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; 
document.getElementById('from_date').min = formattedDate;

    
    function convertToDateInputFormat(dateString) {
        const [day, month, year] = dateString.split('-');
        return `${year}-${month}-${day}`; // Rearrange to YYYY-MM-DD
    }

    // Get references to the inputs
    const modalEndDateInput = document.getElementById('modalEndDate');
    const endDateInput = document.getElementById('end_date');

    // Set the max attribute for the date input
    const maxDate = convertToDateInputFormat(modalEndDateInput.value);
    endDateInput.max = maxDate; // Disable future dates beyond the max date
    document.getElementById('from_date').max = maxDate;
</script>

                  
                    <div class="form-group mb-3">
                        <label for="newEmployee">Select New Employee</label>
                        <select id="newEmployee" name="newEmployee" class="form-control" required>
                            <option value="">Select Employee</option>
                            <!-- Dynamically populated by JavaScript -->
                        </select>
                    </div>

                    <!-- Hidden field to hold service ID -->
                    <input type="text" id="modalServiceId" name="service_id" hidden>
                    <input type="text" id="modalServiceDuration" name="service_duration" hidden>

                    <!-- Submit and Cancel buttons -->
                    <div class="form-group text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="assign_employee">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Handle form submission -->
    <script>
        document.getElementById('reassignEmployeeForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const oldEmployeeID = document.getElementById('modalEmployeeId').value;
const serviceFromDate = document.getElementById('modalFromDate').value;
const serviceEndDate = document.getElementById('modalEndDate').value;

   const reason = document.getElementById('reason').value;
    
    const employeeId = document.getElementById('modalEmployeeId').value; // Current assigned employee ID
    const serviceId = document.getElementById('modalServiceId').value; // Service ID

    
const newEmployeeSelect = document.getElementById('newEmployee');

const selectedOption = newEmployeeSelect.options[newEmployeeSelect.selectedIndex];
const newEmployeeId = selectedOption.value; // The ID from the 'value' attribute
const newEmployeeName = selectedOption.text; // The name from the text of the option


// Assuming 'reason', 'serviceId', 'fromDate', and 'endDate' are already defined
const formData = new FormData();
formData.append('oldEmployeeID', oldEmployeeID);
formData.append('serviceFromDate', serviceFromDate);
formData.append('serviceEndDate', serviceEndDate);
formData.append('employee_id', newEmployeeId);  // Use 'newEmployeeId' here
formData.append('new_employee', newEmployeeName);  // Use 'newEmployeeName' here
formData.append('reason', reason);
formData.append('service_id', serviceId);
formData.append('from_date', document.getElementById('from_date').value);  // Add 'from_date' by ID
formData.append('end_date', document.getElementById('end_date').value);  // Add 'end_date' by ID
formData.append('service_duration', document.getElementById('modalServiceDuration').value);  




        fetch('update_reassigned_employee_details.php', {
       // fetch('ure.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
             // alert('Received Data:\n' + JSON.stringify(data, null, 2));
                alert('Employee reassignment successfully completed!');
                const modal = document.getElementById('reassignEmployeePopupModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide(); // Hide modal after successful submission
                window.location.href = 'pdf.php';
            } else {
                alert(data);

            }
        })
        .catch(error => {
           // console.error('Error:', error);
           alert('An error occurred while processing the request: ' + error) 
            //window.location.href = 'view_services.php';
        });
   
});

    </script>
        
<script>
    function fetchInvoiceDetails(invoiceId) {
    // Clear the previous content
    document.getElementById("invoiceDetails").innerHTML = "Loading...";

    // Make an AJAX request to fetch the invoice details
    fetch('get_single_invoice_details.php?invoiceId=' + invoiceId)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received JSON data:', data);
        if (data.success) {
            // Populate the modal with the fetched data
            document.getElementById('invoice_id').value = data.invoice_id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('customer_email').value = data.customer_email;
            document.getElementById('total_amount').value = data.total_amount;
            document.getElementById('due_date').value = data.due_date;
            
             document.getElementById('status').value = data.status;
            document.getElementById('created_at').value = data.created_at;
            document.getElementById('updated_at').value = data.updated_at;

            // Trigger the modal to show
            $('#viewInvoiceModal').modal('show');
        } else {
            document.getElementById("invoiceDetails").innerHTML = "No details found for this invoice.";
        }
    })
    .catch(error => {
        console.error("Error fetching invoice details:", error);
        document.getElementById("invoiceDetails").innerHTML = "Error loading details.";
    });

}

</script>
          <!-- Modal -->
   <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Service Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modalContent">
            <!-- Details will be populated dynamically -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
 <!-- Modal for Viewing Details -->
 <!-- <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Service Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <table class="table">
          <tbody>
         
          <p><strong>Customer Name:</strong> <span id="customer_name"></span></p>
          <p><strong>Contact No:</strong> <span id="contact_no"></span></p>
          <p><strong>Email:</strong> <span id="email"></span></p>
          <p><strong>Enquiry Date:</strong> <span id="enquiry_date"></span></p>
          <p><strong>Enquiry Time:</strong> <span id="enquiry_time"></span></p>
          <p><strong>Service Type:</strong> <span id="service_type"></span></p>
          <p><strong>Enquiry Source:</strong> <span id="enquiry_source"></span></p>
          <p><strong>Priority Level:</strong> <span id="priority_level"></span></p>
          <p><strong>Status:</strong> <span id="status"></span></p>
          <p><strong>Request Details:</strong> <span id="request_details"></span></p>
          <p><strong>Resolution Notes:</strong> <span id="resolution_notes"></span></p>
          <p><strong>Comments:</strong> <span id="comments"></span></p>
          <p><strong>Created At:</strong> <span id="created_at"></span></p>
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> -->

        <!-- Pagination Controls -->
         <!-- Pagination -->
         <div class="d-flex justify-content-between align-items-center">
          <div>
            Showing <?= $start + 1 ?> to <?= min($start + $pageSize, $totalRecords) ?> of <?= $totalRecords ?> records
          </div>
          <div>
            <a href="?pageIndex=<?= max(0, $pageIndex - 1) ?>&pageSize=<?= $pageSize ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="btn btn-primary btn-sm <?= $pageIndex == 0 ? 'disabled' : '' ?>">Previous</a>
            <a href="?pageIndex=<?= min($totalPages - 1, $pageIndex + 1) ?>&pageSize=<?= $pageSize ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="btn btn-primary btn-sm <?= $pageIndex >= $totalPages - 1 ? 'disabled' : '' ?>">Next</a>
          </div>
          <div>
            <select onchange="window.location.href='?pageIndex=0&pageSize=' + this.value + '&search=<?= htmlspecialchars($searchTerm) ?>'" class="form-select form-select-sm">
              <option value="5" <?= $pageSize == 5 ? 'selected' : '' ?>>5</option>
              <option value="10" <?= $pageSize == 10 ? 'selected' : '' ?>>10</option>
              <option value="20" <?= $pageSize == 20 ? 'selected' : '' ?>>20</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
     function viewDetails(data) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = `
        <table class="table table-bordered">
          <tr><th>Customer Name</th><td>${data.customer_name}</td></tr>
          <tr><th>Contact Number</th><td>${data.contact_no}</td></tr>
          <tr><th>Email</th><td>${data.email}</td></tr>
          <tr><th>Enquiry Date</th><td>${data.enquiry_date}</td></tr>
          <tr><th>Enquiry Time</th><td>${data.enquiry_time}</td></tr>
          <tr><th>Service Type</th><td>${data.service_type}</td></tr>
          <tr><th>Enquiry Source</th><td>${data.enquiry_source}</td></tr>
          <tr><th>Priority Level</th><td>${data.priority_level}</td></tr>
           <tr><th>Status</th><td>${data.status}</td></tr>
            <tr><th>Request Details</th><td>${data.request_details}</td></tr>
             <tr><th>Resolution Notes</th><td>${data.resolution_notes}</td></tr>
              <tr><th>Comments</th><td>${data.comments}</td></tr>
          <tr><th>Created At</th><td>${data.created_at}</td></tr>
        </table>
      `;
    }
      
    // Sample Data
    const data = Array.from({ length: 50 }, (_, i) => ({
      id: i + 1,
      name: `Person ${i + 1}`,
      age: Math.floor(Math.random() * 40) + 20,
      city: `City ${Math.floor(Math.random() * 10) + 1}`,
    }));

    // Pagination Variables
    let pageIndex = 0;
    let pageSize = 5;

    // Elements
    const tableBody = document.getElementById("tableBody");
    const pageInfo = document.getElementById("pageInfo");
    const previousPage = document.getElementById("previousPage");
    const nextPage = document.getElementById("nextPage");
    const pageSizeSelect = document.getElementById("pageSize");
    const globalSearch = document.getElementById("globalSearch");

    // Functions to Render Table
    function renderTable() {
      const start = pageIndex * pageSize;
      const filteredData = data.filter((item) =>
        item.name.toLowerCase().includes(globalSearch.value.toLowerCase())
      );
      const pageData = filteredData.slice(start, start + pageSize);

      tableBody.innerHTML = pageData
        .map(
          (row) =>
            `<tr class="dataTable_row">
              <td>${row.id}</td>
              <td>${row.name}</td>
              <td>${row.age}</td>
              <td>${row.city}</td>
            </tr>`
        )
        .join("");

      pageInfo.textContent = `${pageIndex + 1} of ${Math.ceil(filteredData.length / pageSize)}`;
      previousPage.disabled = pageIndex === 0;
      nextPage.disabled = pageIndex >= Math.ceil(filteredData.length / pageSize) - 1;
    }

    // Event Listeners
    previousPage.addEventListener("click", () => {
      if (pageIndex > 0) {
        pageIndex--;
        renderTable();
      }
    });

    nextPage.addEventListener("click", () => {
      pageIndex++;
      renderTable();
    });

    pageSizeSelect.addEventListener("change", (e) => {
      pageSize = Number(e.target.value);
      pageIndex = 0;
      renderTable();
    });

    globalSearch.addEventListener("input", () => {
      pageIndex = 0;
      renderTable();
    });

    // Initial Render
    renderTable();
    
  </script>
  <script>
  function performSearch() {
    const searchTerm = document.getElementById('globalSearch').value;

    // Send AJAX request
    fetch(`view_services.php?search=${encodeURIComponent(searchTerm)}`)
      .then(response => response.text())
      .then(data => {
        // Update the table with the fetched data
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const newTableBody = doc.querySelector('tbody');

        if (newTableBody) {
          document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
        }
      })
      .catch(error => console.error('Error fetching data:', error));
  }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
  $('#employeeTable').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    language: { search: "Search Employees:" }
  });
});

</script>

</body>
</html>
