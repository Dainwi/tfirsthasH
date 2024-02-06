<?php
include "../config.php";

if (isset($_GET['id'])) {
    $couponId = $_GET['id'];
    $sql = "SELECT * FROM coupon WHERE cou_id = $couponId";
    $result = mysqli_query($con, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Return plan details as JSON
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Coupon not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid ID']);
}


if (isset($_POST['updateStatus'])) {
    $couId = $_POST['couId'];
    $statusId = $_POST['statusId'];

    if ($statusId == 0) {
        $sql = "UPDATE coupon SET cou_status = 1 WHERE cou_id = $couId";
        $result = mysqli_query($con, $sql);
        echo 1;
    } elseif ($statusId == 1) {
        $sql = "UPDATE coupon SET cou_status = 0 WHERE cou_id = $couId";
        $result = mysqli_query($con, $sql);
        echo 2;
    }
}


if(isset($_POST['delStatus'])){
    $cId = $_POST['cId'];

    $sql = "DELETE FROM coupon WHERE cou_id = $cId";

    $result = mysqli_query($con, $sql);
    echo 1;
}
?>