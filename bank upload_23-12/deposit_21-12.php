<?php
// Database connection
include "../config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Deposits Data</title>
</head>
<body>
    <h1>Deposits Data</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <!-- <th>Cheque/Ref No</th> -->
                <th>Remarks</th>
                <th>Deposit Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch deposits data from the database
            $sql = "SELECT * FROM deposits";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tran_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['value_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_posted_date']) . "</td>";
                    // echo "<td>" . htmlspecialchars($row['cheque_no_ref_no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['transaction_remarks']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['deposit_amt'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['balance'], 2)) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No data available</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
