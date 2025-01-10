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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<?php
include('../navbar.php');
?>
<div class="container mt-7">
<div class="row">
    <div class="col-md-8">
  <h3 class="mb-4">Add Purchase Invoice</h3>
  </div>
  <div class="col-md-4">
  <div class="text-right mb-3">
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#vendorModal">
        <strong class="add_button_plus">+</strong> Add Vendor
    </button>
</div>
</div>
</div>
  <form action="vendor_payment_db.php" method="POST" enctype="multipart/form-data">
    <div class="row form-section form-first-row">
     
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
          <select class="styled-input" id="vendor_id" name="vendor_id" style="width: 100%;" required>
  <option value="" disabled selected>Select Vendor</option>
  <?php
  while ($row = mysqli_fetch_assoc($vendor_result)) {
    echo "<option value='{$row['id']}'>{$row['vendor_name']} ({$row['phone_number']})</option>";
  }
  ?>
</select>

        </div>
      </div>
    </div>

    <div class="row form-section form-second-row-full mt-3">
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


 <!-- Vendor Form Modal -->
<div class="modal fade" id="vendorModal" tabindex="-1" aria-labelledby="vendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vendorModalLabel">Vendor Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="vendordb.php" method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Name</label>
            <input type="text" id="vendor_name" name="vendor_name" class="styled-input" placeholder="Enter your name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">GSTIN</label>
            <input type="gstin" class="styled-input" id="gstin" name="gstin" placeholder="Enter your gstin" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Contact Person</label>
            <input type="text" id="contact_person" name="contact_person" class="styled-input" placeholder="Enter Contact person name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Documents</label>
            <input type="file" name="supporting_documents" id="supporting_documents" class="styled-input" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Phone Number</label>
            <input type="phone_number" id="phone_number" name="phone_number" class="styled-input" placeholder="Enter your phonenumber" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Email</label>
            <input type="email" id="email" name="email" class="styled-input" placeholder="Enter your email" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Services Provided</label>
            <select id="services_provided" name="services_provided" class="styled-input">
              <option value="Fully Trained Nurse">Fully Trained Nurse</option>
              <option value="Semi-Trained Nurse">Semi-Trained Nurse</option>
              <option value="Caretaker">Caretaker</option>
              <option value="Caretaker">Naanies</option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Type</label>
            <select class="styled-input" name="vendor_type" id="vendor_type">
              <option value="Individual">Individual</option>
              <option value="Company">Company</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Vendor Groups</label>
            <select id="vendor_groups" name="vendor_groups" class="styled-input">
              <option value="Nursing Services">Nursing Services</option>
              <option value="Electricity Services">Electricity Services</option>
              <option value="Others">Others</option>
            </select>
          </div>
        </div>

        <!-- New address fields -->
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Pincode</label>
            <input 
              type="text" 
              name="pincode" 
              class="styled-input" 
              placeholder="6 digits [0-9] PIN code" 
              required 
              pattern="\d{6}" 
              maxlength="6" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Flat, House No., Building, Company, Apartment</label>
            <input 
              type="text" 
              name="address_line1" 
              class="styled-input" 
              placeholder="Enter Flat, House No., Building, etc." 
              required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Area, Street, Sector, Village</label>
            <input 
              type="text" 
              name="address_line2" 
              class="styled-input" 
              placeholder="Enter Area, Street, Sector, Village" />
          </div>
        </div>
      </div>

      <div class="row">
      

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Landmark</label>
            <input 
              type="text" 
              name="landmark" 
              class="styled-input" 
              placeholder="E.g. near Apollo Hospital" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Town/City</label>
            <input 
              type="text" 
              name="city" 
              class="styled-input" 
              placeholder="Enter Town/City" 
              required />
          </div>
        </div>
      </div>

      <div class="row">
       

        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">State</label>
            <select 
              name="state" 
              class="styled-input" 
              required>
              <option value="" disabled selected>Choose a state</option>
              <option value="Andhra Pradesh">Andhra Pradesh</option>
              <option value="Arunachal Pradesh">Arunachal Pradesh</option>
              <option value="Assam">Assam</option>
              <option value="Bihar">Bihar</option>
              <option value="Chhattisgarh">Chhattisgarh</option>
              <option value="Goa">Goa</option>
              <option value="Gujarat">Gujarat</option>
              <option value="Haryana">Haryana</option>
              <option value="Himachal Pradesh">Himachal Pradesh</option>
              <option value="Jharkhand">Jharkhand</option>
              <option value="Karnataka">Karnataka</option>
              <option value="Kerala">Kerala</option>
              <option value="Madhya Pradesh">Madhya Pradesh</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Manipur">Manipur</option>
              <option value="Meghalaya">Meghalaya</option>
              <option value="Mizoram">Mizoram</option>
              <option value="Nagaland">Nagaland</option>
              <option value="Odisha">Odisha</option>
              <option value="Punjab">Punjab</option>
              <option value="Rajasthan">Rajasthan</option>
              <option value="Sikkim">Sikkim</option>
              <option value="Tamil Nadu">Tamil Nadu</option>
              <option value="Telangana">Telangana</option>
              <option value="Tripura">Tripura</option>
              <option value="Uttar Pradesh">Uttar Pradesh</option>
              <option value="Uttarakhand">Uttarakhand</option>
              <option value="West Bengal">West Bengal</option>
              <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
              <option value="Chandigarh">Chandigarh</option>
              <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
              <option value="Delhi">Delhi</option>
              <option value="Jammu and Kashmir">Jammu and Kashmir</option>
              <option value="Ladakh">Ladakh</option>
              <option value="Lakshadweep">Lakshadweep</option>
              <option value="Puducherry">Puducherry</option>
            </select>
          </div>
        </div>
      </div>

      <h3 class="mb-4">Bank Details</h3>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter bank name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Account Number</label>
            <input type="text" class="styled-input" id="account_number" name="account_number" placeholder="Enter account number" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">IFSC</label>
            <input type="text" id="ifsc" name="ifsc" class="styled-input" placeholder="Enter IFSC code" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-field-container">
            <label class="input-label">Branch</label>
            <input type="text" id="branch" name="branch" class="styled-input" placeholder="Enter Branch" />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
      </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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