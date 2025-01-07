<?php
include('../config.php');

$employee = [];

// Fetch employee details for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Prepare the SQL query
    $sql = "SELECT * FROM emp_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        if (!$employee) {
            echo "Employee not found.";
            exit;
        }
    } else {
        echo "Error fetching employee data.";
        exit;
    }
    $stmt->close();
} 

// Update employee details for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $employee_id = $_POST['id'];

    // Sanitize and collect input data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $doj = $_POST['doj'];
    $aadhar = $_POST['aadhar'];
    $police_verification = $_POST['police_verification'];
    $daily_rate = $_POST['daily_rate'];
    $status = $_POST['status'];
    $termination_date = $_POST['termination_date'];
    $bank_name = $_POST['bank_name'];
    $bank_account_no = $_POST['bank_account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $address = $_POST['address'];

    // Handle file upload if provided
    $document = null;
    if (!empty($_FILES['document']['name'])) {
        $target_dir = "uploads/";
        $document = $target_dir . basename($_FILES['document']['name']);
        move_uploaded_file($_FILES['document']['tmp_name'], $document);
    } else {
        // If no new document is uploaded, retain the old document
        $document = $employee['document']; // Retain the old document if no new file is uploaded
    }

    // Prepare the update query
    $sql = "UPDATE emp_info SET name=?, dob=?, gender=?, phone=?, email=?, role=?, qualification=?, 
            experience=?, doj=?, aadhar=?, police_verification=?, daily_rate=?, status=?, 
            termination_date=?, bank_name=?, bank_account_no=?, ifsc_code=?, address=?, document=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssi",
        $name,
        $dob,
        $gender,
        $phone,
        $email,
        $role,
        $qualification,
        $experience,
        $doj,
        $aadhar,
        $police_verification,
        $daily_rate,
        $status,
        $termination_date,
        $bank_name,
        $bank_account_no,
        $ifsc_code,
        $address,
        $document,
        $employee_id
    );

    if ($stmt->execute()) {
        echo "Employee updated successfully.";
        header("Location: employee_list.php"); // Redirect to list page after update
        exit;
    } else {
        echo "Error updating employee.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

    h1, h2, h3, h4 {
      color: #A26D2B;
    }
  </style> -->
</head>
<body>
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <h3 class="mb-4">Edit Employee</h3>
    <form action="update_emp.php" method="POST" enctype="multipart/form-data">
    <!-- Hidden ID Field -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']); ?>">

    <!-- Basic Details -->
    
    <div class="row">
        <div class="col-md-3">
            <label class="input-label">Name</label>
            <input type="text" name="name" class="styled-input" value="<?= htmlspecialchars($employee['name']); ?>" required />
        </div>
        <div class="col-md-3">
            <label class="input-label">Date of Birth</label>
            <input type="date" name="dob" class="styled-input" value="<?= htmlspecialchars($employee['dob']); ?>" required />
        </div>
        <div class="col-md-3">
            <label class="input-label">Gender</label>
            <select name="gender" class="styled-input">
                <option value="Male" <?= $employee['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $employee['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?= $employee['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="input-label">Phone</label>
            <input type="tel" name="phone" class="styled-input" value="<?= htmlspecialchars($employee['phone']); ?>" pattern="[0-9]{10}" required />
        </div>
    </div>

    <!-- Contact Details -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Email</label>
            <input type="email" name="email" class="styled-input" value="<?= htmlspecialchars($employee['email']); ?>" required />
        </div>
        <div class="col-md-3">
            <label class="input-label">Role</label>
            <input type="text" name="role" class="styled-input" value="<?= htmlspecialchars($employee['role']); ?>" required />
        </div>
        <div class="col-md-3">
            <label class="input-label">Qualification</label>
            <input type="text" name="qualification" class="styled-input" value="<?= htmlspecialchars($employee['qualification']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Experience</label>
            <input type="text" name="experience" class="styled-input" value="<?= htmlspecialchars($employee['experience']); ?>" />
        </div>
    </div>

    <!-- Employment Details -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Date of Joining</label>
            <input type="date" name="doj" class="styled-input" value="<?= htmlspecialchars($employee['doj']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Employment Status</label>
            <select name="status" class="styled-input">
                <option value="Active" <?= $employee['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="Inactive" <?= $employee['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="input-label">Aadhar Number</label>
            <input type="text" name="aadhar" class="styled-input" value="<?= htmlspecialchars($employee['aadhar']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Police Verification Number</label>
            <input type="text" name="police_verification" class="styled-input" value="<?= htmlspecialchars($employee['police_verification']); ?>" />
        </div>
    </div>

    <!-- Documents -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Police Verification Form</label>
            <input type="file" name="police_verification_form" class="styled-input" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Daily Rate (8 hours)</label>
            <input type="number" name="daily_rate8" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate8']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Daily Rate (12 hours)</label>
            <input type="number" name="daily_rate12" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate12']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Daily Rate (24 hours)</label>
            <input type="number" name="daily_rate24" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate24']); ?>" />
        </div>
    </div>

    <!-- Bank Details -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Bank Name</label>
            <input type="text" name="bank_name" class="styled-input" value="<?= htmlspecialchars($employee['bank_name']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Bank Account Number</label>
            <input type="text" name="bank_account_no" class="styled-input" value="<?= htmlspecialchars($employee['bank_account_no']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">IFSC Code</label>
            <input type="text" name="ifsc_code" class="styled-input" value="<?= htmlspecialchars($employee['ifsc_code']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Vendor Name</label>
            <input type="text" name="vendor_name" class="styled-input" value="<?= htmlspecialchars($employee['vendor_name']); ?>" />
        </div>
    </div>

    <!-- Vendor Contact -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Vendor ID</label>
            <input type="text" name="vendor_id" class="styled-input" value="<?= htmlspecialchars($employee['vendor_id']); ?>" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Vendor Contact</label>
            <input type="tel" name="vendor_contact" class="styled-input" value="<?= htmlspecialchars($employee['vendor_contact']); ?>" pattern="[0-9]{10}" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Aadhar Upload</label>
            <input type="file" name="adhar_upload_doc" class="styled-input" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Document 1</label>
            <input type="file" name="document1" class="styled-input" />
        </div>
    </div>

    <!-- Additional Documents -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label class="input-label">Document 2</label>
            <input type="file" name="document2" class="styled-input" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Document 3</label>
            <input type="file" name="document3" class="styled-input" />
        </div>
        <div class="col-md-3">
            <label class="input-label">Address</label>
            <textarea name="address" class="styled-input"><?= htmlspecialchars($employee['address']); ?></textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="input-field-container mt-3">
        <button type="submit" class="btn btn-primary">Update Employee</button>
    </div>
</form>


  </div>
</body>
</html>
