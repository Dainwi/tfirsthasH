<?php
include_once("../../config.php");
session_start();
$cusID = $_SESSION['cus_id'];

// echo $cusID;


//assign requirements
if (isset($_POST['assignreq'])) {
    $rid = $_POST['rid'];
    $evntid = $_POST['evntid'];
    $evntdate = $_POST['evntdate'];
    $emptype = $_POST['emptype'];
    

    $date = date($evntdate);
    $response = "";
    // $sql = "SELECT * FROM employee_position LEFT JOIN users_db ON employee_position.ep_user_id=users_db.u_id WHERE ep_type={$emptype} AND users_db.user_active=1 AND users_db.cus_id={$cusID}";
    $sql="SELECT users_db.u_id, users_db.user_name, users_db.user_username, employee_type.type_name 
            FROM employee_position 
            LEFT JOIN users_db ON employee_position.ep_user_id = users_db.u_id 
            LEFT JOIN employee_type ON employee_position.ep_type = employee_type.type_id 
            WHERE ep_type = '{$emptype}' AND users_db.user_active = 1 AND users_db.cus_id = '{$cusID}'";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        $response .= "<input type='hidden' name='erid' value='{$rid}'> <input type='hidden' name='eventid' value='{$evntid}'><input type='hidden' name='evntdate' value='{$evntdate}'>";
        while ($row = mysqli_fetch_assoc($res)) {
            $userid = $row['u_id'];
            $username = $row['user_name'];
            $uname = $row['type_name'];
            $sql1 = "SELECT * FROM assign_requirements WHERE ar_date='$date' AND ar_assign_to={$userid} AND ar_requirement_id={$rid} AND ar_event_id={$evntid}";
            $res1 = mysqli_query($con, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' checked type='checkbox' value='{$userid}' id='checkr{$userid}'>
                                        <label class='form-check-label text-light' for='checkr{$userid}'>
                                          {$username} <small>({$uname})</small>
                                        </label>
                                      </div>";
            } else {
                // $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' type='checkbox' value='{$userid}' id='checkr{$userid}'>
                //                         <label class='form-check-label text-light' for='checkr{$userid}'>
                //                           {$username} <small>({$uname})</small>
                //                         </label>
                //                       </div>";

                $sql2 = "SELECT * FROM assign_requirements WHERE ar_date ='$date' AND ar_assign_to={$userid}";
                $res2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($res2) > 0) {
                } else {
                    $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' type='checkbox' value='{$userid}' id='checkr{$userid}'>
                                        <label class='form-check-label text-light' for='checkr{$userid}'>
                                          {$username} <small>({$uname})</small>
                                        </label>
                                      </div>";
                }
            }
        }
    } else {
        $response .= "No employee found";
    }

    echo $response;
}


if (isset($_POST['req-submit'])) {
    // echo "<pre>";
    // echo print_r($_POST);
    $erid = $_POST['erid'];
    $prid = $_POST['projectid'];
    $eventid = $_POST['eventid'];
    $evntdate = $_POST['evntdate'];


    $datee = date_create($evntdate);

    $output = "";
    $sql = "DELETE FROM assign_requirements WHERE ar_requirement_id={$erid}";
    $res = mysqli_query($con, $sql);

    if ($res) {
        if (isset($_POST['assignto'])) {
            $assignto = $_POST['assignto'];
            foreach ($assignto as $index => $item_id) {
                $assigntos = $item_id;

                $sql1 = "INSERT INTO assign_requirements(ar_requirement_id,ar_event_id,ar_date,ar_assign_to,ar_project_id)VALUES($erid,$eventid,'$evntdate',$assigntos,$prid)";
                $res1 = mysqli_query($con, $sql1);
            }

            if ($res1) {
                echo "<script>window.location=document.referrer;</script>";
            } else {
                echo "<script>window.location=document.referrer;</script>";
            }
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}
