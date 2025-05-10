<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentMonthStart = date("Y-m-01");
$currentMonthEnd = date("Y-m-t");

$report_query = "
    SELECT
        m.id,
        CONCAT(m.first_name, ' ', m.last_name) AS full_name,
        m.email,
        m.contribution_status,
        m.rotation_order,
        g.amount AS contribution_plan,  -- fetch group amount instead of member's contribution_plan
        (SELECT date_of_contribution FROM contributions WHERE member_id = m.id ORDER BY date_of_contribution DESC LIMIT 1) AS last_contribution_date,
        g.name AS group_name,
        fd.amount AS disbursed_amount,
        fd.distribution_date AS disbursement_date
    FROM members m
    LEFT JOIN group_members gm ON m.id = gm.member_id
    LEFT JOIN groups g ON gm.group_id = g.id
    LEFT JOIN fund_distribution fd ON m.id = fd.member_id
    WHERE (fd.distribution_date >= '$currentMonthStart' AND fd.distribution_date <= '$currentMonthEnd') 
       OR fd.distribution_date IS NULL
    ORDER BY g.name, m.last_name, m.first_name;
";


$report_result = $conn->query($report_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Admin Report</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Print-specific CSS */
        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            #report {
                width: 100%;
                overflow-x: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: auto;
            }

            th, td {
                padding: 8px;
                text-align: left;
                font-size: 12px;
            }

            .btn {
                display: none;
            }

            h1, h2 {
                text-align: center;
                font-size: 18px;
            }

            .container {
                width: 100%;
                padding: 10px;
            }
        }

        /* Standard page styles */
        .table {
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="bg-info text-white py-4">
        <h1 class="text-center">Detailed Admin Report</h1>
    </header>
    <main class="container mt-5">
        <section id="report">
            <h2>Contribution and Fund Disbursement Report for <?php echo date("F Y"); ?></h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Member ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Group</th>
                            <th>Contribution Plan</th>
                            <th>Contribution Status</th>
                            <th>Last Contribution Date</th>
                            <th>Disbursed Amount (<?php echo date("F"); ?>)</th>
                            <th>Disbursement Date</th>
                            <th>Rotation Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($report_result->num_rows > 0) {
                            while ($row = $report_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['group_name'] ?: 'N/A') . "</td>";
                                echo "<td>₦" . number_format(htmlspecialchars($row['contribution_plan']), 2) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contribution_status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['last_contribution_date'] ?: 'N/A') . "</td>";
                                echo "<td>";
                                if ($row['disbursed_amount'] !== null) {
                                    echo "₦" . number_format(htmlspecialchars($row['disbursed_amount']), 2);
                                } else {
                                    echo "N/A";
                                }
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row['disbursement_date'] ?: 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['rotation_order']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center'>No member data available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- Print Button -->
        <div class="mt-4">
            <button class="btn btn-primary" onclick="printReport()">Print Report</button>
            <a href="admin.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </main>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript function to print the report
        function printReport() {
            var printContents = document.getElementById('report').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>
