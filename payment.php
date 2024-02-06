<?php
include "config.php";
session_start();

if (isset($_POST['paymentStatus'])) {
    $payment_id = $_POST['payment_id'];
    $payableAmount = $_POST['payableAmount'];
    $planId = $_POST['planId'];
    $planDuration = $_POST['planDuration'];
    $couId = $_POST['couId'];
    $userId = $_POST['userId'];

    // Corrected SQL queries with single quotes around values
    $sql = "INSERT INTO transaction (t_plan_id, t_coupon_id, t_user_id, t_amount, t_razorpay_id) VALUES ('$planId', '$couId', '$userId', '$payableAmount', '$payment_id')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Success";
    } else {
        echo "Failed";
        
    }

    $sql1 = "INSERT INTO subscription (c_id, p_id, valid_to) VALUES ('$userId', '$planId', DATE_ADD(NOW(), INTERVAL $planDuration DAY))";
    $result1 = mysqli_query($con, $sql1);

    if ($result1) {
        echo "Success";
    } else {
        echo "Failed";
        
    }
}
echo"<script>console.log('This is Payment.php');</script>";