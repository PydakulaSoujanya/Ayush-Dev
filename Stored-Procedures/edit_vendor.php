<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../config.php';  // Ensure the correct path to your database connection file

// Fetch vendor data if vendor_id is set in the URL
if (isset($_GET['vendor_id'])) {
    $vendor_id = $_GET['vendor_id'];

    // Fetch vendor details from the database
    $query = "SELECT * FROM sp_vendors WHERE vendor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if results were returned
    if ($result->num_rows > 0) {
        $vendor = $result->fetch_assoc();
    } else {
        echo "No vendor found with this ID.<br>";
        exit;
    }
} else {
    echo "No vendor_id parameter in the URL.<br>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include('../navbar.php'); ?>

<div class="container mt-7">
    <h3 class="mb-4">Edit Vendor Form</h3>
    <form action="update_vendor.php" method="POST">
    <!-- Hidden input to pass the vendor_id -->
    <input type="hidden" name="vendor_id" value="<?php echo $vendor ? $vendor['vendor_id'] : ''; ?>" />

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Vendor Name</label>
                <input type="text" id="vendor_name" name="vendor_name" class="styled-input" value="<?php echo $vendor ? $vendor['vendor_name'] : ''; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">GSTIN</label>
                <input type="text" class="styled-input" id="gstin" name="gstin" value="<?php echo $vendor ? $vendor['gstin'] : ''; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Contact Person</label>
                <input type="text" id="contact_person" name="contact_person" class="styled-input" value="<?php echo $vendor ? $vendor['contact_person'] : ''; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="styled-input" value="<?php echo $vendor ? $vendor['phone_number'] : ''; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Email</label>
                <input type="email" id="email" name="email" class="styled-input" value="<?php echo $vendor ? $vendor['email'] : ''; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Services Provided</label>
                <select id="services_provided" name="services_provided" class="styled-input">
                    <option value="Fully Trained Nurse" <?php echo ($vendor && $vendor['services_provided'] == 'Fully Trained Nurse') ? 'selected' : ''; ?>>Fully Trained Nurse</option>
                    <option value="Semi-Trained Nurse" <?php echo ($vendor && $vendor['services_provided'] == 'Semi-Trained Nurse') ? 'selected' : ''; ?>>Semi-Trained Nurse</option>
                    <option value="Caretaker" <?php echo ($vendor && $vendor['services_provided'] == 'Caretaker') ? 'selected' : ''; ?>>Caretaker</option>
                    <option value="Nannies" <?php echo ($vendor && $vendor['services_provided'] == 'Nannies') ? 'selected' : ''; ?>>Nannies</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Vendor Type</label>
                <select id="vendor_type" name="vendor_type" class="styled-input">
                    <option value="Individual" <?php echo ($vendor && $vendor['vendor_type'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                    <option value="Company" <?php echo ($vendor && $vendor['vendor_type'] == 'Company') ? 'selected' : ''; ?>>Company</option>
                    <option value="Other" <?php echo ($vendor && $vendor['vendor_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Pincode</label>
                <input type="text" id="pincode" name="pincode" class="styled-input" value="<?php echo $vendor ? $vendor['pincode'] : ''; ?>" pattern="\d{6}" maxlength="6" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Address Line 1</label>
                <input type="text" id="address_line1" name="address_line1" class="styled-input" value="<?php echo $vendor['address_line1']; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Address Line 2</label>
                <input type="text" id="address_line2" name="address_line2" class="styled-input" value="<?php echo $vendor['address_line2']; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Landmark</label>
                <input type="text" id="landmark" name="landmark" class="styled-input" value="<?php echo $vendor['landmark']; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">City</label>
                <input type="text" id="city" name="city" class="styled-input" value="<?php echo $vendor['city']; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">State</label>
                <input type="text" id="state" name="state" class="styled-input" value="<?php echo $vendor['state']; ?>" />
            </div>
        </div>
    </div>

    <h3 class="mb-4">Bank Details</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="styled-input" value="<?php echo $vendor['bank_name']; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Account Number</label>
                <input type="text" id="account_number" name="account_number" class="styled-input" value="<?php echo $vendor['account_number']; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">IFSC Code</label>
                <input type="text" id="ifsc" name="ifsc" class="styled-input" value="<?php echo $vendor['ifsc']; ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Branch</label>
                <input type="text" id="branch" name="branch" class="styled-input" value="<?php echo $vendor['branch']; ?>" />
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Vendor</button>
</form>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
