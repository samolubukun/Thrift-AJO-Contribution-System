<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login page
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thrift_management";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle reject reassignment request
if (isset($_POST['reject_reassignment'])) {
    $request_id = $_POST['request_id'];

    // Update the status of the reassignment request to 'Rejected'
    $update_request = $conn->prepare("UPDATE reassignment_requests SET status = 'Rejected' WHERE id = ?");
    $update_request->bind_param("i", $request_id);
    if ($update_request->execute()) {
        $success_message = "Reassignment request rejected.";
    } else {
        $error_message = "Error rejecting reassignment request: " . $conn->error;
    }
    $update_request->close();
}

// Fetch all pending reassignment requests
$reassignment_requests_query = $conn->query("
    SELECT
        rr.id AS request_id,
        m.id AS member_id,
        m.first_name,
        m.last_name,
        og.id AS current_group_id,
        og.name AS current_group_name,
        rr.reason,
        rr.request_date
    FROM reassignment_requests rr
    JOIN members m ON rr.member_id = m.id
    JOIN groups og ON rr.current_group_id = og.id
    WHERE rr.status = 'Pending'
    ORDER BY rr.request_date ASC
");
$reassignment_requests = $reassignment_requests_query->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reassignment Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { margin-top: 20px; }
        .header { background-color: #343a40; color: white; padding: 15px; text-align: center; margin-bottom: 20px; }
        .request-card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 15px; margin-bottom: 15px; }
        .request-info { margin-bottom: 10px; }
        .action-buttons button { margin-right: 10px; }
        .success-message { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px; }
        .error-message { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; border-radius: 5px; }
        .back-link { display: block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .approve-link { display: inline; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reassignment Requests</h2>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message "><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($reassignment_requests)): ?>
            <?php foreach ($reassignment_requests as $request): ?>
                <div class="request-card">
                    <h5 class="request-info">Member: <?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?> (ID: <?php echo htmlspecialchars($request['member_id']); ?>)</h5>
                    <p class="request-info">Current Group: <strong><?php echo htmlspecialchars($request['current_group_name']); ?></strong> (ID: <?php echo htmlspecialchars($request['current_group_id']); ?>)</p>
                    <?php if (!empty($request['reason'])): ?>
                        <p class="request-info">Reason: <?php echo htmlspecialchars($request['reason']); ?></p>
                    <?php endif; ?>
                    <p class="request-info">Requested On: <?php echo htmlspecialchars($request['request_date']); ?></p>
                    <div class="action-buttons">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="reject_reassignment">Reject</button>
                        </form>
                        <a href="mini_admin_groups.php?member_id=<?php echo htmlspecialchars($request['member_id']); ?>&current_group_id=<?php echo htmlspecialchars($request['current_group_id']); ?>&request_id=<?php echo htmlspecialchars($request['request_id']); ?>" class="btn btn-primary btn-sm approve-link">Approve & Reassign</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No pending reassignment requests.</p>
        <?php endif; ?>

        <a href="admin.php" class="back-link">&larr; Back to Admin Dashboard</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>