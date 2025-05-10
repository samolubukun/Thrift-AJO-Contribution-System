<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$member_id = $_SESSION['member_id'];
$result = $conn->query("SELECT * FROM members WHERE id = $member_id");
$member = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $bank_name = trim($_POST['bank_name']);
    $bank_account_number = trim($_POST['bank_account_number']);
    $bank_code = trim($_POST['bank_code']);

    // Handle profile picture upload
    $profile_picture = $member['profile_picture']; // Keep existing if no new upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $upload_dir = "uploads/"; // Create this directory in your project
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid('profile_') . '.' . $file_extension;
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                // Delete old profile picture if it exists
                if ($member['profile_picture'] && file_exists($upload_dir . $member['profile_picture'])) {
                    unlink($upload_dir . $member['profile_picture']);
                }
                $profile_picture = $new_file_name;
            } else {
                echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>Error uploading profile picture.</div>";
            }
        } else {
            echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>Invalid file type. Only JPEG, PNG, and GIF are allowed.</div>";
        }
    }

    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>First Name, Last Name, and email are required!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>Invalid email format!</div>";
    } else {
        $update_query = "UPDATE members SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', email='$email', phone_number='$phone', address='$address', bank_name='$bank_name', bank_account_number='$bank_account_number', bank_code='$bank_code', profile_picture='$profile_picture'";
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query .= ", password='$hashed_password'";
        }
        $update_query .= " WHERE id=$member_id";
        if ($conn->query($update_query) === TRUE) {
            echo "<div style='background-color:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border:1px solid #c3e6cb; border-radius:5px;'>Profile updated successfully!</div>";
            // Refresh member data after update
            $result = $conn->query("SELECT * FROM members WHERE id = $member_id");
            $member = $result->fetch_assoc();
        } else {
            echo "<div style='background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb; border-radius:5px;'>Error: " . $update_query . "<br>" . $conn->error . "</div>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh;">
    <header style="background-color: #007bff; color: white; text-align: center; padding: 20px;">
        <h1 style="margin: 0; font-size: 2.2em;">Profile</h1>
    </header>
    <main class="container" style="margin-top: 30px; flex-grow: 1;">
        <div class="row justify-content-center">
            <div class="col-md-8" style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);">
                <h2 style="color: #343a40; margin-bottom: 20px;">Your Profile</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <?php if ($member['profile_picture']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail mb-3" style="width: 120px; height: 120px; border-radius: 50%; border: 2px solid #007bff;">
                        <?php else: ?>
                            <img src="images/default_profile.png" alt="Default Profile Picture" class="img-thumbnail mb-3" style="width: 120px; height: 120px; border-radius: 50%; border: 2px solid #6c757d;">
                        <?php endif; ?>
                        <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*" style="margin-top: 10px;">
                        <small class="form-text text-muted" style="color: #6c757d;">Upload a JPEG, PNG, or GIF file.</small>
                    </div>
                    <div class="form-group">
                        <label for="first_name" style="font-weight: bold; color: #343a40;">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($member['first_name']); ?>" required style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="middle_name" style="font-weight: bold; color: #343a40;">Middle Name:</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($member['middle_name']); ?>" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="last_name" style="font-weight: bold; color: #343a40;">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($member['last_name']); ?>" required style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="email" style="font-weight: bold; color: #343a40;">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="phone" style="font-weight: bold; color: #343a40;">Phone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone_number']); ?>" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="address" style="font-weight: bold; color: #343a40;">Address:</label>
                        <textarea class="form-control" id="address" name="address" rows="3" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"><?php echo htmlspecialchars($member['address']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bank_name" style="font-weight: bold; color: #343a40;">Bank Name:</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php echo htmlspecialchars($member['bank_name']); ?>" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="bank_account_number" style="font-weight: bold; color: #343a40;">Bank Account Number:</label>
                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="<?php echo htmlspecialchars($member['bank_account_number']); ?>" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="bank_code" style="font-weight: bold; color: #343a40;">Bank Code:</label>
                        <input type="text" class="form-control" id="bank_code" name="bank_code" value="<?php echo htmlspecialchars($member['bank_code']); ?>" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="password" style="font-weight: bold; color: #343a40;">Password (leave blank to keep current):</label>
                        <input type="password" class="form-control" id="password" name="password" style="padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1em; border-radius: 5px; background-color: #007bff; color: white; border: none; cursor: pointer;">Update Profile</button>
                </form>
            </div>
        </div>
    </main>
    <div class="mt-3 text-center">
        <a href="member_dashboard.php" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.9em; border-radius: 5px; text-decoration: none; color: white; background-color: #6c757d; border: none;">Back to Dashboard</a>
    </div>
    <footer style="background-color: #f8f9fa; text-align: center; padding: 15px; margin-top: 30px; border-top: 1px solid #dee2e6;">
        <p style="margin: 0; font-size: 0.9em; color: #6c757d;">&copy; 2025 Thrift Management System. All rights reserved.</p>
    </footer>
</body>
</html>