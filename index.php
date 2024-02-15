<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


include_once("config.php");
session_start();
if (isset($_SESSION["login_admin"])) {
  header("Location: {$url}/admin/dashboard");
  exit(0);
} elseif (isset($_SESSION["login_manager"])) {
  header("Location: {$url}/manager/dashboard");
  exit(0);
} elseif (isset($_SESSION["login_superadmin"])) {
  header("Location: {$url}/superadmin/dashboard");
  exit(0);
} elseif (isset($_SESSION["login_employee"])) {
  header("Location: {$url}/employee/dashboard");
  exit(0);
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

<body class="login-page">

  <div style="height: 100vh" class="container-fluid d-flex justify-content-center align-items-center">
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
                <p>Please Sign-in to your account.</p>
              </div>

              <form action="login-action.php" method="post">
                <div class="mb-1">
                  <div class="form-floating">
                    <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com" />
                    <label for="email">Email</label>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-floating">
                    <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" />
                    <label for="password">Password</label>
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
                  <button name="submit" type="submit" class="btn m-b-xs py-2">
                    Sign In
                  </button>
                </div>

              </form>
              <div class="authent-reg pt-3">
                <a href="forgot_password_form.php"> Forgot Password? </a>
                <p class="pt-3">
                  Not a Member yet?
                  <br/>
                  <a href="register.php">Create an account</a> and get
                  Started Now!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Javascripts -->
  <script src="assets/plugins/jquery/jquery-3.4.1.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
  <!-- <script src="assets/plugins/pace/pace.min.js"></script> -->
  <script src="assets/js/main.min.js"></script>
</body>

</html>