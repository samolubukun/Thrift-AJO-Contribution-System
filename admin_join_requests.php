<?php
session_start();

// Check if the admin is logged in (you'll need to implement proper admin authentication)
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

// Handle approve join request
if (isset($_POST['approve_join'])) {
    $request_id = $_POST['request_id'];
    $group_id = $_POST['group_id'];
    $member_id = $_POST['member_id'];

    // Check if the group has available slots
    $check_slots_query = $conn->query("SELECT current_number_of_members, max_members FROM groups WHERE id = $group_id");
    $group_data = $check_slots_query->fetch_assoc();

    if ($group_data['current_number_of_members'] < $group_data['max_members']) {
        // Add member to the group_members table
        $insert_member = $conn->prepare("INSERT INTO group_members (group_id, member_id) VALUES (?, ?)");
        $insert_member->bind_param("ii", $group_id, $member_id);
        if ($insert_member->execute()) {
            // Increment the current number of members in the groups table
            $update_group = $conn->prepare("UPDATE groups SET current_number_of_members = current_number_of_members + 1 WHERE id = ?");
            $update_group->bind_param("i", $group_id);
            $update_group->execute();
            $update_group->close();

            // Update the status of the join request to 'Approved'
            $update_request = $conn->prepare("UPDATE group_join_requests SET status = 'Approved' WHERE id = ?");
            $update_request->bind_param("i", $request_id);
            $update_request->execute();
            $update_request->close();

            $success_message = "Join request approved and member added to the group.";
        } else {
            $error_message = "Error adding member to the group: " . $conn->error;
        }
        $insert_member->close();
    } else {
        $error_message = "The selected group is full.";
    }
}

// Handle reject join request
if (isset($_POST['reject_join'])) {
    $request_id = $_POST['request_id'];

    // Update the status of the join request to 'Rejected'
    $update_request = $conn->prepare("UPDATE group_join_requests SET status = 'Rejected' WHERE id = ?");
    $update_request->bind_param("i", $request_id);
    if ($update_request->execute()) {
        $success_message = "Join request rejected.";
    } else {
        $error_message = "Error rejecting join request: " . $conn->error;
    }
    $update_request->close();
}

// Fetch all pending group join requests
$join_requests_query = $conn->query("
    SELECT
        gjr.id AS request_id,
        m.id AS member_id,
        m.first_name,
        m.last_name,
        g.id AS group_id,
        g.name AS group_name
    FROM group_join_requests gjr
    JOIN members m ON gjr.member_id = m.id
    JOIN groups g ON gjr.group_id = g.id
    WHERE gjr.status = 'Pending'
    ORDER BY gjr.request_date ASC
");
$join_requests = $join_requests_query->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Group Join Requests</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Group Join Requests</h2>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($join_requests)): ?>
            <?php foreach ($join_requests as $request): ?>
                <div class="request-card">
                    <h5 class="request-info">Member: <?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?> (ID: <?php echo htmlspecialchars($request['member_id']); ?>)</h5>
                    <p class="request-info">Wants to join group: <strong><?php echo htmlspecialchars($request['group_name']); ?></strong> (ID: <?php echo htmlspecialchars($request['group_id']); ?>)</p>
                    <div class="action-buttons">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                            <input type="hidden" name="group_id" value="<?php echo htmlspecialchars($request['group_id']); ?>">
                            <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($request['member_id']); ?>">
                            <button type="submit" class="btn btn-success btn-sm" name="approve_join">Approve</button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="reject_join">Reject</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No pending group join requests.</p>
        <?php endif; ?>

        <a href="admin.php" class="back-link">&larr; Back to Admin Dashboard</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>