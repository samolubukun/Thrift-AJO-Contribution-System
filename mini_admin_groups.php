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

// Retrieve member_id and current_group_id from URL parameters
$member_id_to_reassign = isset($_GET['member_id']) ? $_GET['member_id'] : null;
$current_group_id = isset($_GET['current_group_id']) ? $_GET['current_group_id'] : null;

// Fetch member details
$member_details_query = $conn->prepare("SELECT id, first_name, last_name FROM members WHERE id = ?");
$member_details_query->bind_param("i", $member_id_to_reassign);
$member_details_query->execute();
$member_result = $member_details_query->get_result();
$member = $member_result->fetch_assoc();
$member_details_query->close();

if (!$member) {
    echo "Member not found.";
    exit();
}

// Handle reassign member form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reassign_member'])) {
    $member_id = $_POST['member_id'];
    $new_group_id = $_POST['new_group_id'];

    // Start transaction
    $conn->begin_transaction();

    // Remove member from the current group
    $delete_member_stmt = $conn->prepare("DELETE FROM group_members WHERE member_id = ? AND group_id = ?");
    $delete_member_stmt->bind_param("ii", $member_id, $current_group_id);
    if ($delete_member_stmt->execute()) {
        // Decrement member count in the old group
        $update_old_group_stmt = $conn->prepare("UPDATE groups SET current_number_of_members = current_number_of_members - 1 WHERE id = ?");
        $update_old_group_stmt->bind_param("i", $current_group_id);
        $update_old_group_stmt->execute();
        $update_old_group_stmt->close();

        // Check for slots in the new group
        $check_slots_query = $conn->query("SELECT current_number_of_members, max_members FROM groups WHERE id = $new_group_id");
        $new_group_data = $check_slots_query->fetch_assoc();

        if ($new_group_data['current_number_of_members'] < $new_group_data['max_members']) {
            // Add member to the new group
            $insert_member_stmt = $conn->prepare("INSERT INTO group_members (group_id, member_id) VALUES (?, ?)");
            $insert_member_stmt->bind_param("ii", $new_group_id, $member_id);
            if ($insert_member_stmt->execute()) {
                // Increment member count in the new group
                $update_new_group_stmt = $conn->prepare("UPDATE groups SET current_number_of_members = current_number_of_members + 1 WHERE id = ?");
                $update_new_group_stmt->bind_param("i", $new_group_id);
                $update_new_group_stmt->execute();
                $update_new_group_stmt->close();

                $conn->commit();
                $success_message = "Member reassigned successfully.";

                // Optionally, update the reassignment request status to 'Approved'
                $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : null;
                if ($request_id) {
                    $update_request_stmt = $conn->prepare("UPDATE reassignment_requests SET status = 'Approved' WHERE id = ?");
                    $update_request_stmt->bind_param("i", $request_id);
                    $update_request_stmt->execute();
                    $update_request_stmt->close();
                }

                // Redirect back to the reassignment requests page
                header("Location: admin_reassignment_requests.php?reassignment_approved=1");
                exit();

            } else {
                $conn->rollback();
                $error_message = "Error adding member to the new group: " . $conn->error;
            }
            $insert_member_stmt->close();
        } else {
            $conn->rollback();
            $error_message = "The selected new group is full.";
        }
    } else {
        $conn->rollback();
        $error_message = "Error removing member from the current group: " . $conn->error;
    }
    $delete_member_stmt->close();
}

// Fetch all available groups for the dropdown
$all_groups_query = $conn->query("SELECT id, name FROM groups ORDER BY name");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reassign Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select.form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button.btn-primary { background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button.btn-primary:hover { background-color: #0056b3; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { display: block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reassign Member</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <h3>Reassigning: <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?> (ID: <?php echo htmlspecialchars($member['id']); ?>)</h3>

        <form method="post">
            <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['id']); ?>">

            <div class="form-group">
                <label for="new_group_id">Select New Group:</label>
                <select class="form-control" id="new_group_id" name="new_group_id" required>
                    <option value="">Select New Group</option>
                    <?php
                    if ($all_groups_query) {
                        while ($group = $all_groups_query->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($group['id']) . "'" . ($group['id'] == $current_group_id ? ' disabled' : '') . ">" . htmlspecialchars($group['name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="reassign_member">Reassign Member</button>
        </form>

        <a href="admin_reassignment_requests.php" class="back-link">&larr; Back to Reassignment Requests</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>