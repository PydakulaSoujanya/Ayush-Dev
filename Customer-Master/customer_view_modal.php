<?php
if (isset($_GET['id'])) {
    include('../config.php'); // Include database configuration

    if (!isset($conn)) {
        die("Database connection is not established. Check config.php.");
    }

    $id = intval($_GET['id']);

    // Fetch customer details from customer_master_new
    $sqlCustomer = "SELECT * FROM customer_master_new WHERE id = ?";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->bind_param('i', $id);
    $stmtCustomer->execute();
    $resultCustomer = $stmtCustomer->get_result();

    // Fetch address details from customer_addresses
    $sqlAddress = "SELECT * FROM customer_addresses WHERE customer_id = ?";
    $stmtAddress = $conn->prepare($sqlAddress);
    $stmtAddress->bind_param('i', $id);
    $stmtAddress->execute();
    $resultAddress = $stmtAddress->get_result();

    if ($resultCustomer->num_rows > 0) {
        $customer = $resultCustomer->fetch_assoc();

        // Format dates
        $createdAt = date('d-m-Y H:i:s', strtotime($customer['created_at']));
        $updatedAt = date('d-m-Y H:i:s', strtotime($customer['updated_at']));

        echo "<table class='table table-bordered'>";
        echo "<tr><th>Patient Name</th><td>{$customer['patient_name']}</td></tr>";
        echo "<tr><th>Relationship</th><td>{$customer['relationship']}</td></tr>";
        echo "<tr><th>Customer Name</th><td>{$customer['customer_name']}</td></tr>";
        echo "<tr><th>Emergency Contact</th><td>{$customer['emergency_contact_number']}</td></tr>";
        echo "<tr><th>Blood Group</th><td>{$customer['blood_group']}</td></tr>";
        echo "<tr><th>Medical Conditions</th><td>{$customer['medical_conditions']}</td></tr>";
        echo "<tr><th>Email</th><td>{$customer['email']}</td></tr>";
        echo "<tr><th>Patient Age</th><td>{$customer['patient_age']}</td></tr>";
        echo "<tr><th>Gender</th><td>{$customer['gender']}</td></tr>";
        echo "<tr><th>Mobility Status</th><td>{$customer['mobility_status']}</td></tr>";
        echo "<tr><th>Discharge Summary</th><td><a href='uploads/{$customer['discharge_summary_sheet']}' target='_blank'>View File</a></td></tr>";
        echo "<tr><th>Created At</th><td>{$createdAt}</td></tr>";
        echo "<tr><th>Updated At</th><td>{$updatedAt}</td></tr>";

        // Display multiple addresses if available
        if ($resultAddress->num_rows > 0) {
            while ($address = $resultAddress->fetch_assoc()) {
                echo "<tr><th>Pincode</th><td>{$address['pincode']}</td></tr>";
                echo "<tr><th>Address Line 1</th><td>{$address['address_line1']}</td></tr>";
                echo "<tr><th>Address Line 2</th><td>{$address['address_line2']}</td></tr>";
                echo "<tr><th>Landmark</th><td>{$address['landmark']}</td></tr>";
                echo "<tr><th>City</th><td>{$address['city']}</td></tr>";
                echo "<tr><th>State</th><td>{$address['state']}</td></tr>";
                 echo "<tr><th>Created At</th><td>{$address['created_at']}</td></tr>";
                 echo "<tr><th>Updated At</th><td>{$address['updated_at']}</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No address details found.</td></tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No details found for the given ID.</p>";
    }

    $stmtCustomer->close();
    $stmtAddress->close();
    $conn->close();
} else {
    echo "<p>Invalid request.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <title>View Customer Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Trigger Button -->
    <!-- <button class="btn btn-primary" onclick="viewDetails(1)">View Details</button> -->

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <table class='table table-bordered'>
                <tr><th>Patient Name</th><td><?= ['patient_name'] ?></td></tr>
        <tr><th>Relationship with patient</th><td><?= ['relationship'] ?></td></tr>
        <tr><th>Customer Name</th><td><?= ['customer_name'] ?></td></tr>
        <tr><th>Emergency Contact Number</th><td><?= ['emergency_contact_number'] ?></td></tr>
        
        <tr><th>Blood Group</th><td><?= ['blood_group'] ?></td></tr>
        <tr><th>Medical Conditions</th><td><?= ['medical_conditions'] ?></td></tr>
        <tr><th>Email</th><td><?= ['email'] ?></td></tr>
        <tr><th>Patient Age</th><td><?= ['patient_age'] ?></td></tr>
        <tr><th>Gender</th><td><?= ['gender'] ?></td></tr>
        <!-- <tr><th>Care Requirements</th><td><?= ['care_requirements'] ?></td></tr> -->
       
        <tr><th>Mobility Status</th><td><?= ['mobility_status'] ?></td></tr>
        <tr><th>pincode</th><td><?= ['pincode'] ?></td></tr>
        <tr><th>flat, house no., building, apartment</th><td><?= ['address_line1'] ?></td></tr>
        <tr><th>Area, Street, Sector, Village</th><td><?= ['address_line2'] ?></td></tr>
        <tr><th>Landmark</th><td><?= ['landmark'] ?></td></tr>
        <tr><th>Town/City</th><td><?= ['city'] ?></td></tr>
        <tr><th>State</th><td><?= ['state'] ?></td></tr>
        </table>";
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
       function viewDetails(id) {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = "<p>Loading...</p>";

            fetch(`customer_view_modal.php?id=${id}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.error) {
                        modalContent.innerHTML = `<p>${data.error}</p>`;
                        return;
                    }

                    const { customer, addresses } = data;

                    let tableContent = `
                        <table class="table table-bordered">
                            <tr><th>Customer Name</th><td>${customer.customer_name || 'N/A'}</td></tr>
                            <tr><th>Email</th><td>${customer.email || 'N/A'}</td></tr>
                            <tr><th>Emergency Contact</th><td>${customer.emergency_contact_number || 'N/A'}</td></tr>
                            <tr><th>Gender</th><td>${customer.gender || 'N/A'}</td></tr>
                            <tr><th>Discharge Summary</th>
                                <td><a href="uploads/${customer.discharge_summary_sheet}" target="_blank">View File</a></td>
                            </tr>
                        </table>
                    `;

                    // Add addresses to the table
                    if (addresses && addresses.length > 0) {
                        addresses.forEach((address, index) => {
                            tableContent += `
                                <h5>Address ${index + 1}</h5>
                                <table class="table table-bordered">
                                    <tr><th>Address Line 1</th><td>${address.address_line1 || 'N/A'}</td></tr>
                                    <tr><th>Address Line 2</th><td>${address.address_line2 || 'N/A'}</td></tr>
                                    <tr><th>City</th><td>${address.city || 'N/A'}</td></tr>
                                    <tr><th>State</th><td>${address.state || 'N/A'}</td></tr>
                                    <tr><th>Pincode</th><td>${address.pincode || 'N/A'}</td></tr>
                                </table>
                            `;
                        });
                    } else {
                        tableContent += `<p>No address details found.</p>`;
                    }

                    modalContent.innerHTML = tableContent;

                    // Show the modal
                    const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                    viewModal.show();
                })
                .catch((error) => {
                    console.error('Error:', error);
                    modalContent.innerHTML = "<p>Failed to load details.</p>";
                });
        }
    </script>
</body>
</html>