<?php
// Connect to the database
include '../config.php';
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
require_once  '../vendor/autoload.php';
//require_once __DIR__ . '../vendor/autoload.php';



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
    // Sanitize the input
    $serviceId = intval($_POST['service_id']); // Converts to an integer for safety

    // Prepare the SQL query to update the status
    $cancelSql = "UPDATE service_requests SET status = 'Cancelled' WHERE id = $serviceId";

    // Execute the query (assuming $conn is your database connection)
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
        $checkSql = "SELECT * FROM service_requests WHERE assigned_employee = '$empName'";
        $checkResult = $conn->query($checkSql);

        // if ($checkResult->num_rows > 0) {
            // If employee is already assigned to a different service request
            // echo "<script>alert('This employee is already assigned to another service!!');
            // window.location.href = 'view_services.php';
            // </script>";
        // } else
        
        {
            // Assign the employee name to the service request
            $assignSql = "UPDATE service_requests SET emp_id='$empId',assigned_employee = '$empName' WHERE id = '$serviceId'";
            if ($conn->query($assignSql) === TRUE) {
                // Change the status to "Confirmed"
                $statusSql = "UPDATE service_requests SET status = 'Confirmed' WHERE id = '$serviceId'";
                if ($conn->query($statusSql) === TRUE) {
                    // Generate invoice after successfully assigning the employee
                    $invoiceSql = "
                        INSERT INTO invoice (invoice_id, customer_id, service_id, customer_name, mobile_number, customer_email, total_amount, due_date, status, created_at)
                        SELECT 
                            CONCAT('INV', LPAD(FLOOR(RAND() * 100000), 6, '0')), 
                            '', sr.id, sr.customer_name, sr.contact_no, sr.email, sr.service_price, 
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

    // Create PDF
    $pdf = new FPDI();
    $pdf->SetTitle("Invoice #" . $invoiceDetails['invoice_id']);
    
    // Add a new page
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    
    // Add the logo
    $pdf->Image('../assets/images/logo.jpg', 10, 10, 30); // Adjust the path to your logo image
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'Aayush Home Health Solutions', 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(190, 5, 'Caring with compassion', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Add header details
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(95, 5, "Address: #27, 9th Street,", 0, 0);
   // Get the current date in the format 'd/m/Y'
$currentDate = date('d/m/Y');

// Correct invoice ID referencing
$pdf->Cell(95, 5, "Date: $currentDate", 0, 1, 'R');
$pdf->Cell(95, 5, "Chikka Nanjunda Reddy Layout,", 0, 0);

// Ensure that $invoiceDetails['invoice_id'] is correctly referenced
$pdf->Cell(95, 5, "Invoice No.: " . $invoiceDetails['invoice_id'], 0, 1, 'R');

    $pdf->Cell(95, 5, "Bank Avenue Colony, Horamavu Post,", 0, 0);
    $pdf->Cell(95, 5, "GST IN: 29ATAPS5160J1ZC", 0, 1, 'R');
    $pdf->Cell(95, 5, "Bengaluru, Karnataka - 560 043", 0, 1);
    $pdf->Cell(95, 5, "Phone: +91 7013050751", 0, 1);
    $pdf->Cell(95, 5, "E-mail: santosh@aayushhomehealth.com", 0, 1);
    $pdf->Ln(10);
    
    // Invoice details section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'INVOICE TO:', 0, 1);
    
    $customersql = "SELECT `address` FROM `customer_master` WHERE `id` = ?";
$customerstmt = $conn->prepare($customersql);
$customerstmt->bind_param("i", $servicerow['customer_id']);
$customerstmt->execute();
$result = $customerstmt->get_result();
$address = $result->fetch_assoc()['address'];

    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(95, 5, "Name: $customer_name", 0, 1);
    $pdf->Cell(95, 5, "Address: $address,", 0, 1); // Add customer address if available
    $pdf->Cell(95, 5, "Phone: +91 " .$servicerow['$contact_no'], 0, 1);
    $pdf->Ln(10);
    
    // Add table header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(230, 230, 230); // Light gray background
    $pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
    $pdf->Cell(100, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Rate', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Days', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Amount', 1, 1, 'C', true);

  
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(10, 10, '01', 1, 0, 'C');
  
// Format the from_date and end_date to dd/mm/yyyy
$fromDateFormatted = date('d/m/Y', strtotime($servicerow['from_date']));
$endDateFormatted = date('d/m/Y', strtotime($servicerow['end_date']));

// Add service details with the correctly formatted dates
$pdf->Cell(100, 10, $servicerow['service_type'] . " provided for -- Hrs  (" . $fromDateFormatted . " - " . $endDateFormatted . ")", 1, 0);



$ttoaday = (strtotime($servicerow['end_date']) - strtotime($servicerow['from_date'])) / (60 * 60 * 24); // Difference in days




    $pdf->Cell(25, 10, $servicerow['per_day_service_price'], 1, 0, 'C');
   $pdf->Cell(25, 10, $servicerow['total_days'], 1, 0, 'C');
    $pdf->Cell(30, 10, $total_amount, 1, 1, 'C');

echo "<script>
    alert('Customer address is $address and total days are " . $servicerow['total_days'] . "');
</script>";

    // Add total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(160, 10, 'TOTAL =', 1, 0, 'R', true);
    $pdf->Cell(30, 10, $total_amount, 1, 1, 'C', true);

    // Add comments
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(190, 5, "OTHER COMMENTS", 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(190, 5, "Thank you for giving us an opportunity to serve you.\nIt's a system generated invoice and doesn't require a signature.\nPlease visit our website - www.aayushhomehealth.com", 0, 'C');

    $pdf->Ln(10);

    // Tax summary
    $pdf->Cell(160, 5, 'IGST', 1, 0, 'R');
    $pdf->Cell(30, 5, 'Nil', 1, 1, 'C');
    $pdf->Cell(160, 5, 'CGST', 1, 0, 'R');
    $pdf->Cell(30, 5, 'Nil', 1, 1, 'C');

    // Final due amount
    $pdf->Cell(160, 10, 'DUE =', 1, 0, 'R', true);
    $pdf->Cell(30, 10, "$total_amount", 1, 1, 'C', true);

// $pdf->Output('F', $pdfFileName);  // Save the PDF to the "invoices" folder
$invoicesFolder = 'invoices';


if (!file_exists($invoicesFolder)) {
    mkdir($invoicesFolder, 0777, true);  // Create the invoices folder if it doesn't exist
}


$pdfFileName = $invoicesFolder . '/invoice_' . $invoiceDetails['invoice_id'] . '.pdf';


$pdf->Output('F', $pdfFileName);  // Save the PDF to the "invoices" folder

$pdf_path_query = "UPDATE `invoice` SET `pdf_invoice_path` = ? WHERE `service_id` = ?";


$pdf_path_stmt = $conn->prepare($pdf_path_query);

$pdf_path_stmt->bind_param("ss", $pdfFileName, $serviceId);


if ($pdf_path_stmt->execute()) {
    echo "Invoice path updated successfully.";
    echo "PDF Path: " . $pdfFileName . "<br>";
echo "Service ID: " . $serviceId . "<br>";
} else {
    echo "Error updating invoice path: " . $pdf_path_stmt->error;
}

// Close the statement
$pdf_path_stmt->close();


                    echo "<script>
                        alert('Employee allocated successfully, service request Confirmed, invoice generated, and PDF created!');
                       
                    </script>";
                    }
                     //window.location.href = 'view_services.php';
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
  <!-- <style>
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
  </style> -->
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
        <tr class="dataTable_headerRow">
            <th>S.no</th>
            <th>Customer Info</th>
            <th>Details</th>
            <th>Total Days & Service Type</th> 
            <th>Payment Details</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Invoice ID</th>
            <th>Action</th>
            <th>Assign Employee</th>
        </tr>
    </thead>
    <tbody>
<?php
$sql1 = "SELECT * FROM service_requests 
        ";
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
        echo "<tr class='dataTable_row'>
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
                <td>{$row['service_price']}</td>
                
                <!-- Status Column with dropdown -->
                <td>
                    {$row['status']}
                </td>
               
<td onclick=\"window.location.href='view_single_invoice.php?invoice_id=" . $invoiceId . "';\" 
    style=\"cursor: pointer; color: blue; text-decoration: underline;\">
   $invoiceId
</td>


                <td>";

        // Check if the employee is assigned
        if (!empty($row['assigned_employee'])) {
            // Show the assigned employee's name
            echo "<span class='text-success'>" . htmlspecialchars($row['assigned_employee']) . "</span>";
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

// <form method='POST' action='update_servicestatus.php'>
//                         <select name='status' required>
//                             <option value='Pending' " . ($row['status'] === 'Pending' ? 'selected' : '') . ">Pending</option>
//                             <option value='Confirmed' " . ($row['status'] === 'Confirmed' ? 'selected' : '') . ">Confirmed</option>
//                             <option value='Booked' " . ($row['status'] === 'Booked' ? 'selected' : '') . ">Booked</option>
//                         </select>
//                         <input type='hidden' name='service_id' value='{$row['id']}'>
//                         <button type='submit' name='update_status' style='border: none; background: none; cursor: pointer;' title='Update Status'>
//                             <i class='fas fa-save text-primary'></i>
//                         </button>
//                     </form>
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
    </td>
    <td class='action-icons'>
        <form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to cancel this service?\")'>
            <input type='hidden' name='service_id' value='" . $row['id'] . "'>
            <button type='submit' name='cancel_service' class='btn btn-warning btn-sm'>Cancel</button>
        </form>
    </td>
</tr>";

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

</body>
</html>
