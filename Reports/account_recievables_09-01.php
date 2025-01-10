<?php
// Connect to the database
include '../config.php';


$total_balance = 0;

// Initialize $serial to 1 as $start is not defined
$serial = 1;

// Fetch service requests
$sql1 = "CALL GetServiceRequests()";
$result1 = mysqli_query($conn, $sql1);

if ($result1) {
    while ($row = mysqli_fetch_assoc($result1)) {
        // Check if 'id' exists in the result
        if (!isset($row['id'])) {
            continue;
        }

        // Check if assigned employee exists, otherwise set default
        $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';

        // Fetch invoice ID for this specific row (service request)
        $serviceId = $row['id'];
        mysqli_next_result($conn); // Free previous result set
        $invoiceQuery = "CALL GetInvoiceTableDetails(?)";
        $stmt = $conn->prepare($invoiceQuery);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $invoiceResult = $stmt->get_result();

        // Fetch the invoice ID if it exists
        $invoiceId = null;
        $totalPaidAmount = 0;
        if ($invoiceResult && $invoiceResult->num_rows > 0) {
            $invoiceRow = $invoiceResult->fetch_assoc();
            $invoiceId = $invoiceRow['invoice_id'];

            // Fetch total paid amount using the stored procedure
            mysqli_next_result($conn); // Free previous result set
            $paidAmountQuery = "CALL GetTotalPaidAmount(?)";
            $paidStmt = $conn->prepare($paidAmountQuery);
            $paidStmt->bind_param("i", $invoiceId);
            $paidStmt->execute();
            $paidResult = $paidStmt->get_result();

            if ($paidResult && $paidRow = $paidResult->fetch_assoc()) {
                $totalPaidAmount = $paidRow['total_paid'] ?? 0; // Handle null sum
            }
        }

        $service_price = $row['service_price'] ?? 0; // Assuming $row['service_price'] is fetched from a database
        $deduction = $totalPaidAmount;
        $balance = $service_price - $deduction;

        // Add the calculated balance to the total balance
        $total_balance += $balance;
        echo $total_balance;
    }
} else {
    echo "Failed to fetch service requests.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
  <title>Services</title>
  
</head>
<body>
 <?php
  include '../navbar.php';
  ?>   

  
  <div class="container  mt-7">
    <h3 class="text-center">You have to receive more Rs. <?php echo number_format($total_balance, 2); ?></h3>
    <div class="dataTable_card card">
      <!-- Card Header -->
      <div class="card-header">Account Recievable Details</div>
        <!-- Table -->
        <div class="table-responsive  p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark mt-4">
        <tr>
            <th>S.no</th>
            <th>Customer Info</th>
            <th>Invoice ID</th>
            <th>Total Price</th>
            <th>Recieved</th>
            <th>Balance</th>
            <th>Due Date</th>

            
   
            
        </tr>
    </thead>
    <tbody>
    <?php

$total_balance = 0;
$sql1 = "CALL GetServiceRequests()";
$result1 = mysqli_query($conn, $sql1);

if ($result1->num_rows > 0) {
    $serial = 1; // Start from 1 since there is no pagination
    while ($row = mysqli_fetch_assoc($result1)) {
        $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';

        // Fetch invoice ID for this specific row (service request)
        $serviceId = $row['id'];
        $invoiceQuery = "CALL GetInvoiceDetails(?)";
$stmt = $conn->prepare($invoiceQuery);
$stmt->bind_param("i", $invoiceId); // Change parameter type if needed
$stmt->execute();
$invoiceResult = $stmt->get_result();

        // Fetch the invoice ID if it exists
        $invoiceId = null;
        $totalPaidAmount = 0;
        if ($invoiceResult->num_rows > 0) {
            $invoiceRow = $invoiceResult->fetch_assoc();
            $invoiceId = $invoiceRow['invoice_id'];
            // Fetch total paid amount using the stored procedure
$paidAmountQuery = "CALL GetTotalPaidAmount(?)";
$paidStmt = $conn->prepare($paidAmountQuery);
$paidStmt->bind_param("i", $invoiceId); // Assuming invoiceId is an integer
$paidStmt->execute();
$paidResult = $paidStmt->get_result();
if ($paidRow = $paidResult->fetch_assoc()) {
    $totalPaidAmount = $paidRow['total_paid'] ?? 0; // Handle null sum
} else {
    $totalPaidAmount = 0; // No payments found
}
        }

        $service_price = $row['service_price']; // Assuming $row['service_price'] is fetched from a database
        $deduction = $totalPaidAmount;
        $balance = $service_price - $deduction;
        $total_balance += $balance;

        // Generate the table row
        echo "<tr class='dataTable_row'>
                <td>{$serial}</td>
                <td>
                  <strong>Name:</strong> " . htmlspecialchars($row['customer_name']) . "<br>
                  <strong>Phone:</strong> " . htmlspecialchars($row['contact_no']) . "
                </td>
                <td onclick=\"window.location.href='../Capturing-Services/view_single_invoice.php?invoice_id=" . $invoiceId . "';\" 
                    style=\"cursor: pointer; color: blue; text-decoration: underline;\">
                    $invoiceId
                </td>
                <td>{$row['service_price']}</td>
                <td>{$totalPaidAmount}</td>
                <td>$balance</td>
                <td>20-12-2024</td>
            </tr>";

        $serial++;
    }
} else {
    echo "<tr><td colspan='8'>No data available</td></tr>";
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
            throw new Error(HTTP error! status: ${response.status});
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
 
      </div>
    </div>
  </div>
  
  
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
      $(document).ready(function() {
      // Initialize DataTable
      const table = $('#employeeTable').DataTable({
        paging: true, // Enable pagination
        searching: true, // Enable global search
        ordering: true, // Enable column-based ordering
        lengthMenu: [5, 10, 20, 50], // Rows per page options
        pageLength: 5, // Default rows per page
        language: {
          search: "Search Payouts:", // Customize the search label
        },
      });

      // Global Search
      $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
      });
      
    });
</script>

</body>
</html>