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

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $admin_email = $_POST['email'];
        $admin_password = $_POST['password'];

        $result = $conn->query("SELECT * FROM admin WHERE email = '$admin_email'");
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($admin_password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: admin.php");
                exit();
            } else {
                $error_message = "Invalid password!";
            }
        } else {
            $error_message = "No admin found with that email!";
        }
    }
}

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
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f8f9fa;
            color: #2d3142;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 400px;
            max-width: 90%;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            color: #2d3142;
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .error {
            background-color: #ffe0e0;
            color: #d32f2f;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #f44336;
            text-align: center;
        }

        h3 {
            color: #4f5d75;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.3rem;
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

        input[type="email"],
        input[type="password"] {
            width: calc(100% - 16px);
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #2d3142, #4f5d75);
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 49, 66, 0.2);
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #354052, #5a677d);
            box-shadow: 0 6px 20px rgba(45, 49, 66, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if(!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <h3>Login</h3>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (email.trim() === "" || password.trim() === "") {
                alert("All fields are required!");
                event.preventDefault();
            } else if (!validateEmail(email)) {
                alert("Please enter a valid email address!");
                event.preventDefault();
            }
        });

        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }
    </script>
</body>
</html>