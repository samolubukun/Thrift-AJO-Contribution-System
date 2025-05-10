<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift Contribution System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2d3142;
            --secondary-color: #4f5d75;
            --text-color: #ffffff;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-link {
            text-decoration: none; /* Remove default underline */
            display: block; /* Make the entire container clickable */
            width: 100%; /* Ensure it spans the full width of its parent */
            height: 100%; /* Ensure it spans the full height of its parent */
            color: var(--text-color); /* Set the text color to white */
        }

        .header-link:hover {
            color: var(--text-color); /* Keep the text color white on hover */
            text-decoration: none; /* Ensure no underline on hover */
        }

        .header-container {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: var(--text-color);
            padding: 24px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .header-icon {
            font-size: 2.4em;
            margin-right: 20px;
            color: var(--text-color);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-title {
            font-size: 2.5em;
            margin: 0;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Background decoration elements */
        .header-container::before {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }

        .header-container::after {
            content: "";
            position: absolute;
            bottom: -30px;
            left: 10%;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            z-index: 1;
        }

        /* Subtle animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        .header-content {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .header-icon {
            animation: float 4s ease-in-out infinite;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-title {
                font-size: 1.8em;
            }

            .header-icon {
                font-size: 1.8em;
            }

            .header-container {
                padding: 18px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <a href="index.php" class="header-link">
            <div class="header-content">
                <i class="fas fa-money-bill-wave header-icon"></i>
                <h1 class="header-title">Thrift Contribution System</h1>
            </div>
        </a>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>