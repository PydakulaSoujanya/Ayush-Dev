<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayush</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>

<body>


    <header class="navbar-header">
        <div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
            <img src="../assets/images/ayush_logo.jpg" alt="Ayush App Logo" class="navbar-ayushlogo-img mt-3" />
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
                        <a href="../Expenses/employee_payouts_table.php">Employee Payouts</a>
                        <a href="../Expenses/vendor_expenditure_table.php">Vendor Payouts</a>
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
                    <a href="../Bank-config/manage_accountconfig.php" onclick="toggleDropdown('config')">Configuration</a>

                
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