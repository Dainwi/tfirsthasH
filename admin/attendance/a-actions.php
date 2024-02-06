<?php
include_once("../../config.php");

if (isset($_POST['present_a'])) {

    $empid = $_POST['empid'];
    $adate = $_POST['adate'];
    $astatus = $_POST['astatus'];

    if (isset($_POST['in_time'])) {
        $time = $_POST['in_time'];
        $newTime = date('h:i A', strtotime($time));
    }


    $sql = "INSERT INTO attendance_db(e_id,a_date,a_time,a_status) VALUES('$empid','$adate','$newTime','$astatus')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['absent_a'])) {

    $empid = $_POST['empid'];
    $adate = $_POST['adate'];
    $astatus = $_POST['astatus'];
    $newTime = 0;


    $sql = "INSERT INTO attendance_db(e_id,a_date,a_time,a_status) VALUES('$empid','$adate','$newTime','$astatus')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['edit_a'])) {
    // echo "<pre>";
    // print_r($_POST);
    $atid = $_POST['atdid'];
    $statusType = $_POST['statusType'];
    if (isset($_POST['in_time'])) {
        $time1 = $_POST['in_time'];
        $nTime = date('h:i A', strtotime($time1));
    } else {
        $nTime = "0";
    }

    $sql = "UPDATE attendance_db SET a_time='$nTime', a_status='$statusType' WHERE a_id={$atid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['timeout_a'])) {
    // echo "<pre>";
    // print_r($_POST);

    $aid = $_POST['atdid'];
    if (isset($_POST['out_time'])) {
        $time2 = $_POST['out_time'];
        $oTime = date('h:i A', strtotime($time2));
    } else {
        $oTime = "0";
    }
    $sql = "UPDATE attendance_db SET out_time='$oTime' WHERE a_id={$aid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['loadData'])) {
    $userId = $_POST['userid'];

    $sql = "SELECT * FROM users_db WHERE u_id={$userId}";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $uname = $row['user_name'];
            $username = $row['user_username'];
            $uphone = $row['user_phone'];
            $uemail = $row['user_email'];
            $uphoto = $row['user_photo'];
            $utype = $row['user_role'];
            $uaddress = $row['user_address'];
        }
    }
?>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-md-flex align-items-center">
                        <div class="text-center text-sm-left ">
                            <div class="avatar avatar-image d-block" style="width: 150px; height:150px">
                                <img src="<?php

                                            if ($uphoto == 0) {
                                                echo $url . "/assets/images/avatars/profile-image.png";
                                            } else {
                                                echo $url . "/assets/images/profile/" . $uphoto;
                                            }
                                            ?>" alt="" class="img-fluid w-75">
                            </div>

                        </div>
                        <div class="text-center text-sm-left m-v-15 p-l-30">
                            <h2 class="m-b-5 text-light"><?php echo $uname ?></h2>
                            <p class="text-opacity font-size-13 text-light"><?php echo $username ?></p>
                            <p class="text-opacity font-size-13 text-light">
                                <?php $sql_type = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userId};";
                                $res_type = mysqli_query($con, $sql_type);

                                if ($res_type) {
                                    while ($r_type = mysqli_fetch_assoc($res_type)) {
                                        echo "<span class='text-dark badge rounded-pill bg-warning'>" . $r_type['type_name'] . "</span> ";
                                    }
                                } ?></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="d-md-block d-none border-left col-1"></div>
                        <div class="col text-left">
                            <ul class="list-unstyled m-t-10">
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light m-b-5">

                                        <span>Email: </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uemail ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light m-b-5">

                                        <span>Phone: </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uphone ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light m-b-5">

                                        <span>Address: </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uaddress ?></p>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php

}
