<?php


// error_reporting(E_ALL); // Report all PHP errors
// ini_set('display_errors', 1); // Display errors on the screen
// ini_set('display_startup_errors', 1); // Display errors during PHP's startup sequence

include '../config.php';


?>
<?php
// Handle single invoice view
if (isset($_GET['invoice_id'])) {
    $invoiceId = $_GET['invoice_id'];
    
    // Fetch the invoice details and calculate total paid amount
$InvoiceSql = "SELECT `invoice_id`, `id`, `total_amount`, 
                      SUM(`paid_amount`) AS total_paid_amount, 
                      `due_date`, `status`, `created_at`, `updated_at` 
               FROM `invoice` 
               WHERE `invoice_id` = ? 
               GROUP BY `invoice_id`
               LIMIT 1";

$stmt = $conn->prepare($InvoiceSql);
$stmt->bind_param("s", $invoiceId);
$stmt->execute();
$singleResult = $stmt->get_result();

if ($singleResult->num_rows > 0) {
    // Fetch the result
    $row = $singleResult->fetch_assoc();


  $totalPaidAmount = $row['total_paid_amount'] ?? 0; // Assign 0 if $totalPaidAmount is null
$totalAmount = $row['total_amount'] ; // Assign 0 if $totalAmount is null
$forpopinvoiceId = $row['invoice_id'] ?? ''; // Assign empty string if $invoice_id is null

// Safely calculate due amount
$dueAmount = max(0, $totalAmount - $totalPaidAmount);

// Calculate status using PHP
if ($totalPaidAmount == 0) {
    $status = 'Pending';
} elseif ($dueAmount == 0) {
    $status = 'Paid';
} elseif ($dueAmount > 0 && $dueAmount < $totalAmount) {
    $status = 'Partially Paid';
} else {
    $status = 'Unknown'; // Fallback for invalid data
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
  <title>Single Invoice Details</title>
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
  <?php  include '../navbar.php'; ?>
  <div class="container  mt-7">
    <div class="dataTable_card card">
      <!-- Card Header -->
      <!--<div class="card-header">Invoicing Table</div>-->

    
    
  
    
<div>
    <span id="spaninvoiceId" hidden><?php echo $forpopinvoiceId; ?></span><br>
    <!-- First Line: Invoice ID -->
    <div style="padding: 5px;">
        <h3>
        INVOICE ID: 
        <a href="download_pdf.php?invoice_id=<?php echo htmlspecialchars($row['invoice_id']); ?>">
            <?php echo htmlspecialchars($row['invoice_id']); ?>
        </a></h3>
    </div>

    <!-- Second Line: Total Amount and Total Paid Amount -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 5px; flex-wrap: wrap;">
       <!-- Total Amount -->
    <div>
        <strong>TOTAL AMOUNT:</strong>
        <span id="totalAmount"><?php echo number_format($totalAmount, 2); ?></span>
    </div>

    <!-- Total Paid Amount -->
    <div>
        <strong>TOTAL PAID AMOUNT:</strong>
        <span id="totalPaidAmount"><?php echo number_format($totalPaidAmount, 2); ?></span>
    </div>

    <!-- Due Amount -->
    <div>
        <strong>DUE AMOUNT:</strong>
        <span id="dueAmount"><?php echo number_format($dueAmount, 2); ?></span>
    </div>

    <!-- Status -->
    <div>
        <strong>STATUS:</strong>
        <span id="status"><?php echo $status; ?></span>
    </div>
<?php
}

?>

 <!-- Button to trigger modal -->
<?php
// Determine the visibility of the button
$button_visibility = ($status === 'Paid') ? 'style="display:none;"' : '';
?>
  </div>

  <div class="card-header" style="display: flex; justify-content: flex-end;">

    <button id="paymentBtn" class="add_button" style="text-align: right;"> <?php echo $button_visibility; ?> <strong class="add_button_plus">+</strong>Receipt</button></div>

  
  
<!-- Button to trigger modal -->

      
  


<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Generate Receipt</h5>
                
                   
                </button>
            </div>
            <form id="paymentForm">
    <div class="modal-body">
        <!-- Display total and paid amounts -->
        <div style="padding: 5px;">
            <strong>Total Amount:</strong> <span id="modalTotalAmount"></span>
        </div>
        <div style="padding: 5px;">
            <strong>Paid Amount:</strong> <span id="modalPaidAmount"></span>
        </div>
        <div style="padding: 5px;">
            <strong>Due Amount:</strong> <span id="modalDueAmount"></span>
        </div>

        <!-- Form for entering amount to pay -->
        <div style="padding: 5px;">
            <label for="amountToPay">Amount to Pay:</label>
            <input type="number" id="amountToPay" name="amountToPay" />
        </div>

        <!-- Receipt Date -->
        <div style="padding: 5px;">
            <label for="receipt_date">Receipt Date:</label>
            <input type="date" id="receipt_date" name="receipt_date" required />
        </div>

        <!-- Hidden input for invoice_id -->
        <input type="hidden" id="modalinvoiceId" name="modalinvoiceId">
        <input type="hidden" id="currentDate" name="currentDate">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeModalBtn">Close</button>

        <!-- Submit Button -->
        <button id="submitPaymentBtn" type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$('#closeModalBtn').click(function() {
    $('#paymentModal').modal('hide'); // Hide the modal when the close button is clicked
});

// Recalculate due amount when the user changes the paid amount
document.getElementById('totalPaidAmount').addEventListener('input', function() {
    var totalAmount = parseFloat(document.getElementById('totalAmount').value);
    var totalPaidAmount = parseFloat(document.getElementById('totalPaidAmount').value);

    // Calculate the due amount
    var dueAmount = totalAmount - totalPaidAmount;

    // Update the due amount field
    document.getElementById('dueAmount').value = dueAmount.toFixed(2);
});

document.getElementById('paymentBtn').addEventListener('click', function() {
    // Get values from other parts of the page (make sure these are correctly set somewhere in the HTML)
    var totalAmount = document.getElementById('totalAmount').innerText;
    var paidAmount = document.getElementById('totalPaidAmount').innerText;
    var dueAmount = document.getElementById('dueAmount').innerText;
    
    var spaninvoiceId = document.getElementById('spaninvoiceId').innerText;
    

     // Set these values into the modal
        document.getElementById('modalTotalAmount').innerText = totalAmount;
        document.getElementById('modalPaidAmount').innerText = paidAmount;
        document.getElementById('modalDueAmount').innerText = dueAmount;
       document.getElementById('modalinvoiceId').value = spaninvoiceId; // Set invoiceId in the modal's hidden input
     

        // Open the modal (using jQuery)
        $('#paymentModal').modal('show');
    });

    // Handle the form submission via AJAX
    document.getElementById('paymentForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission behavior

    // Get form data
    var invoiceId = document.getElementById('modalinvoiceId').value;
    var amountToPay = document.getElementById('amountToPay').value;
    var receiptDate = document.getElementById('receipt_date').value;

    // Validate input
    if (!amountToPay || amountToPay <= 0) {
        alert('Please enter a valid payment amount.');
        return;
    }
    if (!receiptDate) {
        alert('Please select a receipt date.');
        return;
    }

    // Prepare the data to send to the PHP backend
    var data = {
        invoice_id: invoiceId,
        amount_paid: parseFloat(amountToPay),
        receipt_date: receiptDate, // Include receipt date
    };

    // Send the AJAX request
    $.ajax({
        url: 'invoice_receipt_payment.php', // PHP file handling the payment
        type: 'POST',
        contentType: 'application/json', // Sending JSON data
        data: JSON.stringify(data),
        success: function (response) {
            console.log(response);

            if (response.success) {
                alert('Payment processed successfully!');
                location.reload(); // Reload the page to update values (optional)
            } else {
                alert('Error: ' + (response.error || 'Failed to process the payment.'));
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('Failed to send the payment request. Please try again.');
        }
    });
});

</script>






        <!-- Table -->
        <div class="table-responsive mt-3 p-4">
                <table id="employeeTable" class="display table table-striped" style="width:100%">
                    <thead class="thead-dark mt-4">
        <tr>
            <th>S.no</th>
            <th>Receipt ID</th>
            <th>Customer Name</th>
            <th>Mobile Number</th>
            <!--<th>Total Amount</th>-->
            <th>Paid Amount</th>
            <!--<th>Due Date</th>-->
            <!--<th>Status</th>-->
            <th>Reciept Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

  <?php              
                 $onlyInvoiceSql = "SELECT `id`, `invoice_id`, `receipt_id`, `pdf_invoice_path`, `service_id`, 
                                `customer_name`, `mobile_number`, `total_amount`, `paid_amount`, 
                                `due_date`, `status`, `created_at`, `updated_at`, `receipt_date`
                         FROM `invoice` 
                         WHERE `invoice_id` = ?";
    $stmt = $conn->prepare($onlyInvoiceSql);
    $stmt->bind_param("s", $invoiceId);
    $stmt->execute();
    $singleResult = $stmt->get_result();

    if ($singleResult->num_rows > 0) {
        
      
   
    
   
        $start=0;
        
       
            $serial = $start + 1; // Assuming `$start` is defined elsewhere
            while ($row = $singleResult->fetch_assoc()) {
                 if (!empty($row['receipt_id'])) {


  
                echo "<tr>
                        <td>{$serial}</td>
                      <td> <a href='download_pdf.php?invoice_id={$row['id']}'>
    {$row['receipt_id']}
</a></td>

                        <td>{$row['customer_name']}</td>
                        <td>{$row['mobile_number']}</td>
                        
                       
                        <td>{$row['paid_amount']}</td>
                      
                        
                        <td> ". date('d-m-Y', strtotime($row['receipt_date'])) ." </td>
                        
                        <td class='action-icons'>
                            <i class='fas fa-eye' style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#viewModal' onclick='viewDetails(".json_encode($row).")'></i>
                            
                        </td>
                    </tr>";
                $serial++;
            } 
       
        }
        } else {
            echo "<tr><td colspan='12'>No data available</td></tr>";
       }
        ?>
        
<!--        <td class='action-icons'>-->
<!--                            <i class='fas fa-eye' style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#viewModal' onclick='viewDetails(".json_encode($row).")'></i>-->
<!--                            <a href='#' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='populateUpdateForm(".json_encode($row).")'>-->
<!--    <i class='fas fa-edit'></i>-->
<!--</a>-->

<!--                            <a href='delete_invoice.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fas fa-trash'></i></a>-->
<!--                        </td>-->
    </tbody>
</table>
</div>
        </div>
        </div>    


        

   <!-- Modal -->
   <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Invoice Details</h5>
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
<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateInvoiceForm" action="update_invoice.php" method="POST" onsubmit="handleFormSubmit(event)">
                <div class="modal-body">
                    <input type="hidden" name="id" id="invoiceId" />
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobileNumber" name="mobile_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customerEmail" name="customer_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="totalAmount" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" id="totalAmount" name="total_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
          search: "Search Employee Claims:", // Customize the search label
        },
      });

      // Global Search
      $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
</script>
  <script>
  function populateUpdateForm(data) {
    document.getElementById('invoiceId').value = data.id;
    document.getElementById('customerName').value = data.customer_name;
    document.getElementById('mobileNumber').value = data.mobile_number;
    document.getElementById('customerEmail').value = data.customer_email;
    document.getElementById('totalAmount').value = data.total_amount;
    document.getElementById('dueDate').value = data.due_date;
    document.getElementById('status').value = data.status;
}

      function viewDetails(data) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <table class="table table-bordered">
            <tr><th>Invoice ID</th><td>${data.invoice_id}</td></tr>
            <tr><th>Customer Name</th><td>${data.customer_name}</td></tr>
            <tr><th>Mobile Number</th><td>${data.mobile_number}</td></tr>
            <tr><th>Email</th><td>${data.customer_email}</td></tr>
            <tr><th>Service ID</th><td>${data.service_id}</td></tr>
            <tr><th>Total Amount</th><td>${data.total_amount}</td></tr>
            <tr><th>Due Date</th><td>${data.due_date}</td></tr>
            <tr><th>Status</th><td>${data.status}</td></tr>
            <tr><th>Created At</th><td>${data.created_at}</td></tr>
            <tr><th>Updated At</th><td>${data.updated_at}</td></tr>
        </table>
    `;
}
function handleFormSubmit(event) {
    event.preventDefault();
    const form = document.getElementById('updateInvoiceForm');
    const formData = new FormData(form);

    fetch('update_invoice.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Invoice updated successfully!');
                window.location.href = 'view_invoice.php';
            } else {
                alert('Error updating invoice: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
}

  </script>
  <?php  } ?>
</body>
</html>
