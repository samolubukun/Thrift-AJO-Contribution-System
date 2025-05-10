<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login</title>
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

        header {
            background: #2d3142;
            color: #fff;
            padding: 1.5rem 0;
            text-align: center;
            width: 100%;
            margin-bottom: 2rem;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        main {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 90%;
            max-width: 420px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        section#login h2 {
            color: #2d3142;
            margin-bottom: 2.5rem;
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.7px;
        }

        form#login-form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 0.8rem;
            color: #354052;
            font-weight: 500;
            font-size: 1rem;
        }

        input[type="email"],
        input[type="password"] {
            padding: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 1rem;
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
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #354052, #5a677d);
            box-shadow: 0 6px 20px rgba(45, 49, 66, 0.3);
        }

        .error-message {
            color: #ff4d4d; /* A more prominent red */
            background-color: #ffe6e6; /* A light red background */
            border: 1px solid #ff9999; /* A slightly darker red border */
            padding: 10px;
            border-radius: 6px;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            font-weight: bold; /* Make the text bold */
            text-align: center; /* Center the text */
            display: none; /* Initially hide the error message */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const errorContainer = document.createElement('div');
            errorContainer.className = 'error-message';
            const submitButton = loginForm.querySelector('button[type="submit"]');

            loginForm.insertBefore(errorContainer, submitButton);

            loginForm.addEventListener('submit', function(event) {
                event.preventDefault();

                errorContainer.textContent = '';
                errorContainer.style.display = 'none'; // Ensure it's hidden on each submit

                if (emailInput.value.trim() === "" || passwordInput.value.trim() === "") {
                    errorContainer.textContent = "All fields are required!";
                    errorContainer.style.display = 'block';
                    return;
                } else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(emailInput.value)) {
                    errorContainer.textContent = "Please enter a valid email address!";
                    errorContainer.style.display = 'block';
                    return;
                }

                fetch('login_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(emailInput.value)}&password=${encodeURIComponent(passwordInput.value)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        window.location.href = 'member_dashboard.php';
                    } else {
                        errorContainer.textContent = data;
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorContainer.textContent = 'An error occurred during login.';
                    errorContainer.style.display = 'block';
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Member Login</h1>
    </header>
    <main>
        <section id="login">
            <h2>Login</h2>
            <form id="login-form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Log In</button>
            </form>
        </section>
    </main>
</body>
</html>