<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thrift_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone_number) || empty($address)) {
        echo "<div class='error-message'>All fields are required!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message'>Invalid email format!</div>";
    } elseif (strlen($password) < 8) {
        echo "<div class='error-message'>Password must be at least 8 characters long!</div>";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $rotation_order = 1; // You might have a different logic for this later

        // Insert the new member
        $stmt = $conn->prepare("INSERT INTO members (first_name, middle_name, last_name, email, password, phone_number, address, rotation_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $first_name, $middle_name, $last_name, $email, $password, $phone_number, $address, $rotation_order);

        if ($stmt->execute()) {
            $member_id = $conn->insert_id;
            $_SESSION['member_id'] = $member_id;
            header("Location: member_dashboard.php");
            exit();
        } else {
            echo "<div class='error-message'>Error registering member: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f8f9fa;
            color: #2d3142;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: #2d3142;
            color: #fff;
            padding: 1.5rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        main {
            flex-grow: 1;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .registration-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 90%;
            max-width: 600px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #2d3142;
            margin-bottom: 2.5rem;
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.7px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #354052;
            font-weight: 500;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 16px);
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .error-message {
            background-color: #ffe0e0;
            color: #d32f2f;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #f44336;
            text-align: center;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #2d3142, #4f5d75);
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 49, 66, 0.2);
            font-weight: 500;
            letter-spacing: 0.5px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #354052, #5a677d);
            box-shadow: 0 6px 20px rgba(45, 49, 66, 0.3);
        }

        footer {
            background: #354052;
            color: #d1d8e0;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Member Registration</h1>
    </header>
    <main>
        <div class="registration-container">
            <h2>Sign Up</h2>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Thrift Management System. All rights reserved.</p>
    </footer>
</body>
</html>