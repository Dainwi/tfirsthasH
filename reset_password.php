<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Firsthash</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- <link href="assets/plugins/pace/pace.css" rel="stylesheet"> -->



    <!-- Theme Styles -->
    <!-- <link href="assets/css/main.min.css" rel="stylesheet"> -->
    <!-- <link href="assets/css/custom.css" rel="stylesheet"> -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>



<?php
include './config.php';

// reset_password.php
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    
    // Validate token and check expiry
    $stmt = $con->prepare("SELECT * FROM users_db WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token is valid; display reset form
        // echo '<form action="update_password.php" method="post">
        //     New Password: <input type="password" name="password" required>
        //     <input type="hidden" name="token" value="'.$token.'">
        //     <input type="submit" value="Reset Password">
        // </form>';

        echo '<div style="height: 100vh" class="container-fluid d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
              
                <div class="col-md-12 col-lg-4">
                    <div class="card bg-white border-0 shadow rounded">
                        <div class="card-body">
                            <div class="authent-logo text-center">
                                <div class="text-center img-fluid">
                                    <img src="logo-icon.png" width="100px" height="100px" alt="" srcset="" />
                                </div>
                                <span>Welcome to <b>First Hash</b></span>
                                <p>Please Enter your new password.</p>
                            </div>

                            <form action="update_password.php" method="post">
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input name="password" type="password" class="form-control" id="password" placeholder="name@example.com" />
                                        <input type="hidden" name="token" value="'.$token.'">
                                        <label for="password">password</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button name="submit" type="submit" value="Reset Password" class="btn m-b-xs py-2">
                                        Reset Password
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    } else {
        echo 'Invalid or expired token.';
    }
} else {
    echo 'No token provided.';
}