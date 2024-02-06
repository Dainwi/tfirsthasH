<?php
include_once("../../config.php");

if (isset($_POST['save_task'])) {


    $empid = $_POST['emp_assign'];
    $task = $_POST['task_details'];
    $taskDate = $_POST['name_date'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO daily_tasks(dt_user_id,dt_details,dt_date,dt_due_date) VALUES('{$empid}','{$task}','{$taskDate}','{$due_date}')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['deltask'])) {
    $taskid = $_POST['taskid'];
    $sql1 = "DELETE FROM daily_tasks WHERE dt_id={$taskid}";
    $res1 = mysqli_query($con, $sql1);
    if ($res1) {
        echo true;
    }
}

if (isset($_POST['viewdetails'])) {
    $task_id = $_POST['taskidd'];
    $sql = "SELECT * FROM daily_tasks LEFT JOIN users_db ON users_db.u_id = daily_tasks.dt_user_id WHERE dt_id={$task_id}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $userid = $row['u_id'];
            $username = $row['user_name'];
            $userrole = $row['user_role'];
            $tasks = $row['dt_details'];
            $taskstatus = $row['dt_completed'];
            $taskcompleted = $row['dt_manager_status'];
            $date = $row['dt_date'];
        }
        if ($taskstatus == 0) {
            $com = "<span class='text-danger'>not completed</span>";
        } else {
            $com = "<span class='text-success'>completed</span>";
        }

        if ($taskcompleted == 1) {
            $man = "<small class='text-success'>verified</small>";
        } else {
            $man = "<small class='text-danger'>not verified</small> <button class='ms-2 btn btn-sm btn-success btn-check1'>set completed</button>";
        }
    }


    if ($userrole = 1) {
        $role = "manager";
    } else {
        $sql2 = "SELECT users.u_id,employee_position.*,position_type.* FROM users INNER JOIN employee_position ON users.u_id = employee_position.ep_user_id INNER JOIN position_type ON employee_position.ep_type = position_type.e_id WHERE users.u_id={$userid}";
        $res2 = mysqli_query($con, $res2);
        if (mysqli_num_rows($res2) > 0) {
            while ($r_type2 = mysqli_fetch_array($res2)) {
                $role = $r_type2['e_name'] . ", ";
            }
        }
    }
    echo "<h4 class='modal-title w-100 d-block fw-bold text-center text-warning' id='view-taskUser'>{$username}</h4>
                    <small class='text-center d-block' id='view-userType'>{$role}</small><input type='hidden' id='userTaskId' name='user_id_task'>
            
                <div class='card card-bg p-2 d-flex m-0'>
                <p class='py-0 my-0'><small class='text-light mr-2'>TASK DATE : </small> <span class='emp-status text-light'>{$date}</span></p>
                <p class='py-0 my-0'><small class='text-light mr-2'>DUE DATE : </small> <span class='emp-status text-danger'>{$date}</span></p>
                    <p class='py-0 my-0'><small class='text-light mr-2'>status : </small> <span class='emp-status text-light'>{$com}</span></p>
                </div>
                <div class='card card-bg p-2 m-0'>
                    <small class='text-secondary'>TASK DETAILS:</small>
                    <p class='text-light' id='textId'>{$tasks}</p>
                </div>
                <div class='card card-bg p-2 d-flex mt-2 mb-0'>
                <input type='hidden' name='taskId' value='{$task_id}'>
                    <p class=''><small class='text-light me-2'>verify : </small> <span class='verify'>{$man}</span></p>
                </div>";
}

if (isset($_POST['completeverify'])) {
    $taskid = $_POST['taskid'];

    $sql = "UPDATE daily_tasks SET dt_manager_status=1,dt_completed=1 WHERE dt_id ={$taskid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['update_d_task'])) {
    $task = $_POST['task_details'];
    $taskid = $_POST['taskid'];
    $ddate = $_POST['due_date'];

    $sql = "UPDATE daily_tasks SET dt_details='$task', dt_due_date='$ddate' WHERE dt_id={$taskid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}
