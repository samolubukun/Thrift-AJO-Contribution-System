<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$member_id = $_SESSION['member_id'];
$member_query = $conn->prepare("SELECT m.*, gm.group_id, g.amount AS group_contribution_amount FROM members m JOIN group_members gm ON m.id = gm.member_id JOIN groups g ON gm.group_id = g.id WHERE m.id = ?");
$member_query->bind_param("i", $member_id);
$member_query->execute();
$member_result = $member_query->get_result();
$member = $member_result->fetch_assoc();
$member_query->close(); // Close the first prepared statement

if (!$member) {
    // Handle case where member or group info is not found
    die("Error: Could not retrieve member or group information.");
}

$currentDate = date("Y-m-d");
$lastDayOfMonth = date("Y-m-t");
$firstDayOfPaymentWindow = date("Y-m-", strtotime($lastDayOfMonth)) . (date("t") - 6);
$withinPaymentWindow = ($currentDate >= $firstDayOfPaymentWindow && $currentDate <= $lastDayOfMonth);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($withinPaymentWindow) {
        $contribution_status = "Contributed";
        $amount = $member['group_contribution_amount']; // Use the group's defined contribution amount
        $date_of_contribution = date("Y-m-d H:i:s");
        $member_name = $member['first_name'] . ' ' . $member['last_name']; // Store member's full name in a variable

        $stmt = $conn->prepare("INSERT INTO contributions (member_id, member_name, amount, date_of_contribution) VALUES (?, ?, ?, ?)");
        // Pass the variable $member_name by value
        $stmt->bind_param("isds", $member_id, $member_name, $amount, $date_of_contribution);
        if ($stmt->execute()) {
            $conn->query("UPDATE members SET contribution_status = '$contribution_status' WHERE id = $member_id");
            $message = "Contribution of ₦" . number_format($amount, 2) . " made successfully!";
        } else {
            $message = "Error making contribution: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "CANNOT CONTRIBUTE OUTSIDE OF PAYMENT WINDOW";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Contribution</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css"> </head>
<body>
    <header class="bg-warning text-white text-center py-4">
        <h1>Make Contribution</h1>
    </header>
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Contribution Form</h2>
                <p>Status: <?php echo $withinPaymentWindow ? "Within Payment Window" : "Outside Payment Window"; ?></p>
                <p>Your Group's Contribution Amount: <strong>₦<?php echo number_format($member['group_contribution_amount'], 2); ?></strong></p>
                <form action="contribute.php" method="POST">
                    <input type="hidden" name="amount" value="<?php echo $member['group_contribution_amount']; ?>">
                    <div class="form-group">
                        <label for="amount" class="font-weight-bold">Contribution Amount:</label>
                        <input type="text" class="form-control" id="amount" value="₦<?php echo number_format($member['group_contribution_amount'], 2); ?>" readonly>
                        <small class="form-text text-muted">This is the required contribution amount for your group.</small>
                    </div>
                    <?php if ($withinPaymentWindow): ?>
                        <button type="submit" class="btn btn-warning btn-block">Submit Contribution of ₦<?php echo number_format($member['group_contribution_amount'], 2); ?></button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-warning btn-block" disabled>Submit Contribution</button>
                    <?php endif; ?>
                </form>
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo (strpos($message, 'Error') !== false) ? 'danger' : 'success'; ?> mt-3" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="member_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-light text-center py-4 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Thrift Management System. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>