<?php
include '../config.php';

// Check if the form is submitted for adding or updating plan
if (isset($_POST['addplan'])) {
    if (empty($_POST['edit_plan_id'])) {
        // Adding a new plan
        $p_name = $_POST['p_name'];
        $p_duration = $_POST['p_duration'];
        $p_price_c = $_POST['p_price_c'];
        $p_price_s = $_POST['p_price_s'];

        $sql = "INSERT INTO plan (p_name, p_duration, p_price_c, p_price_s) VALUES ('$p_name','$p_duration','$p_price_c','$p_price_s')";
    } else {
        // Updating an existing plan
        $edit_plan_id = $_POST['edit_plan_id'];
        $p_name = $_POST['p_name_updated'];
        $p_duration = $_POST['p_duration_updated'];
        $p_price_c = $_POST['p_price_c_updated'];
        $p_price_s = $_POST['p_price_s_updated'];

        $sql = "UPDATE plan SET p_name='$p_name', p_duration='$p_duration', p_price_c='$p_price_c', p_price_s='$p_price_s' WHERE p_id='$edit_plan_id'";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        header("location: index.php");
        exit(0);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

if(isset($_POST['updateStatus'])){
    $pId = $_POST['pId'];
    $statusId = $_POST['statusId'];

    if($statusId == 0){
        $sql = "UPDATE plan SET p_status = 1 WHERE p_id = $pId";
        $result = mysqli_query($con, $sql);
        echo 1;
    }elseif ($statusId == 1) {
        $sql = "UPDATE plan SET p_status = 0 WHERE p_id = $pId";
        $result = mysqli_query($con, $sql);
        echo 2;
    }
}

if(isset($_POST['delStatus'])){
    $pId = $_POST['pId'];

    $sql = "DELETE FROM plan WHERE p_id = $pId";

    $result = mysqli_query($con, $sql);
    echo 1;
}

if (isset($_GET['id'])) {
    $planId = $_GET['id'];
    $sql = "SELECT * FROM plan WHERE p_id = $planId";
    $result = mysqli_query($con, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Return plan details as JSON
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Plan not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid ID']);
}

?>