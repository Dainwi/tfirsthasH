<?php 
include './config.php';

$token = $_POST['token'];
$newPassword = $_POST['password']; // Consider validating and hashing the password

// Validate token again (for security reasons) and get user email
$stmt = $con->prepare("SELECT user_email FROM users_db WHERE reset_token = ? AND token_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['user_email'];
    
    // Hash the new password before storing it
    $hashedPassword = md5($newPassword);
    
    // Update user's password and clear reset token and expiry
    $stmt = $con->prepare("UPDATE users_db SET user_password = ?, reset_token = NULL, token_expiry = NULL WHERE user_email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $success = $stmt->execute();

    if ($success) {
        // Password updated successfully
        echo '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Updated</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
        
                .container {
                    text-align: center;
                }
        
                h2 {
                    color: #28a745;
                }
        
                .btn {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #fff;
                    text-decoration: none;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
        
                .btn:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        
        <body>
            <div class="container">
                <h2>Password Updated Successfully!</h2>
                <a href="index.php" class="btn">Go to Login Page</a>
            </div>
        </body>
        
        </html>
        ';

    } else {
        echo '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Updated</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
        
                .container {
                    text-align: center;
                }
        
                h2 {
                    color: red;
                }
        
                .btn {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #fff;
                    text-decoration: none;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
        
                .btn:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        
        <body>
            <div class="container">
                <h2>Error Updating Password!</h2>
                <a href="forgot_password_form.php" class="btn"> Try again</a>
            </div>
        </body>
        
        </html>
        ';
    }
} else {
    echo '<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Updated</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
    
            .container {
                text-align: center;
            }
    
            h2 {
                color: #28a745;
            }
    
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
    
            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    
    <body>
    <div class="container">
    <h2>Link Expired!</h2>
    <a href="forgot_password_form.php" class="btn"> Try again</a>
</div>
    </body>
    
    </html>
    ';
}

// Close statement and connection if applicable
// $stmt->close();
?>