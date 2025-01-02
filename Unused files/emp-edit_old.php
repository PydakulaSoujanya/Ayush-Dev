<?php
include('config.php');

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

  <style>
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
  </style>
</head>
<body>
<?php include('navbar.php'); ?>
  <div class="container mt-7">
    <h3 class="mb-4">Edit Employee</h3>
    <form method="POST" enctype="multipart/form-data" action="update_emp.php">
    <!-- Row 1 -->
    <div class="row">
        <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']); ?>">

        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Name</label>
                <input type="text" name="name" class="styled-input" value="<?= htmlspecialchars($employee['name']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Date of Birth</label>
                <input type="date" name="dob" class="styled-input" value="<?= htmlspecialchars($employee['dob']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Gender</label>
                <select name="gender" class="styled-input" required>
                    <option value="male" <?= $employee['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?= $employee['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?= $employee['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Phone Number</label>
                <input type="tel" name="phone" class="styled-input" value="<?= htmlspecialchars($employee['phone']); ?>" pattern="[0-9]{10}" required />
            </div>
        </div>
    </div>

    <!-- Row 2 -->
    <div class="row">
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Email</label>
                <input type="email" name="email" class="styled-input" value="<?= htmlspecialchars($employee['email']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
    <div class="input-field-container">
        <label class="input-label">Role</label>
        <select name="role" class="styled-input" required>
            <option value="" disabled <?= empty($employee['role']) ? 'selected' : ''; ?>>Select Role</option>
            <option value="care_taker" <?= $employee['role'] == 'care_taker' ? 'selected' : ''; ?>>Care Taker</option>
            <option value="nanny" <?= $employee['role'] == 'nanny' ? 'selected' : ''; ?>>Nanny</option>
            <option value="fully_trained_nurse" <?= $employee['role'] == 'fully_trained_nurse' ? 'selected' : ''; ?>>Fully Trained Nurse</option>
            <option value="semi_trained_nurse" <?= $employee['role'] == 'semi_trained_nurse' ? 'selected' : ''; ?>>Semi Trained Nurse</option>
        </select>
    </div>
</div>

        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Qualification</label>
                <select name="qualification" class="styled-input" required>
                    <option value="10th" <?= $employee['qualification'] == '10th' ? 'selected' : ''; ?>>10th</option>
                    <option value="intermediate" <?= $employee['qualification'] == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="degree" <?= $employee['qualification'] == 'degree' ? 'selected' : ''; ?>>Degree</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Experience</label>
                <select name="experience" class="styled-input" required>
                    <option value="0-1" <?= $employee['experience'] == '0-1' ? 'selected' : ''; ?>>0 to 1 year</option>
                    <option value="2-3" <?= $employee['experience'] == '2-3' ? 'selected' : ''; ?>>2 to 3 years</option>
                    <option value="4-5" <?= $employee['experience'] == '4-5' ? 'selected' : ''; ?>>4 to 5 years</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Row 3 -->
    <div class="row">
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Date of Joining</label>
                <input type="date" name="doj" class="styled-input" value="<?= htmlspecialchars($employee['doj']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Aadhar Number</label>
                <input type="text" name="aadhar" class="styled-input" value="<?= htmlspecialchars($employee['aadhar']); ?>" pattern="[0-9]{12}" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Police Verification</label>
                <select name="police_verification" class="styled-input" required>
                    <option value="verified" <?= $employee['police_verification'] == 'verified' ? 'selected' : ''; ?>>Verified</option>
                    <option value="pending" <?= $employee['police_verification'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Status</label>
                <select name="status" class="styled-input" required>
                    <option value="active" <?= $employee['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?= $employee['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Row 4 -->
    <div class="row">
        
         <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Daily Rate(8)</label>
                <input type="number" name="daily_rate8" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate8']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Daily Rate(12)</label>
                <input type="number" name="daily_rate12" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate12']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Daily Rate(24)</label>
                <input type="number" name="daily_rate24" class="styled-input" value="<?= htmlspecialchars($employee['daily_rate24']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
    <div class="input-field-container">
    <div class="row">
    <div class="col-md-10">
        <label class="input-label">Documents</label>
        
        <!-- Input for uploading a new document -->
        <input type="file" name="document" class="styled-input" />
</div>
<div class="col-md-2">
        <!-- Display the currently uploaded document -->
        <?php if (!empty($employee['document'])): ?>
            <p class="uploaded-document">
                <a href="<?= $employee['document']; ?>" target="_blank" title="View Document">
                    <i class="bi bi-file-earmark-text" style="font-size: 24px; color: #007bff;"></i>
                </a>
            </p>
        <?php else: ?>
            <p class="uploaded-document mt-2 text-muted">No document uploaded.</p>
        <?php endif; ?>
    </div>
</div>
</div>
</div>


        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Bank Name</label>
                <input type="text" name="bank_name" class="styled-input" value="<?= htmlspecialchars($employee['bank_name']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">Bank Account Number</label>
                <input type="text" name="bank_account_no" class="styled-input" value="<?= htmlspecialchars($employee['bank_account_no']); ?>" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-field-container">
                <label class="input-label">IFSC Code</label>
                <input type="text" name="ifsc_code" class="styled-input" value="<?= htmlspecialchars($employee['ifsc_code']); ?>" required />
            </div>
        </div>
    </div>

    <!-- Row 5 -->
    <div class="row">
       
       
        <div class="col-md-6">
            <div class="input-field-container">
                <label class="input-label">Address</label>
                <textarea name="address" class="styled-input" rows="3" required><?= htmlspecialchars($employee['address']); ?></textarea>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</form>

  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
