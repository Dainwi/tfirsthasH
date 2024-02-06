<?php
include 'config.php';

if(isset($_POST['checkCoupon'])){
            
    $couponE = $_POST['couponE'];

    // Sanitize the input to prevent SQL injection
$couponE = mysqli_real_escape_string($con, $couponE);

// Query to check if $couponE exists in the coupon table
$query = "SELECT cou_id, cou_name, cou_status, cou_price FROM coupon WHERE cou_name = '$couponE'";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $couStatus = $row['cou_status'];
        $couPrice = $row['cou_price'];
        $couId = $row['cou_id'];

        if ($couStatus == 1) {
            // Coupon exists and cou_status is 1
            $response = array("code" => 1, "text" => "Coupon Applied!!", "price" => $couPrice, "couponId"=> $couId);
        } elseif ($couStatus == 0) {
            // Coupon exists and cou_status is 0
            $response = array("code" => 2, "text" => "Coupon Expired!!");
        }
    } else {
        // Coupon doesn't match any cou_name
        $response = array("code" => 3, "text" => "Coupon is Invalid.");
    }
} else {
    // Error in the SQL query
    $response = array("code" => 4, "text" => "Error: " . mysqli_error($con));
}

// Encode the response as JSON and echo it
echo json_encode($response);

// if(mysqli_num_rows($result) > 0){
//     $row = mysqli_fetch_assoc($result);
//     if($row){
// }else{
//     echo "Coupon is Invalid!!";
// }
}
if (isset($_POST['paymentStatus'])) {
    $payment_id = $_POST['response.razorpay_payment_id'];
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

    $sql1 = "INSERT INTO subscription (c_id, p_id, valid_to) VALUES ('$userId', '$planId', '$planDuration')";
    $result1 = mysqli_query($con, $sql1);

    if ($result1) {
        echo "Success";
    } else {
        echo "Failed";
        
    }
}
