<?php
session_start();

$session_lifetime = 3600; // 1 hour in seconds

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session
    header("Location: admin_login.php?timeout=1"); // Redirect to login with a timeout message
    exit();
}

// Update the last activity timestamp
$_SESSION['last_activity'] = time();

// Check if the user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT email FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$admin_email = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_email = $row['email'];
}

$stmt->close();
$conn->close();

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
?>

<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="bg-danger text-white text-center py-4">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard</h1>
        <?php if (!empty($admin_email)): ?>
            <p class="mt-2"><i class="fas fa-user-shield mr-1"></i> Welcome, <?php echo htmlspecialchars($admin_email); ?></p>
        <?php endif; ?>
        <p><a href="?logout" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a></p>
    </header>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users-cog mr-2 text-danger"></i>Group Management</h5>
                        <p class="card-text">Create and manage groups.</p>
                        <a href="admin_groups.php" class="btn btn-danger"><i class="fas fa-tasks mr-1"></i> Manage Groups</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-plus mr-2 text-secondary"></i>View Join Requests</h5>
                        <p class="card-text">Review and process pending group join requests from members.</p>
                        <a href="admin_join_requests.php" class="btn btn-secondary"><i class="fas fa-clipboard-list mr-1"></i> View Join Requests</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-exchange-alt mr-2 text-primary"></i>View Reassignment Requests</h5>
                        <p class="card-text">Review and process pending group reassignment requests from members.</p>
                        <a href="admin_reassignment_requests.php" class="btn btn-primary"><i class="fas fa-sync-alt mr-1"></i> View Reassignment Requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-hand-holding-usd mr-2 text-warning"></i>Fund Distribution</h5>
                        <p class="card-text">Distribute collected funds to members.</p>
                        <a href="fund_distribution.php" class="btn btn-warning"><i class="fas fa-money-bill-wave mr-1"></i> Distribute Funds</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar mr-2 text-info"></i>Reports</h5>
                        <p class="card-text">View and export detailed reports.</p>
                        <a href="admin_report.php" class="btn btn-info"><i class="fas fa-file-alt mr-1"></i> View Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-bell mr-2 text-danger"></i> Send Reminders</h5>
                        <p class="card-text">Send monthly contribution reminders to members with pending payments.</p>
                        <a href="reminder.php" class="btn btn-danger"><i class="fas fa-envelope mr-1"></i> Manage Reminders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-plus mr-2 text-info"></i>Add Admin</h5>
                        <p class="card-text">Add a new administrator to the system.</p>
                        <a href="insert_admin.php" class="btn btn-info"><i class="fas fa-plus-circle mr-1"></i> Add Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-light text-center py-4 mt-5">
        <p><i class="far fa-copyright mr-1"></i> 2025 Thrift Management System. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>