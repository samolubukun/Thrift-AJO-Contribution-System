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

// Define the variables for checking the payment window
$currentDate = date("Y-m-d");
$lastDayOfMonth = date("Y-m-t");

$message = "";
$selected_group = null;
$next_member_details = null; // Changed variable name for clarity
$total_funds = 0;

// Get all groups for selection
$groups_result = $conn->query("SELECT id, name, amount FROM groups ORDER BY name");
$groups = [];
while ($row = $groups_result->fetch_assoc()) {
    $groups[] = $row;
}

// If a group is selected or form submitted
if (isset($_GET['group_id']) || (isset($_POST['group_id']) && $_SERVER["REQUEST_METHOD"] == "POST")) {
    $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : $_POST['group_id'];
    $selected_group = null;

    // Get group info
    $group_query = $conn->prepare("SELECT id, name, amount FROM groups WHERE id = ?");
    $group_query->bind_param("i", $group_id);
    $group_query->execute();
    $group_result = $group_query->get_result();

    if ($group_result->num_rows > 0) {
        $selected_group = $group_result->fetch_assoc();

        // Get total funds for this group
        $funds_query = $conn->prepare("
            SELECT SUM(m.contribution_plan) as total
            FROM members m
            JOIN group_members gm ON m.id = gm.member_id
            WHERE gm.group_id = ? AND m.contribution_status = 'Contributed'
        ");
        $funds_query->bind_param("i", $group_id);
        $funds_query->execute();
        $funds_result = $funds_query->get_result();
        $funds_row = $funds_result->fetch_assoc();
        $total_funds = $funds_row['total'] ?: 0;

        // Get next member in rotation along with their bank details
        $next_member_query = $conn->prepare("
            SELECT
                m.id,
                CONCAT(m.first_name, ' ', m.last_name) as full_name,
                m.rotation_order,
                m.bank_name,
                m.bank_account_number,
                m.bank_code
            FROM members m
            JOIN group_members gm ON m.id = gm.member_id
            WHERE gm.group_id = ?
            ORDER BY m.rotation_order ASC
            LIMIT 1
        ");
        $next_member_query->bind_param("i", $group_id);
        $next_member_query->execute();
        $next_member_result = $next_member_query->get_result();

        if ($next_member_result->num_rows > 0) {
            $next_member_details = $next_member_result->fetch_assoc();
        }
    }
}

// Process distribution
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['distribute']) && isset($_POST['group_id'])) {
    $group_id = $_POST['group_id'];

    // Get actual total contributions for this group
    $contrib_query = $conn->prepare("
        SELECT SUM(m.contribution_plan) as total, COUNT(m.id) as count
        FROM members m
        JOIN group_members gm ON m.id = gm.member_id
        WHERE gm.group_id = ? AND m.contribution_status = 'Contributed'
    ");
    $contrib_query->bind_param("i", $group_id);
    $contrib_query->execute();
    $contrib_result = $contrib_query->get_result();
    $contrib_data = $contrib_result->fetch_assoc();
    $actual_total = $contrib_data['total'] ?: 0;
    $contributor_count = $contrib_data['count'] ?: 0;

    // Get next member in rotation along with their bank details
    $next_member_query = $conn->prepare("
        SELECT
            m.id,
            CONCAT(m.first_name, ' ', m.last_name) as full_name,
            m.rotation_order,
            m.bank_name,
            m.bank_account_number,
            m.bank_code
        FROM members m
        JOIN group_members gm ON m.id = gm.member_id
        WHERE gm.group_id = ?
        ORDER BY m.rotation_order ASC
        LIMIT 1
    ");
    $next_member_query->bind_param("i", $group_id);
    $next_member_query->execute();
    $next_member_result = $next_member_query->get_result();

    if ($next_member_result->num_rows > 0) {
        $next_member = $next_member_result->fetch_assoc();
        $next_member_id = $next_member['id'];
        $next_member_name = $next_member['full_name'];

        if ($actual_total > 0) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Reset contribution status for members in this group who contributed
                $reset_query = $conn->prepare("
                    UPDATE members m
                    JOIN group_members gm ON m.id = gm.member_id
                    SET m.contribution_status = 'Pending'
                    WHERE gm.group_id = ? AND m.contribution_status = 'Contributed'
                ");
                $reset_query->bind_param("i", $group_id);
                $reset_query->execute();

                // Record the fund distribution
                $dist_query = $conn->prepare("
                    INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date)
                    VALUES (?, ?, ?, CURDATE())
                ");
                $dist_query->bind_param("iid", $group_id, $next_member_id, $actual_total);
                $dist_query->execute();

                // Update rotation orders for this group only
                // First, get the maximum rotation order in the group
                $max_order_query = $conn->prepare("
                    SELECT MAX(m.rotation_order) as max_order
                    FROM members m
                    JOIN group_members gm ON m.id = gm.member_id
                    WHERE gm.group_id = ?
                ");
                $max_order_query->bind_param("i", $group_id);
                $max_order_query->execute();
                $max_order_result = $max_order_query->get_result();
                $max_order = $max_order_result->fetch_assoc()['max_order'];

                // Move current recipient to the end of the rotation within their group
                $update_recipient_query = $conn->prepare("
                    UPDATE members m
                    JOIN group_members gm ON m.id = gm.member_id
                    SET m.rotation_order = ?
                    WHERE m.id = ? AND gm.group_id = ?
                ");
                $new_order = $max_order + 1;
                $update_recipient_query->bind_param("iii", $new_order, $next_member_id, $group_id);
                $update_recipient_query->execute();

                // Now reset all other members in the group to have sequential rotation orders
                // We can't use variables in a prepared statement with UPDATE... ORDER BY
                // So we'll get all the members first, then update them
                $members_query = $conn->prepare("
                    SELECT m.id
                    FROM members m
                    JOIN group_members gm ON m.id = gm.member_id
                    WHERE gm.group_id = ? AND m.id != ?
                    ORDER BY m.rotation_order
                ");
                $members_query->bind_param("ii", $group_id, $next_member_id);
                $members_query->execute();
                $members_result = $members_query->get_result();

                // Update each member's rotation order sequentially
                $new_order = 1;
                while ($member = $members_result->fetch_assoc()) {
                    $update_order_query = $conn->prepare("
                        UPDATE members SET rotation_order = ? WHERE id = ?
                    ");
                    $update_order_query->bind_param("ii", $new_order, $member['id']);
                    $update_order_query->execute();
                    $new_order++;
                }

                // Commit the transaction
                $conn->commit();

                $message = "Success! Funds totaling ₦" . number_format($actual_total, 2) . " from $contributor_count contributors have been distributed to $next_member_name.";
            } catch (Exception $e) {
                // Rollback the transaction if any error occurs
                $conn->rollback();
                $message = "Error: " . $e->getMessage();
            }
        } else {
            $message = "No funds are available for distribution in this group. No members have contributed yet.";
        }
    } else {
        $message = "No members found in this group for distribution.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Distribution</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="bg-warning text-white text-center py-4">
        <h1>Fund Distribution</h1>
    </header>
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Distribute Collected Funds</h2>

                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo strpos($message, 'Success') !== false ? 'success' : 'info'; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Select Group</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                            <div class="form-group">
                                <label for="group_id">Group:</label>
                                <select class="form-control" id="group_id" name="group_id" required onchange="this.form.submit()">
                                    <option value="">-- Select a Group --</option>
                                    <?php foreach ($groups as $group): ?>
                                    <option value="<?php echo $group['id']; ?>" <?php echo (isset($_GET['group_id']) && $_GET['group_id'] == $group['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($group['name']); ?> (₦<?php echo number_format($group['amount'], 2); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($selected_group): ?>
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Group: <?php echo htmlspecialchars($selected_group['name']); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="group_id" value="<?php echo $selected_group['id']; ?>">

                            <div class="form-group">
                                <p class="lead"><strong>Group Contribution Amount:</strong> ₦<?php echo number_format($selected_group['amount'], 2); ?></p>
                                <p class="lead"><strong>Total Collected Funds:</strong> ₦<?php echo number_format($total_funds, 2); ?></p>

                                <?php if ($next_member_details): ?>
                                <p class="lead"><strong>Next Member in Line:</strong> <?php echo htmlspecialchars($next_member_details['full_name']); ?></p>
                                <p class="lead">
                                    <strong>Bank Details:</strong>
                                    <?php echo htmlspecialchars($next_member_details['bank_name']); ?> -
                                    Account No: <?php echo htmlspecialchars($next_member_details['bank_account_number']); ?> -
                                    Code: <?php echo htmlspecialchars($next_member_details['bank_code']); ?>
                                </p>
                                <?php else: ?>
                                <p class="lead text-danger"><strong>No members found in this group</strong></p>
                                <?php endif; ?>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <p>Distribution will reset all group members' contribution status to "Pending" and update the rotation order.</p>
                            </div>

                            <?php if ($next_member_details): ?>
                            <button type="submit" name="distribute" value="1" class="btn btn-warning btn-lg btn-block">Distribute Funds</button>
                            <?php else: ?>
                            <button type="button" class="btn btn-warning btn-lg btn-block" disabled>Distribute Funds</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="admin.php" class="btn btn-secondary">Back to Dashboard</a>
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