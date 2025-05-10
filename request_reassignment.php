<?php
session_start();

// Redirect if the member is not logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
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

$member_id = $_SESSION['member_id'];

// Check if the member is currently in a group
$check_current_group = $conn->query("SELECT g.name AS current_group_name, gm.group_id AS current_group_id FROM group_members gm JOIN groups g ON gm.group_id = g.id WHERE gm.member_id = $member_id");
$current_group = $check_current_group->fetch_assoc();

if (!$current_group) {
    echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>You are not currently assigned to any group.</div>";
    echo "<p><a href='member_dashboard.php' style='color: #007bff; text-decoration: underline;'>Back to Dashboard</a></p>";
    exit();
}

// Fetch available groups (where current members < max members AND not the current group)
$available_groups_query = $conn->query("SELECT id, name FROM groups WHERE current_number_of_members < max_members AND id != " . $current_group['current_group_id']);
$available_groups = $available_groups_query->fetch_all(MYSQLI_ASSOC);

$reassignment_requested = false;
$request_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_reassignment'])) {
    $reason = $_POST['reason'];
    $requested_new_group_id = $_POST['new_group_id'];

    // Fetch the name of the selected new group
    $new_group_name_query = $conn->prepare("SELECT name FROM groups WHERE id = ?");
    $new_group_name_query->bind_param("i", $requested_new_group_id);
    $new_group_name_query->execute();
    $new_group_result = $new_group_name_query->get_result();
    $new_group_data = $new_group_result->fetch_assoc();
    $new_group_name = $new_group_data ? $new_group_data['name'] : 'a selected group';
    $new_group_name_query->close();

    $full_reason = "I would like to request a reassignment to the group: " . $new_group_name . ". Reason: " . $reason;

    $insert_request = $conn->prepare("INSERT INTO reassignment_requests (member_id, current_group_id, reason) VALUES (?, ?, ?)");
    $insert_request->bind_param("iis", $member_id, $current_group['current_group_id'], $full_reason);

    if ($insert_request->execute()) {
        $reassignment_requested = true;
    } else {
        $request_error = "Error submitting reassignment request: " . $conn->error;
    }
    $insert_request->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Group Reassignment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .container { margin-top: 30px; flex-grow: 1; }
        .header { background-color: #007bff; color: white; text-align: center; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .form-container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-primary { background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 1em; }
        .btn-primary:hover { background-color: #0056b3; }
        .back-link { display: block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .success-message { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px; }
        .error-message { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Request Group Reassignment</h2>
        </div>

        <?php if ($reassignment_requested): ?>
            <div class="success-message">Your reassignment request has been submitted to the admin.</div>
            <p><a href="member_dashboard.php" class="back-link">Back to Dashboard</a></p>
        <?php elseif ($request_error): ?>
            <div class="error-message"><?php echo htmlspecialchars($request_error); ?></div>
            <p><a href="member_dashboard.php" class="back-link">Back to Dashboard</a></p>
        <?php else: ?>
            <div class="form-container">
                <p>You are currently in group: <strong><?php echo htmlspecialchars($current_group['current_group_name']); ?></strong>.</p>
                <form method="post">
                    <div class="form-group">
                        <label for="new_group_id">Select Group to Join (Optional):</label>
                        <select class="form-control" id="new_group_id" name="new_group_id">
                            <option value="">No specific group - Admin's Choice</option>
                            <?php foreach ($available_groups as $group): ?>
                                <option value="<?php echo htmlspecialchars($group['id']); ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Selecting a group will be included in your reassignment request reason.</small>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Reassignment (Optional):</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="request_reassignment">Submit Reassignment Request</button>
                </form>
            </div>
            <a href="member_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>