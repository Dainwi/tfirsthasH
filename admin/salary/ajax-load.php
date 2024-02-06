<?php
include_once("../../config.php");
if (isset($_POST['salary'])) {
    $userid = $_POST['userid'];
    $lastpaid = $_POST['lastpaid'];
    $date = date("Y/m/d");

    $start    = (new DateTime($lastpaid))->modify('first day of this month');
    $end      = (new DateTime($date))->modify('first day of next month');
    $interval = DateInterval::createFromDateString('1 month');
    $period   = new DatePeriod($start, $interval, $end);
    $r = 0;
    echo "<label class='mb-4 text-warning'>Select Paid Months :</label><input type='hidden' value='1'name='update-s'> <input type='hidden' name='us_id' id='us_id' value='{$userid}'>";
    foreach ($period as $dt) {
        echo "<div class='checkbox text-secondary'>
    <input id='{$dt->format("Y-m")}' name='month[]' value='{$dt->format("Y-m-d")}' type='checkbox' class='bg-secondary'>
    <label for='{$dt->format("Y-m")}' class='text-light'>" . $dt->format("F-Y") . "</label>
</div><br>\n";
        $r++;
    }
}


if (isset($_POST['task'])) {
    $today = date("Y-m-d");
    $useId = $_POST['userid'];
    $sql1 = "SELECT * FROM assign_requirements WHERE ar_assign_to = {$useId} AND ar_paid_status = 0 AND ar_date < '$today'";
    $res1 = mysqli_query($con, $sql1);
    echo "<label class='mb-4 text-warning'>Select Paid Tasks :</label><input type='hidden' value='1'name='update-t'> <input type='hidden' name='us_id1' id='usid' value='{$useId}'>";
    if (mysqli_num_rows($res1) > 0) {
        echo "<input type='hidden' value='1' name='emp'>";
        while ($row = mysqli_fetch_assoc($res1)) {
            $evntId = $row['ar_event_id'];
            $sqll = "SELECT * FROM events_db LEFT JOIN projects_db ON events_db.event_pr_id = projects_db.project_id WHERE event_id={$evntId}";
            $resl = mysqli_query($con, $sqll);
            if ($resl) {
                while ($rowl = mysqli_fetch_assoc($resl)) {

                    $evntName = $rowl['event_name'];
                    $evntDate = $rowl['event_date'];
                    $evntProject = $rowl['project_name'];
                }
                echo "<div class='input-group checkbox text-light'>
    <input id='{$row["ar_id"]}' name='tasks[]' value='{$row["ar_id"]}' type='checkbox' class='text-light'>
    <label for='{$row["ar_id"]}' class='text-info my-auto mx-4'>" . $evntProject . " : " . $evntName . " : " . $evntDate . "</label>
    <small class='my-auto mx-3'>Task Amount (INR) : </small><input name='amount[]' value='{$row["ar_amount"]}' type='text' class='form-control text-light' style='max-width:105px'>
</div><br>";
            }
        }
    } else {
        $sql2 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$useId} AND da_paid_status=0 AND da_status=1";
        $res2 = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res2) > 0) {
            echo "<input type='hidden' value='1' name='emp'>";
            while ($row2 = mysqli_fetch_assoc($res2)) {
                $daid = $row2['da_id'];
                $daprid = $row2['da_pr_id'];
                $item = $row2['da_item'];
                $amt = $row2['da_amount'];

                $sql3 = "SELECT project_name FROM projects_db WHERE project_id={$daprid}";
                $res3 = mysqli_query($con, $sql3);
                if ($res3) {
                    while ($row3 = mysqli_fetch_assoc($res3)) {
                        $prname = $row3['project_name'];
                    }
                    echo "<div class='input-group checkbox text-light'>
    <input id='{$daid}' name='deliverable[]' value='{$daid}' type='checkbox' class='text-light'>
    <label for='{$daid}' class='text-info my-auto mx-4'>" . $prname . " : " . $item  . "</label>
    <small class='my-auto mx-3'>Task Amount (INR) : </small><input name='amount[]' value='{$amt}' type='text' class='form-control text-light' style='max-width:105px'>
</div><br>";
                }
            }
        } else {
            echo "no data";
        }
    }
}


if (isset($_POST['update-s'])) {
    $usid =  $_POST['us_id'];
    $smonth = $_POST['month'];
    $date = date("Y/m/d");

    foreach ($smonth as $months) {
        $sql = "INSERT INTO salary_details(sd_user_id,sd_month,sd_paid_on)VALUES('$usid','$months','$date')";
        $res = mysqli_query($con, $sql);
    }
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['update-t'])) {
    $usid = $_POST['us_id1'];
    $date1 = date("Y-m-d");
    if (isset($_POST['tasks'])) {

        $reqid = $_POST['tasks'];
        $amount = $_POST['amount'];
        foreach ($reqid as $index => $tasks) {
            $requiredid = $tasks;
            $amounts = $amount[$index];
            $sql3 = "UPDATE assign_requirements SET ar_paid_status = 1, ar_paid_date='$date1', ar_amount={$amounts} WHERE ar_id ={$requiredid}";
            $res3 = mysqli_query($con, $sql3);
        }
        if ($res3) {
            echo 1;
        } else {
            echo 0;
        }
    } elseif (isset($_POST['deliverable'])) {

        $deliverable = $_POST['deliverable'];

        $amount = $_POST['amount'];
        foreach ($deliverable as $index => $tasks) {
            $deliverables = $tasks;
            $amounts = $amount[$index];
            $sql3 = "UPDATE deliverables_assign SET da_paid_status = 1, da_paid_date='$date1', da_amount={$amounts} WHERE da_id ={$deliverables}";
            $res3 = mysqli_query($con, $sql3);
        }
        if ($res3) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}

// if (isset($_POST['update-t'])) {
//     $usid =  $_POST['us_id1'];
//     $taskId = $_POST['tasks'];
//     $date1 = date("Y-m-d");

//     if (isset($_POST['emp'])) {
//         foreach ($taskId as $tasks) {
//             $sql3 = "UPDATE assign_tasks_db SET at_paid_status = 1, at_paid_on='$date1' WHERE at_id ={$tasks}";
//             $res3 = mysqli_query($con, $sql3);
//         }
//         if ($res3) {
//             echo 1;
//         } else {
//             echo 0;
//         }
//     }
// }
