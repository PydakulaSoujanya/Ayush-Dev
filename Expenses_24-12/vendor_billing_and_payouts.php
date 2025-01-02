<?php

include('../config.php'); // Ensure this includes the database connection logic


// Fetch vendor data for the dropdown
$vendor_query = "SELECT id, vendor_name, phone_number FROM vendors";
$vendor_result = mysqli_query($conn, $vendor_query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Payment Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <!-- <style>
    .input-field-container {
      position: relative;
      margin-bottom: 15px;
    }

    .input-label {
      position: absolute;
      top: -10px;
      left: 10px;
      background-color: white;
      padding: 0 5px;
      font-size: 14px;
      font-weight: bold;
      color: #A26D2B;
    }

    .styled-input {
      width: 100%;
      padding: 10px;
      font-size: 12px;
      outline: none;
      box-sizing: border-box;
      border: 1px solid #A26D2B;
      border-radius: 5px;
    }

    .styled-input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    h3 {
      color: #A26D2B;
    }
  </style> -->
</head>
<body>
<?php
include('../navbar.php');
?>
<div class="container mt-7">
  <h3 class="mb-4">Add Purchase Invoice</h3>
  <form action="vendor_payment_db.php" method="POST" enctype="multipart/form-data">
    <div class="row">
      <!-- Purchase Invoice Number (Auto-Generated) -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Purchase Invoice Number</label>
          <input type="text" class="styled-input" name="purchase_invoice_number" value="Auto-generated" readonly />
        </div>
      </div>

      <!-- Bill ID -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Bill ID</label>
          <input type="text" class="styled-input" name="bill_id" placeholder="Enter Bill ID" required />
        </div>
      </div>

      <!-- Vendor Name Dropdown -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Vendor Name</label>
          <select class="styled-input" id="vendor_name" name="vendor_name" style="width: 100%;" required>
            <option value="" disabled selected>Select Vendor</option>
            <?php
            // Replace with your query to fetch vendors
            while ($row = mysqli_fetch_assoc($vendor_result)) {
              echo "<option value='{$row['vendor_name']}'>{$row['vendor_name']} ({$row['phone_number']})</option>";
            }
            ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Invoice Amount -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Invoice Amount</label>
          <input type="number" class="styled-input" id="invoice_amount" name="invoice_amount" placeholder="Enter Invoice Amount" step="0.01" required />
        </div>
      </div>

      <!-- Description -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Description</label>
          <textarea class="styled-input" name="description" rows="4" placeholder="Enter description"></textarea>
        </div>
      </div>

      <!-- Upload Bill -->
      <div class="col-md-4">
        <div class="input-field-container">
          <label class="input-label">Upload Bill</label>
          <input type="file" class="styled-input" name="bill_file" accept=".jpg,.jpeg,.png,.pdf" required />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
      </div>
    </div>
  </form>
</div>


<script>
  document.getElementById("payment_mode").addEventListener("change", function () {
  const selectedMode = this.value;

  // Hide all conditional fields by default
  document.getElementById("transaction_id_container").style.display = "none";
  document.getElementById("card_reference_container").style.display = "none";
  document.getElementById("bank_name_container").style.display = "none";

  // Clear the values of the hidden fields
  document.getElementById("transaction_id").value = "";
  document.getElementById("card_reference_number").value = "";
  document.getElementById("bank_name").value = "";

  // Show relevant fields based on the selected payment mode
  if (selectedMode === "UPI") {
    document.getElementById("transaction_id_container").style.display = "block";
  } else if (selectedMode === "Card") {
    document.getElementById("card_reference_container").style.display = "block";
  } else if (selectedMode === "Bank Transfer") {
    document.getElementById("transaction_id_container").style.display = "block";
    document.getElementById("bank_name_container").style.display = "block";
  }
});

// Update payment status and balance based on payment and paid amounts
function updatePaymentStatusAndBalance() {
  const paymentAmount = parseFloat(document.getElementById("payment_amount").value) || 0;
  const paidAmount = parseFloat(document.getElementById("paid_amount").value) || 0;
  const remainingBalanceField = document.getElementById("remainingBalanceField");
  const remainingBalanceInput = document.getElementById("remaining_balance");
  const paymentStatus = document.getElementById("payment_status");

  if (!paymentAmount) {
    // If Payment Amount is empty, reset the status and hide remaining balance
    paymentStatus.value = "";
    remainingBalanceField.style.display = "none";
    remainingBalanceInput.value = "";
    return;
  }

  if (!paidAmount) {
    // If Paid Amount is empty, set status to Pending and show remaining balance
    paymentStatus.value = "Pending";
    remainingBalanceField.style.display = "block";
    remainingBalanceInput.value = paymentAmount.toFixed(2);
  } else {
    const remainingBalance = paymentAmount - paidAmount;

    if (remainingBalance > 0) {
      paymentStatus.value = "Partially Paid";
      remainingBalanceField.style.display = "block";
      remainingBalanceInput.value = remainingBalance.toFixed(2);
    } else if (remainingBalance === 0) {
      paymentStatus.value = "Paid";
      remainingBalanceField.style.display = "none";
      remainingBalanceInput.value = "";
    }
  }
}

// Attach event listeners to the Payment Amount and Paid Amount fields
document.getElementById("payment_amount").addEventListener("input", updatePaymentStatusAndBalance);
document.getElementById("paid_amount").addEventListener("input", updatePaymentStatusAndBalance);


document.addEventListener("DOMContentLoaded", function () {
  const paymentMode = document.getElementById("payment_mode");
  const transactionIdContainer = document.getElementById("transaction_id_container");
  const cardReferenceContainer = document.getElementById("card_reference_container");
  const bankNameContainer = document.getElementById("bank_name_container");

  // Hide all conditional fields initially
  transactionIdContainer.style.display = "none";
  cardReferenceContainer.style.display = "none";
  bankNameContainer.style.display = "none";

  // Add event listener for Payment Mode changes
  paymentMode.addEventListener("change", function () {
    const selectedMode = this.value;

    // Hide all conditional fields
    transactionIdContainer.style.display = "none";
    cardReferenceContainer.style.display = "none";
    bankNameContainer.style.display = "none";

    // Show relevant fields based on selected payment mode
    if (selectedMode === "UPI") {
      transactionIdContainer.style.display = "block";
    } else if (selectedMode === "Card") {
      cardReferenceContainer.style.display = "block";
    } else if (selectedMode === "Bank Transfer") {
      transactionIdContainer.style.display = "block";
      bankNameContainer.style.display = "block";
    }
  });
});

</script>
</body>
</html>
