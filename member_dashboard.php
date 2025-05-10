<?php
session_start();

$session_lifetime = 3600; // 1 hour in seconds

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session
    header("Location: login.php?timeout=1"); // Redirect to login with a timeout message
    exit();
}

// Update the last activity timestamp
$_SESSION['last_activity'] = time();

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

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

// Get the logged-in member's ID
$member_id = $_SESSION['member_id'];

// Fetch member information
$result = $conn->query("SELECT * FROM members WHERE id = $member_id");
$member = $result->fetch_assoc();

// --- Group Joining Logic ---
// Check if the member is already in a group
$check_group_assignment = $conn->query("SELECT gm.group_id, g.name AS group_name FROM group_members gm JOIN groups g ON gm.group_id = g.id WHERE gm.member_id = $member_id");
$current_group = $check_group_assignment->fetch_assoc();

// Check if the member has any pending group requests
$check_pending_requests = $conn->prepare("SELECT r.id, g.name, g.amount 
                                        FROM group_join_requests r 
                                        JOIN groups g ON r.group_id = g.id 
                                        WHERE r.member_id = ? AND r.status = 'Pending'");
$check_pending_requests->bind_param("i", $member_id);
$check_pending_requests->execute();
$pending_request_result = $check_pending_requests->get_result();
$pending_request = $pending_request_result->fetch_assoc();

// Fetch available groups (where current members < max members)
$available_groups_query = $conn->query("SELECT id, name, amount, current_number_of_members, max_members FROM groups WHERE current_number_of_members < max_members");
$available_groups = $available_groups_query->fetch_all(MYSQLI_ASSOC);

// Handle group join request submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_group'])) {
    $requested_group_id = $_POST['group_id'];

    // Check if the member has already requested this group
    $check_existing_request = $conn->prepare("SELECT id FROM group_join_requests WHERE member_id = ? AND group_id = ? AND status = 'Pending'");
    $check_existing_request->bind_param("ii", $member_id, $requested_group_id);
    $check_existing_request->execute();
    $existing_request_result = $check_existing_request->get_result();

    if ($existing_request_result->num_rows > 0) {
        $_SESSION['message'] = "You have already sent a request to join this group.";
        $_SESSION['message_type'] = "warning";
    } else {
        $insert_request = $conn->prepare("INSERT INTO group_join_requests (member_id, group_id) VALUES (?, ?)");
        $insert_request->bind_param("ii", $member_id, $requested_group_id);
        if ($insert_request->execute()) {
            $_SESSION['message'] = "Your request to join the group has been sent to the admin.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error sending request: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
        $insert_request->close();
    }
    $check_existing_request->close();
    
    // Redirect to prevent form resubmission on refresh
    header("Location: member_dashboard.php");
    exit();
}

// --- Contribution Status Logic ---
$currentDate = date("Y-m-d");
$lastDayOfMonth = date("Y-m-t");
$firstDayOfPaymentWindow = date("Y-m-", strtotime($lastDayOfMonth)) . (date("t") - 6);
$withinPaymentWindow = ($currentDate >= $firstDayOfPaymentWindow && $currentDate <= $lastDayOfMonth);

$contributedThisMonth = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_contribution'])) {
    if ($withinPaymentWindow) {
        $contribution_status = "Contributed";
        $conn->query("UPDATE members SET contribution_status = '$contribution_status' WHERE id = $member_id");
        $_SESSION['message'] = "Contribution made successfully!";
        $_SESSION['message_type'] = "success";
        // To immediately reflect the contribution status:
        $contributedThisMonth = true;
        
        // Redirect to prevent form resubmission on refresh
        header("Location: dashboard.php");
        exit();
    }
}

// Check if the member has contributed within the current month
$firstDayOfMonth = date("Y-m-01");
$checkContribution = $conn->query("SELECT COUNT(*) AS count FROM contributions WHERE member_id = $member_id AND date_of_contribution >= '$firstDayOfMonth' AND date_of_contribution <= '$lastDayOfMonth'");
$contributionData = $checkContribution->fetch_assoc();
if ($contributionData['count'] > 0) {
    $contributedThisMonth = true;
}

// --- Group and Payout Information ---
$group_info_query = $conn->prepare("
    SELECT
        g.name AS group_name,
        g.amount AS contribution_amount,
        (SELECT COUNT(*) FROM group_members WHERE group_id = gm.group_id) AS total_members,
        m.rotation_order
    FROM group_members gm
    JOIN groups g ON gm.group_id = g.id
    JOIN members m ON gm.member_id = m.id
    WHERE gm.member_id = ?
");
$group_info_query->bind_param("i", $member_id);
$group_info_query->execute();
$group_info_result = $group_info_query->get_result();
$group_info = $group_info_result->fetch_assoc();

$next_payout_date = "N/A";
$potential_payout = "N/A";

if ($group_info) {
    // Calculate potential payout (assuming all members contribute)
    $potential_payout = number_format($group_info['contribution_amount'] * $group_info['total_members'], 2);

    // Determine the next payout date (simplified logic based on rotation order)
    $next_payout_order = ($group_info['rotation_order'] % $group_info['total_members']) + 1;
    $months_until_payout = $next_payout_order - 1;
    $next_payout_timestamp = strtotime("+" . $months_until_payout . " months", strtotime(date("Y-m-01")));
    $next_payout_date = date("Y-m-d", $next_payout_timestamp);
}

// --- Group Activity ---
$group_activity_query = $conn->prepare("
    SELECT
        m.first_name,
        m.last_name,
        c.date_of_contribution,
        g.amount AS contribution_amount
    FROM contributions c
    JOIN members m ON c.member_id = m.id
    JOIN group_members gm ON m.id = gm.member_id
    JOIN groups g ON gm.group_id = g.id
    WHERE gm.group_id = (SELECT group_id FROM group_members WHERE member_id = ?)
    ORDER BY c.date_of_contribution DESC
    LIMIT 5
");
$group_activity_query->bind_param("i", $member_id);
$group_activity_query->execute();
$group_activity_result = $group_activity_query->get_result();
$group_activities = [];
while ($row = $group_activity_result->fetch_assoc()) {
    $group_activities[] = $row;
}

// --- Payout History ---
$payout_history_query = $conn->prepare("
    SELECT
        fd.distribution_date,
        fd.amount
    FROM fund_distribution fd
    WHERE fd.member_id = ?
    ORDER BY fd.distribution_date DESC
");
$payout_history_query->bind_param("i", $member_id);
$payout_history_query->execute();
$payout_history_result = $payout_history_query->get_result();
$payout_history = [];
while ($row = $payout_history_result->fetch_assoc()) {
    $payout_history[] = $row;
}

// Close the database connection
$conn->close();
?>
<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh;">
<header style="background-color: #28a745; color: white; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center;">
        <?php if ($member['profile_picture']): ?>
            <img src="uploads/<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; border: 2px solid white;">
        <?php else: ?>
            <img src="images/default_profile.png" alt="Default Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; border: 2px solid white;">
        <?php endif; ?>
        <h1 style="margin: 0; font-size: 1.8em;">Welcome, <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h1>
    </div>
    <div>
        <a href="?logout" class="btn btn-light btn-sm" style="font-weight: bold;"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
    </div>
</header>

    <main class="container" style="margin-top: 30px; flex-grow: 1;">
        <!-- Display session messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php 
            // Clear the message after displaying it
            unset($_SESSION['message']); 
            unset($_SESSION['message_type']); 
            ?>
        <?php endif; ?>

        <?php if ($current_group): ?>
            <div style="background-color:#e9ecef; color:#343a40; padding:15px; margin-bottom: 20px; border-radius: 5px;">
                <p style="margin-bottom: 0;"><i class="fas fa-users mr-2" style="color: #007bff;"></i> You are currently assigned to the group: <strong style="color: #007bff;"><?php echo htmlspecialchars($current_group['group_name']); ?></strong>.</p>
                <p style="margin-bottom: 0;"><a href="request_reassignment.php" style="color: #007bff; text-decoration: underline;"><i class="fas fa-exchange-alt mr-1"></i> Request Reassignment?</a></p>
            </div>
        <?php elseif ($pending_request): ?>
            <div style="background-color:#e9ecef; color:#343a40; padding:15px; margin-bottom: 20px; border-radius: 5px;">
                <p style="margin-bottom: 0;"><i class="fas fa-clock mr-2" style="color: #ffc107;"></i> You have a pending request to join <strong style="color: #007bff;"><?php echo htmlspecialchars($pending_request['name']); ?></strong> group (₦<?php echo number_format($pending_request['amount'], 2); ?>).</p>
                <p style="margin-bottom: 0;"><i class="fas fa-info-circle mr-1" style="color: #17a2b8;"></i> Please wait for admin approval.</p>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #007bff; margin-bottom: 10px;"><i class="fas fa-user-plus mr-2"></i> Join a Thrift Group</h5>
                    <?php if (!empty($available_groups)): ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="group_id" style="font-weight: bold;">Available Groups:</label>
                                <select class="form-control" id="group_id" name="group_id" required>
                                    <option value="">Select a group</option>
                                    <?php foreach ($available_groups as $group): ?>
                                        <option value="<?php echo htmlspecialchars($group['id']); ?>">
                                            <?php echo htmlspecialchars($group['name']); ?> (₦<?php echo number_format($group['amount'], 2); ?>) - Slots: <?php echo htmlspecialchars($group['max_members'] - $group['current_number_of_members']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="request_group"><i class="fas fa-paper-plane mr-1"></i> Send Join Request</button>
                        </form>
                    <?php else: ?>
                        <p style="color: #6c757d;"><i class="fas fa-info-circle mr-1"></i> No groups are currently available with open slots.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
            <div style="flex: 1 1 300px; margin-right: 20px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #28a745; margin-bottom: 10px;"><i class="fas fa-money-bill-wave mr-2"></i> Contribution Status</h5>
                    <p style="margin-bottom: 15px;">
                        Status:
                        <?php
                        if ($contributedThisMonth) {
                            echo "<strong style='color: #198754;'><i class='fas fa-check-circle mr-1'></i> Contributed this month</strong>";
                        } else {
                            echo "<strong style='color: #dc3545;'><i class='fas fa-exclamation-circle mr-1'></i> Pending</strong>";
                        }
                        ?>
                    </p>
                    <?php if (!$contributedThisMonth && $withinPaymentWindow): ?>
                        <a href="contribute.php" class="btn btn-success"><i class="fas fa-wallet mr-1"></i> Make Contribution</a>
                    <?php elseif (!$withinPaymentWindow): ?>
                        <p style="color: #6c757d; font-size: 0.9em;"><i class="far fa-clock mr-1"></i> Contribution window is currently closed.</p>
                    <?php else: ?>
                        <p style="color: #198754; font-size: 0.9em;"><i class="fas fa-check-double mr-1"></i> Thank you for your contribution!</p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex: 1 1 300px; margin-right: 20px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #007bff; margin-bottom: 10px;"><i class="fas fa-user-cog mr-2"></i> Profile Management</h5>
                    <p style="margin-bottom: 15px;">Update your profile information.</p>
                    <a href="profile_management.php" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1em;"><i class="fas fa-edit mr-1"></i> Update Profile</a>
                </div>
            </div>
            <div style="flex: 1 1 300px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #17a2b8; margin-bottom: 10px;"><i class="fas fa-history mr-2"></i> Contribution History</h5>
                    <p style="margin-bottom: 15px;">View your past contributions.</p>
                    <a href="contribution_history.php" style="display: inline-block; padding: 10px 15px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 5px; font-size: 1em;"><i class="fas fa-list-alt mr-1"></i> View History</a>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap;">
            <div style="flex: 1 1 300px; margin-right: 20px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                <h5 style="font-size: 1.5em; color: #ffc107; margin-bottom: 10px;"><i class="fas fa-calendar-check mr-2"></i> Upcoming Payout</h5>
                    <p style="margin-bottom: 10px;"><i class="far fa-calendar-alt mr-1"></i> Next Payout Date: <strong style="color: #000;"><?php echo htmlspecialchars($next_payout_date); ?></strong></p>
                    <p style="margin-bottom: 10px;"><i class="fas fa-coins mr-1"></i> Potential Amount: <strong style="color: #000;">₦<?php echo htmlspecialchars($potential_payout); ?></strong></p>
                    <?php if ($group_info && isset($group_info['rotation_order']) && isset($group_info['total_members'])): ?>
                        <p style="font-size: 0.95em;"><i class="fas fa-sort-numeric-down mr-1"></i> Your Rotation Order: <strong style="color: #000;"><?php echo htmlspecialchars($group_info['rotation_order']); ?></strong> / <?php echo htmlspecialchars($group_info['total_members']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex: 1 1 300px; margin-right: 20px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #6c757d; margin-bottom: 10px;"><i class="fas fa-hand-holding-usd mr-2"></i> Payout History</h5>
                    <?php if (!empty($payout_history)): ?>
                        <ul style="list-style: none; padding: 0;">
                            <?php foreach ($payout_history as $payout): ?>
                                <li style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.95em;">
                                    <i class="far fa-calendar mr-1"></i> Date: <span style="color: #000;"><?php echo htmlspecialchars($payout['distribution_date']); ?></span>,
                                    <i class="fas fa-money-bill-alt mr-1"></i> Amount: <span style="color: #000;">₦<?php echo number_format($payout['amount'], 2); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color: #6c757d; font-size: 0.9em;"><i class="fas fa-info-circle mr-1"></i> No payout history available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex: 2 1 400px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 8px; overflow: hidden; background-color: white;">
                <div style="padding: 20px;">
                    <h5 style="font-size: 1.5em; color: #343a40; margin-bottom: 10px;"><i class="fas fa-users-cog mr-2"></i> Group Activity</h5>
                    <p style="color: #6c757d; margin-bottom: 15px;"><i class="fas fa-chart-line mr-1"></i> Latest contributions in your group.</p>
                    <ul style="list-style: none; padding: 0;">
                        <?php if (!empty($group_activities)): ?>
                            <?php foreach ($group_activities as $activity): ?>
                                <li style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.95em;">
                                    <i class="fas fa-user-circle mr-1"></i> <strong style="color: #000;"><?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?></strong> contributed ₦<?php echo number_format($activity['contribution_amount'], 2); ?> on <?php echo htmlspecialchars($activity['date_of_contribution']); ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="color: #6c757d; font-size: 0.9em;"><i class="fas fa-info-circle mr-1"></i> No recent group activity.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    <footer style="background-color: #f8f9fa; text-align: center; padding: 15px; margin-top: 30px; border-top: 1px solid #dee2e6;">
        <p style="margin: 0; font-size: 0.9em; color: #6c757d;">&copy; <?php echo date('Y'); ?> Thrift Management System. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>