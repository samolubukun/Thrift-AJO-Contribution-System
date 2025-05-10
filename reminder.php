<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Reminders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .back-link {
            display: block;
            margin-top: 20px;
            color: #dc3545; /* Bootstrap's danger color */
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-bell mr-2 text-danger"></i> Monthly Contribution Reminders</h5>
                        <p class="card-text">Click the button below to send out monthly contribution reminders to members with pending payments.</p>
                        <form method="post">
                            <button type="submit" name="send_reminders" class="btn btn-danger"><i class="fas fa-envelope mr-1"></i> Activate Monthly Reminder</button>
                        </form>
                        <div id="reminder_status" class="mt-3">
                            <?php
                            use PHPMailer\PHPMailer\PHPMailer;
                            use PHPMailer\PHPMailer\Exception;

                            require 'PHPMailer/src/PHPMailer.php';
                            require 'PHPMailer/src/SMTP.php';
                            require 'PHPMailer/src/Exception.php';

                            if (isset($_POST['send_reminders'])) {
                                // Database connection
                                $conn = new mysqli("localhost", "root", "", "thrift_management");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                try {
                                    $result = $conn->query("SELECT email FROM members WHERE contribution_status = 'Pending'");

                                    $mailSentCount = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        $mail = new PHPMailer(true);

                                        // SMTP configuration
                                        $mail->isSMTP();
                                        $mail->CharSet = "utf-8";
                                        $mail->SMTPAuth = true;
                                        $mail->SMTPSecure = 'ssl'; // Use 'ssl' instead of 'tls'
                                        $mail->Host = 'smtp.gmail.com';
                                        $mail->Port = 465;
                                        $mail->SMTPOptions = array(
                                            'ssl' => array(
                                                'verify_peer' => false,
                                                'verify_peer_name' => false,
                                                'allow_self_signed' => true
                                            )
                                        );
                                        $mail->isHTML(true);

                                        // Gmail app credentials
                                        $mail->Username = 'hunterking4lf@gmail.com';
                                        $mail->Password = 'wqgr duqy oglb kbsd'; // Use your generated Gmail App Password

                                        // Sender and recipient
                                        $mail->setFrom('admin@thriftmanagement.com', 'Thrift Management');
                                        $mail->addAddress($row['email']);

                                        // Email content
                                        $mail->Subject = 'Monthly Contribution Reminder';
                                        $mail->MsgHTML('
                                            Dear Valued Member,<br><br>
                                            We hope this message finds you well.<br><br>
                                            This is a friendly reminder to kindly complete your monthly contribution to the Thrift Management Group. Your continued support is vital to our shared growth and success.<br><br>
                                            If you have already made your payment, please disregard this message.<br><br>
                                            Thank you for being a committed member!<br><br>
                                            Best Regards,<br>
                                            <strong>Thrift Management Team</strong>');

                                        if ($mail->send()) {
                                            $mailSentCount++;
                                        }
                                    }
                                    echo "<div class='alert alert-success' role='alert'><i class='fas fa-check-circle mr-2'></i> Reminders sent successfully to {$mailSentCount} members!</div>";
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger' role='alert'><i class='fas fa-exclamation-triangle mr-2'></i> Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                                }

                                // Close the connection
                                $conn->close();
                            }
                            ?>
                        </div>
                        <a href="admin.php" class="back-link">&larr; Back to Admin Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>