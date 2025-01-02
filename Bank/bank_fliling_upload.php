<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../index.php");
    exit;
}

// Your protected page content goes here
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <title>Vendor Table</title>
 <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include('../navbar.php'); ?>
  <div class="container mt-7">
    <div class="dataTable_card card">
      <div class="card-header">Excel Download </div>
      <div class="card-body">
      
             <h2>Download Bank Filing Excel</h2>

    <!-- Button to trigger the download -->
    <form action="download_excel.php" method="POST">
        <button type="submit" name="download" class="btn btn-primary">Download Excel</button>
    </form>
       
        
            
          </div>
        </div>
      </div>
    </div>
  </div>

  

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
