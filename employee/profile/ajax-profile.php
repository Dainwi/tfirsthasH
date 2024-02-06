<?php
include_once("../../config.php");
session_start();
if (isset($_POST['updatepro'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $userid = $_POST['userid'];
    $address = $_POST['address'];

    $sql = "UPDATE users_db SET user_name ='$name', user_phone= '$phone', user_email ='$email', user_address='$address' WHERE u_id={$userid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $response = 1;
    } else {
        $response = 0;
    }
    echo $response;
}

if (isset($_POST['imgUpdate'])) {

    $uid = $_POST['userid'];
    $response = "";
    $img_name = $_FILES['img']['name'];
    $img_size = $_FILES['img']['size'];
    $tmp_name = $_FILES['img']['tmp_name'];
    $error = $_FILES['img']['error'];

    if ($error === 0) {

        if ($img_size > 1048576) {
            echo "file size must be 1mb or less";
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {

                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $img_upload_path = "../../assets/images/profile/" . $new_img_name;
                $move_file = move_uploaded_file($tmp_name, $img_upload_path);

                if ($move_file) {
                    $sql1 = "UPDATE users_db SET user_photo='$new_img_name' WHERE u_id={$uid}";
                    $res1 = mysqli_query($con, $sql1);

                    if ($res1) {
                        $response .=  1;
                    }
                } else {

                    $response .= "not uploading file";
                }
            } else {

                $response .= "You can't upload this type of file";
            }
        }
    } else {

        $response .= "Please choose an image";
    }

    echo $response;
}

if (isset($_POST['new-password'])) {
    $opass = $_POST['old-password'];
    $npass = $_POST['new-password'];
    $cpass = $_POST['confirm-password'];
    $aid = $_POST['admin-id'];

    $oldpass = md5($opass);
    $newpass = md5($npass);

    if ($npass === $cpass) {

        $sql = "SELECT user_password,u_id FROM users_db WHERE u_id ={$aid} AND user_password='$oldpass'";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {

            $sql2 = "UPDATE users_db SET user_password='$newpass' WHERE user_id={$aid}";
            $res2 = mysqli_query($con, $sql2);
            if ($res2) {
                $_SESSION['success'] = "Password Changed Successful ";
                echo "<script>window.location=document.referrer;</script>";
            } else {
                $_SESSION['error'] = "something went wrong";
                echo "<script>window.location=document.referrer;</script>";
            }
        } else {
            $_SESSION['error'] = "old password is incorrect";
            echo "<script>window.location=document.referrer;</script>";
        }
    } else {
        $_SESSION['error'] = "new password and confirm password donot match";
        echo "<script>window.location=document.referrer;</script>";
    }
}
