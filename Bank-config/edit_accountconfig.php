<?php


// Database connection
include('../config.php');

// Check if the id is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No ID specified for editing.";
    header("Location: manage_accountconfig.php");
    exit;
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM account_config WHERE id=$id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    $_SESSION['error'] = "No record found with ID $id.";
    header("Location: manage_accountconfig.php");
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account Configuration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .styled-input, .styled-select {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            outline: none;
            box-sizing: border-box;
            border: 1px solid #A26D2B;
            border-radius: 5px;
        }

        .styled-input:focus, .styled-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        h3 {
            color: #A26D2B;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Edit Account Configuration</h3>
    <form action="account_configdb.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">Account Name</label>
                    <input type="text" id="account_name" name="account_name" class="styled-input" 
                           value="<?php echo $data['account_name']; ?>" required />
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">Bank Account No</label>
                    <input type="text" id="bank_account_no" name="bank_account_no" class="styled-input" 
                           value="<?php echo $data['bank_account_no']; ?>" required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">IFSC Code</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" class="styled-input" 
                           value="<?php echo $data['ifsc_code']; ?>" required />
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">Bank Name</label>
                    <input type="text" id="bank_name" name="bank_name" class="styled-input" 
                           value="<?php echo $data['bank_name']; ?>" required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">Account Type</label>
                    <select id="account_type" name="account_type" class="styled-select" required>
                        <option value="" disabled>Select Account Type</option>
                        <option value="Saving" <?php echo $data['account_type'] === 'Saving' ? 'selected' : ''; ?>>Saving</option>
                        <option value="Current" <?php echo $data['account_type'] === 'Current' ? 'selected' : ''; ?>>Current</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-field-container">
                    <label class="input-label">Status</label>
                    <select id="status" name="status" class="styled-select" required>
                        <option value="Active" <?php echo $data['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="In Active" <?php echo $data['status'] === 'In Active' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="manage_accountconfig.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
