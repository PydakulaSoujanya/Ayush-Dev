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
    <div id="page-loader" class="loader" style="display: none;">
    <div class="spinner"></div>
</div>

    <header class="navbar-header">
        <!-- <div class="navbar-brand">
            <img src="../images/logo.jpg" alt="Ayush App Logo" class="navbar-logo-img" />
        </div> -->

        <div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
  <img src="../assets/images/ayush_logo.jpg" alt="Ayush App Logo" class="navbar-ayushlogo-img mt-3" />
  <!-- <img src="../images/payfiller_logo.jpg" alt="Payfiller App Logo" class="navbar-logo-img mt-3" /> -->
</div>

        <div class="navbar-hamburger" onclick="toggleMenu()">
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
        </div>

        <nav class="navbar-links">
            <div class="navbar-dropdown" onmouseover="toggleDropdown('dashboard')" onmouseout="toggleDropdown('dashboard')">
                <span class="navbar-dropdown-title">
                    DASHBOARD <span class="dropdown-arrow-icon">▼</span>
                </span>
                <!-- <div class="navbar-dropdown-content" id="dashboard">
                    <a href="view_invoice.php">Invoice</a>
                </div> -->
            </div>

           

            <!-- Transactions Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('customers')" onmouseout="toggleDropdown('customers')">
                <span class="navbar-dropdown-title">
                    CUSTOMERS <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="customers">
                    <a href="../Customer-Master/customer_table.php">Customers</a>
                    <!-- <a href="/estimates">Estimate</a>
                    <a href="/stockEntryTable">Stock Entry</a>
                    <a href="/paymentstable">Payments</a>
                    <a href="/receiptstable">Receipts</a>
                    <a href="/purchasetable">Purchase</a>
                    <a href="/repairstable">Repairs</a>
                    <a href="/urd_purchase">URD Purchase</a> -->
                </div>
            </div>

            <!-- Reports Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('vendor')" onmouseout="toggleDropdown('vendor')">
                <span class="navbar-dropdown-title">
                    VENDORS <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="vendor">
                    <a href="../Vendor-Master/vendors.php">Vendors</a>
                    <!-- <a href="Vendor_Billing_and_Payouts.php">Vendor Billing</a> -->
                    <!-- <a href="/estimateReport">Estimate Report</a>
                    <a href="/purchaseReport">Purchase Report</a>
                    <a href="/repairsReport">Repairs Report</a>
                    <a href="/urdPurchaseReport">URD Purchase Report</a>
                    <a href="/stockReport">Stock Report</a>
                    <a href="/barcodeprinting">Barcode Printing Report</a> -->
                    
                </div>
            </div>
            <div class="navbar-dropdown" onmouseover="toggleDropdown('employee')" onmouseout="toggleDropdown('employee')">
                <span class="navbar-dropdown-title">
                    EMPLOYEES <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="employee">
                    <a href="../Employee-Master/table.php">Employee</a>
                    <!-- <a href="/estimateReport">Estimate Report</a>
                    <a href="/purchaseReport">Purchase Report</a>
                    <a href="/repairsReport">Repairs Report</a>
                    <a href="/urdPurchaseReport">URD Purchase Report</a>
                    <a href="/stockReport">Stock Report</a>
                    <a href="/barcodeprinting">Barcode Printing Report</a>
                    <a href="/cashReport">Cash Report</a> -->
                </div>
            </div>
            <div class="navbar-dropdown" onmouseover="toggleDropdown('service')" onmouseout="toggleDropdown('service')">
                <span class="navbar-dropdown-title">
                    SERVICES <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="service">
                    <a href="../Service-Master/view_servicemaster.php">Services</a>
                    <a href="../Capturing-Services/view_services.php">Capture Services</a>
                    <!-- <a href="/estimateReport">Estimate Report</a>
                    <a href="/purchaseReport">Purchase Report</a>
                    <a href="/repairsReport">Repairs Report</a>
                    <a href="/urdPurchaseReport">URD Purchase Report</a>
                    <a href="/stockReport">Stock Report</a>
                    <a href="/barcodeprinting">Barcode Printing Report</a>
                    <a href="/cashReport">Cash Report</a> -->
                </div>
            </div>
            <div class="navbar-dropdown" onmouseover="toggleDropdown('sales')" onmouseout="toggleDropdown('sales')">
                <span class="navbar-dropdown-title">
                    SALES <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="sales">
                    <a href="../Sales/view_invoice.php">Invoice</a>
                    <!-- <a href="/estimateReport">Estimate Report</a>
                    <a href="/purchaseReport">Purchase Report</a>
                    <a href="/repairsReport">Repairs Report</a>
                    <a href="/urdPurchaseReport">URD Purchase Report</a>
                    <a href="/stockReport">Stock Report</a>
                    <a href="/barcodeprinting">Barcode Printing Report</a>
                    <a href="/cashReport">Cash Report</a> -->
                </div>
            </div>

            
    <div class="navbar-links">
        <div class="navbar-dropdown" onmouseover="toggleDropdown('expenses')" onmouseout="toggleDropdown('expenses')">
            <span class="navbar-dropdown-title" >
                EXPENSES <span class="dropdown-arrow-icon">▼</span>
            </span>
            <div class="navbar-dropdown-content" id="expenses" style="display: none;">
                <a href="javascript:void(0);" onclick="toggleDropdown('direct-expenses')">Direct Expenses</a>
                <div class="submenu" id="direct-expenses" style="display: none; margin-left: 15px;">
                    <a href="../Expenses/employee_payouts_table.php">Employee Payouts</a>
                    <a href="#">Vendor Payouts</a>
                </div>
                <a href="javascript:void(0);" onclick="toggleDropdown('indirect-expenses')">Indirect Expenses</a>
                <div class="submenu" id="indirect-expenses" style="display: none; margin-left: 15px;">
                    <a href="../Expenses/expenses_claim_form.php">Employee Claims</a>
                    <a href="../Expenses/vendor_expenditure_table.php">Utility Expenses</a>
                </div>
                <!-- <a href="#">Employee Claims</a>
                <a href="#">Vendor Payouts</a>
                <a href="#">Employee Payouts</a> -->
                <a href="../Expenses/refunds_table.php">Refund</a>
            </div>
        </div>
    </div>

    <div class="navbar-dropdown" onmouseover="toggleDropdown('bank')" onmouseout="toggleDropdown('bank')">
                <span class="navbar-dropdown-title">
                    BANK <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="bank">
                    <a href="#">Filing Excel</a>
                    <a href="javascript:void(0);" onclick="toggleDropdown('reconcilation')">Reconcilation</a>

                    <!-- <a href="#"></a> -->
                    <div class="submenu" id="reconcilation" style="display: none; margin-left: 15px;">
                    <a href="">Deposits</a>
                    <a href="">Withdrawls</a>
                    <a href="">Upload</a>
                </div>
                </div>
            </div>

    <div class="navbar-links">
        <div class="navbar-dropdown" onmouseover="toggleDropdown('reports')" onmouseout="toggleDropdown('reports')">
            <span class="navbar-dropdown-title" >
                REPORTS <span class="dropdown-arrow-icon">▼</span>
            </span>
            <div class="navbar-dropdown-content" id="reports" style="display: none;">
                <a href="javascript:void(0);" onclick="toggleDropdown('account-payables')">Account Payables</a>
                <!-- <div class="submenu" id="direct-expenses" style="display: none; margin-left: 15px;">
                    <a href="../Expenses/employee_payouts_table.php">Employee Payouts</a>
                    <a href="../Expenses/vendor_expenditure_table.php">Vendor Payouts</a>
                </div> -->
                <a href="../Reports/account_recievables.php" onclick="toggleDropdown('account-recievables')">Account Recievables</a>
                <!-- <div class="submenu" id="indirect-expenses" style="display: none; margin-left: 15px;">
                    <a href="../Expenses/expenses_claim_form.php">Employee Claims</a>
                    <a href="">Utility Expenses</a>
                </div> -->
                <!-- <a href="#">Employee Claims</a>
                <a href="#">Vendor Payouts</a>
                <a href="#">Employee Payouts</a> -->
                <!-- <a href="../Expenses/refunds_table.php">Refund</a> -->
            </div>
        </div>
    </div>

    <!-- <a href="logout.php" class="btn btn-danger">Logout</a> -->

    <!-- <a href="../logout.php" class="btn btn-secondary">
    <i class="fas fa-sign-out-alt"></i>
</a> -->



    <div class="text-center mb-4 d-flex justify-content-center align-items-center gap-3">
  <!-- <img src="../images/ayush_logo.jpg" alt="Ayush App Logo" class="navbar-logo-img mt-3" /> -->
  <img src="../assets/images/payfiller_logo.jpg" alt="Payfiller App Logo" class="navbar-logo-img mt-3" />
</div>



            
            <!-- <div class="navbar-dropdown" onmouseover="toggleDropdown('expenses')" onmouseout="toggleDropdown('expenses')">
                <span class="navbar-dropdown-title">
                    EXPENSES <span class="dropdown-arrow-icon">▼</span>
                </span>
                <div class="navbar-dropdown-content" id="expenses">
                    <a href="">Direct Expenses</a>
    <div class="submenu">
        <a href="../Expenses/expenses_claim_form.php">Employee claims</a>
        <a href="../Expenses/Vendor_Billing_and_Payouts.php">Vendor Payouts</a>
    </div>
                    <a href="../Expenses/expenses_claim_form.php">Indirect Expenses</a>
                    <a href="../Expenses/expenses_claim_form.php">Employee claims</a>
                    <a href="../Expenses/Vendor_Billing_and_Payouts.php">Vendor Payouts</a>
                    <a href="">Employee Payouts</a>
                    <a href="">Refund</a>
                    
                </div>
            </div> -->
        </nav>
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
            const arrowIcon = dropdownContent.previousElementSibling.querySelector('.dropdown-arrow-icon');
            if (dropdownContent.style.display === 'block') {
                dropdownContent.style.display = 'none';
                arrowIcon.style.transform = 'rotate(0deg)';
            } else {
                dropdownContent.style.display = 'block';
                arrowIcon.style.transform = 'rotate(180deg)';
            }
        }
    </script>

    <!-- <script>
    // Show loader on navigation
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('a[href]');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                const href = link.getAttribute('href');

                // Ensure the link has an actual destination
                if (href && href !== 'javascript:void(0);') {
                    e.preventDefault();
                    
                    // Show the loader
                    document.getElementById('page-loader').style.display = 'flex';
                    
                    // Simulate navigation (for demo) or perform actual navigation
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500); // Adjust delay if needed
                }
            });
        });
    });
</script> -->

</body>

</html>
