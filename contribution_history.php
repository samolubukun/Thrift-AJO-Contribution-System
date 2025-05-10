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
$result = $conn->query("SELECT * FROM contributions WHERE member_id = $member_id ORDER BY date_of_contribution DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribution History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh;">
    <header style="background-color: #17a2b8; color: white; text-align: center; padding: 20px;">
        <h1 style="margin: 0; font-size: 2.2em;">Contribution History</h1>
    </header>
    <main class="container" style="margin-top: 30px; flex-grow: 1;">
        <div class="row justify-content-center">
            <div class="col-md-8" style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);">
                <h2 style="color: #343a40; margin-bottom: 20px;">Your Contributions</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: left;">Date of Contribution</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: left;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo $row['date_of_contribution']; ?></td>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">₦ <?php echo number_format($row['amount'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="background-color: #fff3cd; color: #85640a; padding: 15px; border: 1px solid #ffeeba; border-radius: 4px;">
                        No contributions found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div class="mt-3 text-center">
        <a href="member_dashboard.php" style="display: inline-block; padding: 10px 20px; font-size: 1em; border-radius: 5px; text-decoration: none; color: white; background-color: #6c757d; border: none;">Back to Dashboard</a>
    </div>
    <footer style="background-color: #f8f9fa; text-align: center; padding: 15px; margin-top: 30px; border-top: 1px solid #dee2e6;">
        <p style="margin: 0; font-size: 0.9em; color: #6c757d;">&copy; 2025 Thrift Management System. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>