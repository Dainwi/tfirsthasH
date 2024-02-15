<?php 
include './config.php';
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/Exception.php';
require './phpmailer/src/SMTP.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $query = "SELECT * FROM users_db WHERE user_email = '$email'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50)); // Generate a random token
        $expires = date("Y-m-d H:i:s", strtotime("+6 hour")); // Token expires in 6 hours

        // Store token and expiry date in your database
        $updateQuery = "UPDATE users_db SET reset_token = '$token', token_expiry = '$expires' WHERE user_email = '$email'";
        mysqli_query($con, $updateQuery);

        // Send reset email using PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'firsthash.business@gmail.com';
            $mail->Password = 'qoklbmdtlyqesvqr';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('firsthash.business@gmail.com', 'First Hash');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            
            $url = "https://tfirsthash.triplehash.in/reset_password.php?token=$token"; // Adjust the URL
           $mail->Body = '
            <html>
            <head>
                <title>Reset Your Password</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
                    .container {
                        max-width: 600px;
                        margin: auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    }
                    h2 {
                        color: #333;
                    }
                    p {
                        color: #666;
                    }
                    .reset-link {
                        background-color: #007bff;
                        color: #fff !important;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .reset-link:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Reset Your Password</h2>
                    <p>Dear User,</p>
                    <p>Welcome to First Hash! We\'re delighted to have you join our community.</p>
                    <p>Please click on the following link to reset your password:</p>
                    <br/>
                    <a href="' . $url . '" class="reset-link">' . 'Reset Your Password' . '</a>
                    <br/>
                    <br/>
                    <p>If you have any questions or need further assistance, feel free to contact us.</p>
                    <p>Best regards,<br>Team First Hash</p>
                </div>
            </body>
            </html>';
            
            if ($mail->send()) {
                $_SESSION['message'] = 'success';
            } else {
                $_SESSION['message'] = 'Mail not sent: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            $_SESSION['message'] = 'Mail not sent: ' . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = 'Email not found.';
    }
}

echo $_SESSION['message'];
?>