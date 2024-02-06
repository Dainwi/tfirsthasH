<?php
include_once("../config.php");
session_start();
if (isset($_SESSION["login_admin"])) {
    header("Location: {$url}/admin/dashboard");
}
if (isset($_SESSION["login_manager"])) {
    header("Location: {$url}/manager/dashboard");
}
if (isset($_SESSION["login_employee"])) {
    header("Location: {$url}/employee/dashboard");
}

if (isset($_SESSION['login_client'])) {
    header("Location: {$url}/clients/dashboard");
}
?>

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
    <link rel="shortcut icon" href="../assets/images/favicon.png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <!-- <link href="assets/plugins/pace/pace.css" rel="stylesheet"> -->


    <!-- Theme Styles -->
    <link href="../assets/css/main.min.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="login-page">

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-4">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="authent-logo">
                            <a href="#"><img src="../assets/images/logo/logo.png" alt="" class="img-fluid w-25"></a>
                        </div>
                        <div class="authent-text">
                            <p>Welcome to Firsthash</p>
                            <p>Please Sign-in to your account.</p>
                        </div>

                        <form action="actions/login-actions.php" method="POST">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="floatingEmail" placeholder="Email" name="email">
                                    <label for="floatingEmail">Email</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingPhone" placeholder="Phone" name="phone">
                                    <label for="floatingPhone">Phone</label>
                                </div>
                            </div>
                            <?php

                            if (isset($_SESSION['error'])) {

                            ?>
                                <div class="alert alert-danger mb-3" role="alert"><?php echo $_SESSION['error']; ?></div>
                            <?php

                                unset($_SESSION['error']);
                            }

                            ?>

                            <div class="d-grid">
                                <input type="hidden" name="login" value="1">
                                <button type="submit" class="btn btn-warning btn-firsthash m-b-xs mt-4 border-none">Sign In</button>

                            </div>
                        </form>
                        <div class="">
                            <p class="text-center">Don't have an account. <a href="<?php echo $url . "/clients/create-account.php" ?>" class="text-info ms-3">Create Account</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <!-- <script src="assets/plugins/pace/pace.min.js"></script> -->
    <script src="../assets/js/main.min.js"></script>
</body>

</html>