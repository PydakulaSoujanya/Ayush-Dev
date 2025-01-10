<?php
include '../config.php';

$total_balance = 0;

function Get_Service_Requests($conn) {
    $data = [];
    $sql1 = "CALL GetServiceRequests()";
    $result1 = mysqli_query($conn, $sql1);

    if ($result1 && $result1->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result1)) {
            // Debugging: Print out the row to ensure the 'id' is available
            // print_r($row); exit;

            // Check if 'id' exists in the row
            if (!isset($row['id'])) {
                continue; // Skip if 'id' is not set
            }

            $serviceId = $row['id'];
            $assignedEmployee = !empty($row['assigned_employee']) ? $row['assigned_employee'] : 'Not Assigned';

            mysqli_next_result($conn);
            $invoiceQuery = "CALL GetInvoiceDetails(?) COLLATE utf8mb4_unicode_ci"; // Ensure consistent collation
            $stmt = $conn->prepare($invoiceQuery);
            $stmt->bind_param("i", $serviceId);
            $stmt->execute();
            $invoiceResult = $stmt->get_result();

            $invoiceId = null;
            $totalPaidAmount = 0;
            if ($invoiceResult && $invoiceResult->num_rows > 0) {
                $invoiceRow = $invoiceResult->fetch_assoc();
                $invoiceId = $invoiceRow['invoice_id'];

                mysqli_next_result($conn);
                $paidAmountQuery = "CALL GetTotalPaidAmount(?) COLLATE utf8mb4_unicode_ci"; // Ensure consistent collation
                $paidStmt = $conn->prepare($paidAmountQuery);
                $paidStmt->bind_param("i", $invoiceId);
                $paidStmt->execute();
                $paidResult = $paidStmt->get_result();

                if ($paidResult && $paidRow = $paidResult->fetch_assoc()) {
                    $totalPaidAmount = $paidRow['total_paid'] ?? 0;
                }
                $paidResult->free();
                $paidStmt->close();
            }

            $servicePrice = $row['service_price'] ?? 0;
            $balance = $servicePrice - $totalPaidAmount;

            $data[] = [
                'id' => $row['id'],
                'customer_name' => $row['customer_name'],
                'contact_no' => $row['contact_no'],
                'service_price' => $servicePrice,
                'total_paid' => $totalPaidAmount,
                'balance' => $balance,
                'invoice_id' => $invoiceId,
                'due_date' => '20-12-2024', 
            ];

            global $total_balance;
            $total_balance += $balance;

            $invoiceResult->free();
            $stmt->close();
        }
    }
    return $data;
}

$serviceRequests = Get_Service_Requests($conn);
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
    <?php include '../navbar.php'; ?>
    <div class="container mt-7">
        <h3 class="text-center">You have to receive more Rs. <?php echo number_format($total_balance, 2); ?></h3>
        <div class="dataTable_card card">
            <div class="card-header">Account Receivable Details</div>
            <div class="table-responsive p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>S.no</th>
                            <th>Customer Info</th>
                            <th>Invoice ID</th>
                            <th>Total Price</th>
                            <th>Received</th>
                            <th>Balance</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 1;
                        foreach ($serviceRequests as $request) {
                            echo "<tr>
                            <td>{$serial}</td>
                            <td>
                                <strong>Name:</strong> " . htmlspecialchars($request['customer_name']) . "<br>
                                <strong>Phone:</strong> " . htmlspecialchars($request['contact_no']) . "
                            </td>
                            <td onclick=\"window.location.href='../Capturing-Services/view_single_invoice.php?invoice_id=" . $request['invoice_id'] . "';\" 
                                style=\"cursor: pointer; color: blue; text-decoration: underline;\">
                                {$request['invoice_id']}
                            </td>
                            <td>{$request['service_price']}</td>
                            <td>{$request['total_paid']}</td>
                            <td>{$request['balance']}</td>
                            <td>{$request['due_date']}</td>
                        </tr>";
                        
                            $serial++;
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