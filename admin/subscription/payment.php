<?php
session_start();
include '../../config.php';

$userId = $_SESSION['user_id'];

if (isset($_POST['paymentStatus'])) {
    $payment_id = $_POST['payment_id'];
    $payableAmount = $_POST['payableAmount'];
    $planId = $_POST['planId'];
    $planDuration = $_POST['planDuration'];
    // $userId = $_POST['userId'];

    // Corrected SQL queries with single quotes around values
    $sql = "INSERT INTO transaction (t_plan_id, t_user_id, t_amount, t_razorpay_id) VALUES ('$planId', '$userId', '$payableAmount', '$payment_id')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Success";
    } else {
        echo "Failed";
        
    }

    // $sql1 = "INSERT INTO subscription (c_id, p_id, valid_to) VALUES ('$userId', '$planId', DATE_ADD(NOW(), INTERVAL $planDuration DAY))";
    $sql1 = "UPDATE subscription SET valid_to = DATE_ADD(valid_to, INTERVAL $planDuration DAY) WHERE c_id = '$userId' AND p_id = '$planId'";
    $result1 = mysqli_query($con, $sql1);

    if ($result1 && $result) {
        echo "Success";
    } else {
        echo "Failed";
        
    }
}
if (isset($_POST['upgradeStatus'])) {
    $payment_id = $_POST['payment_id'];
    $payableAmount = $_POST['payableAmount'];
    $planId = $_POST['planId'];
    $planDuration = $_POST['planDuration'];
    // $userId = $_POST['userId'];

    // Corrected SQL queries with single quotes around values
    $sql = "INSERT INTO transaction (t_plan_id, t_user_id, t_amount, t_razorpay_id) VALUES ('$planId', '$userId', '$payableAmount', '$payment_id')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Success";
    } else {
        echo "Failed";
        
    }

    // $sql1 = "INSERT INTO subscription (c_id, p_id, valid_to) VALUES ('$userId', '$planId', DATE_ADD(NOW(), INTERVAL $planDuration DAY))";
    $sql1 = "UPDATE subscription SET p_id = '$planId', valid_to = DATE_ADD(valid_to, INTERVAL $planDuration DAY) WHERE c_id = '$userId'";
    $result1 = mysqli_query($con, $sql1);

    if ($result1 && $result) {
        echo "Success";
    } else {
        echo "Failed";
        
    }
}

