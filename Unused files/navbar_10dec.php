<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayush</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <header class="navbar-header">
        <!-- <div class="navbar-brand">Jewellery App</div> -->
        <div class="navbar-brand">
            <img src="images/logo.jpg" alt="Ayush App Logo" class="navbar-logo-img" />
        </div>


        <div class="navbar-hamburger" onclick="toggleMenu()">
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
            <div class="navbar-bar"></div>
        </div>

        <nav class="navbar-links">
            <div class="navbar-dropdown" onmouseover="toggleDropdown('utility')" onmouseout="toggleDropdown('utility')">
                <span class="navbar-dropdown-title">
                    DASHBOARD <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="view_services.php">Capture Service</a>
                </div>
            </div>
            <!-- Masters Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('masters')" onmouseout="toggleDropdown('masters')">
                <span class="navbar-dropdown-title">
                    CONTACTS <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="/customerstable">Customer Master</a>
                    <a href="/suppliertable">Supplier Master</a>
                    <a href="/itemmastertable">Product</a>
                    <a href="/purity">Purity</a>
                    <a href="/accountstable">Accounts</a>
                </div>
            </div>

            <!-- Transactions Dropdown -->
            <!-- <div class="navbar-dropdown" onmouseover="toggleDropdown('transactions')"
                onmouseout="toggleDropdown('transactions')">
                <span class="navbar-dropdown-title">
                    CUSTOMERS <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="/sales">Sales</a>
                    <a href="/estimates">Estimate</a>
                    <a href="/stockEntryTable">Stock Entry</a>
                    <a href="/paymentstable">Payments</a>
                    <a href="/receiptstable">Receipts</a>
                    <a href="/purchasetable">Purchase</a>
                    <a href="/repairstable">Repairs</a>
                    <a href="/urd_purchase">URD Purchase</a>
                </div>
            </div> -->

            <div class="navbar-item">
    <a href="customer_table.php" class="navbar-link">CUSTOMERS</a>
</div>

            <!-- Reports Dropdown -->
            <!-- <div class="navbar-dropdown" onmouseover="toggleDropdown('reports')" onmouseout="toggleDropdown('reports')">
                <span class="navbar-dropdown-title">
                    VENDORS <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="/salesReport">Sales Report</a>
                    <a href="/estimateReport">Estimate Report</a>
                    <a href="/purchaseReport">Purchase Report</a>
                    <a href="/repairsReport">Repairs Report</a>
                    <a href="/urdPurchaseReport">URD Purchase Report</a>
                    <a href="/stockReport">Stock Report</a>
                    <a href="/barcodeprinting">Barcode Printing Report</a>
                    <a href="/cashReport">Cash Report</a>
                </div>
            </div> -->
            <div class="navbar-item">
    <a href="vendors.php" class="navbar-link">VENDORS</a>
</div>
            <!-- <div class="navbar-dropdown" onmouseover="toggleDropdown('utility')" onmouseout="toggleDropdown('utility')">
                <span class="navbar-dropdown-title">
                    EMPLOYEES <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="emp-form.php">Employees</a>
                </div>
            </div> -->
            <div class="navbar-item">
    <a href="manage_employee.php" class="navbar-link">EMPLOYEES</a>
</div>
            <div class="navbar-dropdown" onmouseover="toggleDropdown('utility')" onmouseout="toggleDropdown('utility')">
                <span class="navbar-dropdown-title">
                    PAYMENTS <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="/invoice">Invoice</a>
                </div>
            </div>

            <!-- Utility/Settings Dropdown -->
            <div class="navbar-dropdown" onmouseover="toggleDropdown('utility')" onmouseout="toggleDropdown('utility')">
                <span class="navbar-dropdown-title">
                    REPORTS <i class="fas fa-chevron-down dropdown-arrow-icon"></i>
                </span>
                <div class="navbar-dropdown-content">
                    <a href="/invoice">Invoice</a>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // JavaScript for toggling the menu
        function toggleMenu() {
            const navbarLinks = document.querySelector('.navbar-links');
            navbarLinks.classList.toggle('open');
        }

        // JavaScript for dropdown toggles
        function toggleDropdown(dropdown) {
            const dropdownContent = document.querySelector(`.navbar-dropdown-content`);
            dropdownContent.style.display =
                dropdownContent.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>

</html>