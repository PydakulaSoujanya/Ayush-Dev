<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        
    </style>
</head>
<body>

<div class="login-container">
<div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
  <img src="assets/images/ayush_logo.jpg" alt="Ayush App Logo" class="navbar-logo-img" />
  <img src="assets/images/payfiller_logo.jpg" alt="Payfiller App Logo" class="navbar-logo-img" />
</div>

    <h2>Login</h2>
    
    <div class="alert alert-danger" id="login-alert">
        Invalid login credentials!
    </div>

    <form id="login-form" method="post" action="logindb.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <!-- <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
            <label class="form-check-label" for="rememberMe">Remember Me</label>
        </div> -->
        <div class="login-footer mt-3">
    <p><a href="forgot_password.php">Forgot Password?</a></p>
</div>


        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <!-- <div class="login-footer mt-3">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div> -->
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
