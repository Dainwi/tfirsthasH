<?php
include "config.php";

$alertMessage = ''; // Initialize an empty alert message

if (isset($_POST['submit'])) {
  $name = mysqli_real_escape_string($con, $_POST['username']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $phone = mysqli_real_escape_string($con, $_POST['phone']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  // Hashing the password
  $password_hashed = md5($password);

  // Check if user already exists
  $sql_check = "SELECT c_email FROM admin WHERE c_email = '$email'";
  $result_check = mysqli_query($con, $sql_check);

  if (mysqli_num_rows($result_check) > 0) {
    $alertMessage = '<div class="alert alert-danger" role="alert">User with this email already exists.</div>';
  } else {
    // Validate email domain
    $allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com'];  // Add more domains as needed

    $emailParts = explode('@', $email);
    $domain = end($emailParts);

    if (!in_array($domain, $allowedDomains)) {
      $alertMessage = '<div class="alert alert-danger" role="alert">Invalid email. Please enter a valid email address.</div>';
    } else {
      // Registration successful
      $alertMessage = '<div class="alert alert-success" role="alert">Registration successful</div>';

      // Insert new user if not exists
   $sql = "INSERT INTO admin(c_name, c_email, c_phone, password) VALUES ('$name', '$email', '$phone', '$password_hashed')";
$result = mysqli_query($con, $sql);







      if ($result) {
        $userid = mysqli_insert_id($con);
        // Correctly use the last inserted ID in the next query
        $sqlcomp = "INSERT INTO companies(cus_id) VALUES($userid)";
        $resultcomp = mysqli_query($con, $sqlcomp);
        session_start();
        $_SESSION['user_id'] = $userid;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        header("location: {$url}/plan.php");
        exit(); // Terminate script execution after redirection
      } else {
        $alertMessage = '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($con) . '</div>';
      }
    }
  }
}
?>

<!DOCTYPE html>
<!-- The rest of your HTML remains unchanged -->




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

  <!-- Display Bootstrap alert message -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 col-lg-4">
        <?php echo $alertMessage; ?>
      </div>
    </div>
  </div>
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
                <p>Signup with us to become a Member</p>
              </div>

              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="mb-1">
                  <div class="form-floating">
                    <input type="text" name="username" class="form-control" id="input-username" placeholder="Name" />
                    <label for="input-username">Full Name</label>
                  </div>
                </div>
                <div class="mb-1">
                  <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="input-email" placeholder="name@example.com" />
                    <label for="input-email">Email address</label>
                  </div>
                </div>
                <div class="mb-1">
                  <div class="form-floating">
                    <input type="tel" name="phone" class="form-control" id="input-phone" placeholder="+91-9876543210" />
                    <label for="input-phone">Mobile Number</label>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" />
                    <label for="floatingPassword">Password</label>
                  </div>
                </div>

                <div class="d-grid">
                  <button name="submit" type="submit" class="btn m-b-xs py-2">
                    Create account
                  </button>
                </div>
              </form>
              <div class="authent-reg">
                <p class="pt-3">
                  Already a Member?
                  <a href="index.php">Sign In Here</a>
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