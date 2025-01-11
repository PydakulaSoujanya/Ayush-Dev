<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayush</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>


    <header class="navbar-header">
    <div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
    <img src="../assets/images/ayush_logo.jpg" alt="Ayush App Logo" class="navbar-ayushlogo-img mt-3" />
   <!-- Profile Icon -->
<!-- Profile Icon -->
<!-- <div class="profile-icon-container">
    <i class="bi bi-person-circle profile-icon" data-bs-toggle="modal" data-bs-target="#profileModal"></i> 
</div> -->

<!-- Modal Structure -->
<!-- <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card wide">
                    <div class="card-title text-center">Company Profile</div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
               
                        <div class="profile-picture mb-3">
                            <img src="https://via.placeholder.com/80" alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #027cc9;">
                        </div>

                        <div class="company-info text-center" style="font-size: 0.9rem; line-height: 1.5;">
                            <h6 style="margin: 0; color: #383636;"><strong>Ayush Healthcare</strong></h6>
                            <p style="margin: 5px 0; color: #383636;">Leading provider of healthcare solutions.</p>
                            <p style="margin: 5px 0; color: #383636;"><strong>Established:</strong> 2010</p>
                            <p style="margin: 5px 0; color: #383636;"><strong>Location:</strong> Bangalore, India</p>
                        </div>

           
                        <div class="contact-info text-center mt-3" style="font-size: 0.9rem; color: #383636;">
                            <p style="margin: 5px 0;"><strong>Phone:</strong> +91-9876543210</p>
                            <p style="margin: 5px 0;"><strong>Email:</strong> info@ayush.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
</div> -->


</div>




        <div class="navbar-hamburger" onclick="toggleMenu()">
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
        </div>

        <nav class="navbar-links">
            <!-- Dashboard Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('dashboard')" onmouseout="toggleDropdown('dashboard')">
                <!-- <span class="navbar-dropdown-title">
                    DASHBOARD 
                </span> -->
                <a href="../Dashboard/dashboard.php" class="navbar-dropdown-title">
                DASHBOARD 
    </a>
            </div>

            <div class="navbar-dropdown">
    <a href="../Customer-Master/customer_table.php" class="navbar-dropdown-title">
        CUSTOMERS
    </a>
</div>
            <div class="navbar-dropdown">
    <a href="../Vendor-Master/vendors.php" class="navbar-dropdown-title">
        VENDORS
    </a>
</div>

            <div class="navbar-dropdown">
    <a href="../Employee-Master/table.php" class="navbar-dropdown-title">
        EMPLOYEES
    </a>
</div>


            <!-- Services Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('service')" onmouseout="toggleDropdown('service')">
                <span class="navbar-dropdown-title">
                    SERVICES <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="service">
                    <a href="../Service-Master/view_servicemaster.php">Services</a>
                    <a href="../Capturing-Services/view_services.php">Capture Services</a>
                    
                </div>
            </div>

    
            <div class="navbar-dropdown">
    <a href="../Sales/view_invoice.php" class="navbar-dropdown-title">
        SALES
    </a>
</div>


            <!-- Expenses Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('expenses')" onmouseout="toggleDropdown('expenses')">
                <span class="navbar-dropdown-title">
                    EXPENSES <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="expenses">
                    <a href="javascript:void(0);" onclick="toggleDropdown('direct-expenses')">Direct Expenses</a>
                    <div class="submenu" id="direct-expenses" style="margin-left: 15px;">
                        <a href="../Expenses/newEP.php">Employee Payouts</a>
                        <!-- <a href="../Expenses/vendor_expenditure_table.php">Vendor Payouts</a> -->
                    </div>
                    <a href="javascript:void(0);" onclick="toggleDropdown('indirect-expenses')">Indirect Expenses</a>
                    <div class="submenu" id="indirect-expenses" style="margin-left: 15px;">
                        <a href="../Expenses/expenses_claim_table.php">Employee Claims</a>
                    <a href="../Expenses/emp_advance_table.php">Employee Advance Payments</a>
                    <a href="../Expenses/utility_expenses_table.php">Utility Expenses</a>
                    </div>
                    <a href="../Expenses/refunds_table.php">Refund</a>
                    <a href="../Expenses/expenses.php">All Expenses</a>
                </div>
            </div>
<!-- <div class="navbar-links"> -->
        <div class="navbar-dropdown" onmouseover="toggleDropdown('bank')" onmouseout="toggleDropdown('bank')">
            <span class="navbar-dropdown-title" >
                BANK <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
            </span>
            <div class="navbar-dropdown-content" id="bank" style="display: none;">
                <a href="../Bank/bank_fliling_upload.php" onclick="toggleDropdown('filing-excel')">Filing Excel</a>
                <a href="javascript:void(0);" onclick="toggleDropdown('reconcilation')">Reconcilation</a>
                    <div class="submenu" id="reconcilation" style="margin-left: 15px;">
                        <a href="../bank upload/deposit.php">Deposits</a>
                    <a href="../bank upload/withdraw.php">Withdrawls</a>
                    <a href="../bank upload/index.php">Upload</a>
                    </div>
           
                
            </div>
        </div>
    <!-- </div> -->

    
    <!-- <div class="navbar-links"> -->
        <div class="navbar-dropdown" onmouseover="toggleDropdown('reports')" onmouseout="toggleDropdown('reports')">
            <span class="navbar-dropdown-title" >
                REPORTS <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
            </span>
            <div class="navbar-dropdown-content" id="reports" style="display: none;">
                <a href="../Reports/account_payables.php" onclick="toggleDropdown('account-payables')">Account Payables</a>
            
                <a href="../Reports/account_recievables.php" onclick="toggleDropdown('account-recievables')">Account Recievables</a>
                
            </div>
        </div>
    <!-- </div> -->
        </nav>

        <div class="text-center mb-4 d-flex justify-content-left align-items-left gap-3">
            <img src="../assets/images/payfiller_logo.jpg" alt="Payfiller App Logo" class="navbar-logo-img mt-3" />
        </div>

        <!-- <a href="../logout.php" class="btn btn-danger logout-button">
            <i class="fas fa-sign-out-alt"></i>
        </a> -->
        <a href="../logout.php" class="btn btn-danger logout-button" data-tooltip="Logout the application">
    <i class="fas fa-sign-out-alt"></i>
</a>

    </header>
<style>
    .logout-button {
    position: relative;
}

.logout-button::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 1%; /* Position the tooltip above the button */
    left: 10%;
    top:20%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
}

.logout-button:hover::after {
    opacity: 1;
    visibility: visible;
}

</style>
    <script>
        // JavaScript for toggling the menu
        function toggleMenu() {
            const navbarLinks = document.querySelector('.navbar-links');
            navbarLinks.classList.toggle('open');
        }

        // JavaScript for toggling dropdowns and rotating the arrow
        function toggleDropdown(dropdownId) {
            const dropdownContent = document.getElementById(dropdownId);
            const arrowIcon = dropdownContent.previousElementSibling.querySelector('.dropdown-arrow-icon i');
            if (dropdownContent.style.display === 'block') {
                dropdownContent.style.display = 'none';
                arrowIcon.classList.remove('fa-rotate-180'); // Remove rotation class
            } else {
                dropdownContent.style.display = 'block';
                arrowIcon.classList.add('fa-rotate-180'); // Add rotation class
            }
        }
    </script>


</body>

</html>