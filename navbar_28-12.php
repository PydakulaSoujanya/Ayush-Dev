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
    <!-- <div id="page-loader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div> -->

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
                <span class="navbar-dropdown-title">
                    DASHBOARD <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="dashboard">
                    <a href="../dashboard.php">Dashboard</a>
                </div>
            </div>

            <!-- Customers Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('customers')" onmouseout="toggleDropdown('customers')">
                <span class="navbar-dropdown-title">
                    CUSTOMERS <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="customers">
                    <a href="../Customer-Master/customer_table.php">Customers</a>
                </div>
            </div>

            <!-- Vendors Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('vendor')" onmouseout="toggleDropdown('vendor')">
                <span class="navbar-dropdown-title">
                    VENDORS <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="vendor">
                    <a href="../Vendor-Master/vendors.php">Vendors</a>
                </div>
            </div>

            <!-- Employees Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('employee')" onmouseout="toggleDropdown('employee')">
                <span class="navbar-dropdown-title">
                    EMPLOYEES <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="employee">
                    <a href="../Employee-Master/table.php">Employee</a>
                </div>
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

            <!-- Sales Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('sales')" onmouseout="toggleDropdown('sales')">
                <span class="navbar-dropdown-title">
                    SALES <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                </span>
                <div class="navbar-dropdown-content" id="sales">
                    <a href="../Sales/view_invoice.php">Invoice</a>
                </div>
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
                        <a href="../Expenses/employee_expenditure_table.php">Employee Claims</a>
                    <a href="../Expenses/emp_advance.php">Employee Advance Payments</a>
                    <a href="../Expenses/utility_expenses.php">Utility Expenses</a>
                    </div>
                    <a href="../Expenses/refunds_table.php">Refund</a>
                </div>
            </div>
            <div class="navbar-links">
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
                <!-- <a href="javascript:void(0);" onclick="toggleDropdown('reconcilation')">Reconcilation</a>
                <div class="submenu" id="reconcilation" style="display: none; margin-left: 15px;">
                    <a href="../Bank upload/deposit.php">Deposits</a>
                    <a href="../Bank upload/withdraw.php">Withdrawls</a>
                    <a href="../Bank upload/index.php">Upload</a>
                </div> -->
                
            </div>
        </div>
    </div>

    
    <div class="navbar-links">
        <div class="navbar-dropdown" onmouseover="toggleDropdown('reports')" onmouseout="toggleDropdown('reports')">
            <span class="navbar-dropdown-title" >
                REPORTS <span class="dropdown-arrow-icon"><i class="fas fa-chevron-down"></i></span>
            </span>
            <div class="navbar-dropdown-content" id="reports" style="display: none;">
                <a href="../Reports/account_payables.php" onclick="toggleDropdown('account-payables')">Account Payables</a>
                
                <a href="../Reports/account_recievables.php" onclick="toggleDropdown('account-recievables')">Account Recievables</a>
                
            </div>
        </div>
    </div>
        </nav>

        <div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
            <img src="../assets/images/payfiller_logo.jpg" alt="Payfiller App Logo" class="navbar-logo-img mt-3" />
        </div>

        <a href="../logout.php" class="btn btn-danger logout-button">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </header>

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

    <script>
        // Show loader on navigation
        // document.addEventListener('DOMContentLoaded', function () {
        //     const links = document.querySelectorAll('a[href]');
        //     links.forEach(link => {
        //         link.addEventListener('click', function (e) {
        //             const href = link.getAttribute('href');
        //             if (href && href !== 'javascript:void(0);') {
        //                 e.preventDefault();
        //                 document.getElementById('page-loader').style.display = 'flex';
        //                 setTimeout(() => {
        //                     window.location.href = href;
        //                 }, 500);
        //             }
        //         });
        //     });
        // });
    </script>
</body>

</html>