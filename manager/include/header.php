<?php
include_once("../../config.php");
if (!isset($_SESSION)) {
    session_start();


    if (!isset($_SESSION["login_manager"])) {
        header("Location: $url");
    }

    $manager_id = $_SESSION['user_id'];
    $sql_user = "SELECT user_photo,user_name,user_role, cus_id FROM users_db WHERE u_id={$manager_id}";
    $res_user = mysqli_query($con, $sql_user);
    if (mysqli_num_rows($res_user) > 0) {
        while ($row_user = mysqli_fetch_assoc($res_user)) {
            $user_photo = $row_user['user_photo'];
            $user_name = $row_user['user_name'];
            $user_role = $row_user['user_role'];
            $_SESSION['cus_id'] = $row_user['cus_id'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Firsthash">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Firsthash - <?php echo TITLE ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="../../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/plugins/select2/css/select2.min.css">
    <!-- <link href="../../assets/plugins/pace/pace.css" rel="stylesheet"> -->


    <!-- Theme Styles -->
    <link href="../../assets/css/main.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
    <script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="">


    <div class="page-container">