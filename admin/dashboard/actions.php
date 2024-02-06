<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once("../../config.php");
if (isset($_POST['add-terms'])) {
    $terms = $_POST['terms'];
    $sql = "INSERT INTO terms_condition(terms_details)VALUES('$terms')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['edit-terms'])) {
    $terms = $_POST['terms'];
    $termsid = $_POST['termsid'];
    $sql = "UPDATE terms_condition SET terms_details='$terms' WHERE terms_id={$termsid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['btn_del_terms'])) {
    $eventid = $_POST['eventid'];
    $sql = "DELETE FROM terms_condition WHERE terms_id={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}
