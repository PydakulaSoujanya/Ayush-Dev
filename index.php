

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    .hidden {
      display: none;
    }

    .login-container h2 {
      color: #6c757d;
    }
    .styled-input {
  width: 100%;
  padding: 10px;
  font-size: 14px;
}

.input-label {
  margin-bottom: 5px;
  display: block;
  font-weight: bold;
}

.toggle-password {
  font-size: 18px;
  color: #999;
}
.toggle-password:hover {
  color: #333;
}
#submit-button {
  background-color: #A26D2B;
  width: 150px;
  text-align: center;
  padding: 10px 20px; /* Adjust as needed */
  border: none; /* Remove border */
  margin: 0 auto; /* Center horizontally */
  display: block; /* Required for margin: auto to work */
  color: white; /* Optional: Ensures text is visible */
  cursor: pointer; /* Optional: Adds a pointer on hover */
  border-radius: 0%;
}
.forgot-class a {
  color: #6c757d; /* Sets the desired color */
  text-decoration: none; /* Optional: Removes underline */
}

.forgot-class a:hover {
  text-decoration: underline; /* Optional: Adds underline on hover */
}


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
       

        <div class="col-md-12">
        <div class="form-group custom-form-group">
          <label for="email" class="custom-label">Email address</label>
          <input type="email" class="form-control custom-input" id="email" name="email" placeholder="Enter your email" required />
        </div>
      </div>

      <div class="col-md-12 mt-2">
  <div class="form-group custom-form-group" style="position: relative;">
    <label for="password" class="custom-label">Password</label>
    <input 
      type="password" 
      class="form-control custom-input" 
      id="password" 
      name="password" 
      placeholder="Enter your password" 
      required 
      style="padding-right: 40px;"
    />
    <span 
      class="toggle-password" 
      onclick="togglePasswordVisibility()" 
      style="position: absolute; right: 10px; top: 70%; transform: translateY(-50%); cursor: pointer;"
    >
      <i class="fa fa-eye" id="password-icon"></i>
    </span>
  </div>
</div>


      

        <!-- <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
            <label class="form-check-label" for="rememberMe">Remember Me</label>
        </div> -->
        <div class="login-footer mt-3">
    <p class="forgot-class"><a href="forgot_password.php" >Forgot Password?</a></p>
</div>

<div class="submit-btn-container text-center">
        <button type="submit" class="btn btn-secondary submit-btn p-2 w-50">Login</button>
</div>
   
      </form>

    <!-- <div class="login-footer mt-3">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div> -->
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
  function togglePasswordVisibility() {
  const passwordField = document.getElementById('password');
  const icon = document.getElementById('password-icon');
  
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    passwordField.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

</script>
</body>
</html>
