<?php
include_once("../../config.php");
session_start();
if (isset($_POST['signup'])) {
    // echo "<pre>";
    // print_r($_POST);
    $client_name = $_POST['client_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "INSERT INTO clients_db(c_name,c_phone,c_email,c_address,c_auth)VALUES('$client_name','$phone','$email','$address',1)";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>location.href='{$url}/clients/index.php'</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}


//login 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "SELECT * FROM clients_db WHERE c_email='$email' AND c_phone='$phone'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $clid = $row['c_id'];
            $auth = $row['c_auth'];
        }

        if ($auth == 1) {
            $_SESSION['login_client'] = true;
            $_SESSION['client_id'] = $clid;
            echo "<script>location.href='{$url}/clients/dashboard'</script>";
        } else {
            $_SESSION['error'] = "Authentication is not enabled";
            echo "<script>window.location.reload(history.back());</script>";
        }
    } else {

        $_SESSION['error'] = "Please Enter Valid Credential";
        echo "<script>window.location.reload(history.back());</script>";
    }
}
