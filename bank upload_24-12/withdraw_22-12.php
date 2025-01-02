<?php
// Database connection
include "../config.php";


// Fetch withdrawal data
$sql = "SELECT * FROM withdrawals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawals Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Withdrawals Data</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Transaction Date</th>
                <th>Transaction Posted Date</th>
                <!-- <th>Cheque No/Ref No</th> -->
                <th>Transaction Remarks</th>
                <th>Withdrawal Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
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
                    echo "<td>" . htmlspecialchars(number_format($row['withdrawal_amt'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['balance'], 2)) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php $conn->close(); ?>
</body>
</html>
