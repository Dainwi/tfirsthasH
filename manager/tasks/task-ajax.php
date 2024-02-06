<?php
include_once("../../config.php");

if (isset($_POST['tasks'])) {
    $taskId = mysqli_real_escape_string($con, $_POST['taskId']);

    $sqll = "SELECT * FROM daily_tasks WHERE dt_id ={$taskId}";
    $resl = mysqli_query($con, $sqll);
    if (mysqli_num_rows($resl) > 0) {
        while ($row = mysqli_fetch_assoc($resl)) {
            $seen = $row['dt_status_seen'];
            $taskDetails = $row['dt_details'];
            $date = date("jS M-y", strtotime($row['dt_date']));
            $ddate = date("jS M-y", strtotime($row['dt_due_date']));
            $completed = $row['dt_completed'];
            $verify = $row['dt_manager_status'];
            if ($verify == 0) {
                $verification = "<span class='text-danger'>not verified</span>";
            } else {
                $verification = "<span class='text-success'>verified</span>";
            }
            if ($completed == 0) {
                $complete = "<span class='text-danger'>not completed</span> <form action='{$url}/employee/tasks/task-ajax.php' method='post'>
               <input type='hidden' name='taskId' id='task-id' value='{$taskId}'><input type='submit' class='btn btn-secondary btn-tone btn-sm' name='verify' value='set completed'> </form>";
            } else {
                $complete = "<span class='text-success'>completed</span>";
            }

            if ($seen == 0) {
                $sql = "UPDATE daily_tasks SET dt_status_seen = 1 WHERE dt_id = {$taskId}";
                $res = mysqli_query($con, $sql);
            }
        }
        $output = " <input type='hidden' name='taskId' id='task-id' value='{$taskId}'>
        <div class='card p-2 d-flex m-0' style='background:#181821'>
                    <p class=''><small class='text-info me-2'>task date : </small> <span class='date text-info'>{$date}</span></p>
                    <p class=''><small class='text-danger me-2'>Due date : </small> <span class='date text-danger'>{$ddate}</span></p>
                </div>
                <div class='card p-2 m-0 mt-2' style='background:#181821'>
                    <small class='text-gray'>Task Details:</small>
                    <p class='text-light' id='textId'>{$taskDetails}</p>
                </div>

                <div class='card p-2 d-flex m-0 mt-2' style='background:#181821'>
                    <p class=''><small class='text-gray mr-2'>status : </small> <span class='emp-status'>{$complete}</span></p>
                </div>

                <div class='card p-2 d-flex mt-2 mb-0 mt-3' style='background:#181821'>

                    <p class=''><small class='text-gray mr-2'>verification : </small> <span class='verify'>{$verification}</span></p>

                </div>
";
    }
    echo $output;
}

if (isset($_POST['verify'])) {
    $taskid = mysqli_real_escape_string($con, $_POST['taskId']);
    $sql2 = "UPDATE daily_tasks SET dt_completed = 1 WHERE dt_id ={$taskid}";
    $res2 = mysqli_query($con, $sql2);
    if ($res2) {
        echo "<script>window.location.reload(history.back());</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}

if (isset($_POST['setcompletep'])) {
    $delivId = $_POST['deliv'];
    $feildOption = $_POST['feildOption'];
    $sql2 = "UPDATE deliverables_assign SET da_completed = '$feildOption' WHERE da_id ={$delivId}";
    $res2 = mysqli_query($con, $sql2);
    if ($res2) {
        echo 1;
    } else {
        echo 0;
    }
}



if (isset($_POST['viewclientinfo'])) {
    $prid = $_POST['prid'];

    $sqlm = "SELECT * FROM assign_manager_db LEFT JOIN users_db ON assign_manager_db.am_user_id=users_db.u_id WHERE am_project_id={$prid}";
    $resm = mysqli_query($con, $sqlm);
    if (mysqli_num_rows($resm) > 0) {
        while ($rowm = mysqli_fetch_assoc($resm)) {
            $manager = $rowm['user_name'];
        }
    } else {
        $manager = "<small>not assign</small>";
    }

    $response = "<small class='my-4 text-info'>Client Info:</small>";
    $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_id={$prid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cname = $row['c_name'];
            $cphone = $row['c_phone'];
            $cemail = $row['c_email'];
            $caddress = $row['c_address'];
            $suggestion = $row['project_note'];
        }

        $response .= "<div class='row mt-2 mb-4'>";
        $response .= "<div class='col-md-6 mb-2'><small class='d-block text-warning'>NAME</small><h6 class='text-white'>{$cname}</h6></div>";
        $response .= "<div class='col-md-6 mb-2'><small class='d-block text-warning'>PHONE</small><h6 class='text-white'>{$cphone}</h6></div>";
        $response .= "<div class='col-md-6 mb-2'><small class='d-block text-warning'>EMAIL</small><h6 class='text-white'>{$cemail}</h6></div>";
        $response .= "<div class='col-md-6 mb-2'><small class='d-block text-warning'>ADDRESS</small><h6 class='text-white'>{$caddress}</h6></div>";
        $response .= "</div>";
        $response .= "<div class='my-3 d-flex'><h6 class='text-warning'>PROJECT MANAGER : <span class='text-white'>{$manager}</span></h6></div>";

        $response .= "<small class='my-4 text-info'>Events:</small>";
        $response .= "<div class='table-responsive'>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th scope='col' class='text-info'>#</th>
                            <th scope='col' class='text-info'>Event name</th>
                            <th scope='col' class='text-info'>Date</th>
                            <th scope='col' class='text-info'>Event Venue</th>
<th scope='col' class='text-info'>Data stored in</th>
                        </tr>
                    </thead>
                    <tbody>";
        $sql1 = "SELECT * FROM events_db WHERE event_pr_id={$prid}";
        $res1 = mysqli_query($con, $sql1);

        if (mysqli_num_rows($res1)) {
            $ie = 1;
            while ($row1 = mysqli_fetch_assoc($res1)) {
                $datt = date_create($row1['event_date']);
                $dat = date_format($datt, 'd M Y');
                $response .= "<tr>";
                $response .= "<td class='text-light'>{$ie}</td>";
                $response .= "<td class='text-light'>{$row1['event_name']}</td>";
                $response .= "<td class='text-light'>{$dat}</td>";
                $response .= "<td class='text-light'>{$row1['event_venue']}</td>";
                $response .= "<td class='text-light'>{$row1['event_note']}</td>";
                $response .= "</tr>";
                $ie++;
            }
        }

        $response .= " </tbody>
                </table>
            </div>
            <small class='my-4 text-info'>Suggestions: <button type='button' data-id='{$prid}' data-note='{$suggestion}' class='ms-3 btn btn-outline-secondary btn-sm border-dark btn-edit-suggestion'><i class='fas fa-pencil-alt'></i></button></small>
            <div class='card p-2 border border-dark text-white div-suggestion'>{$suggestion}</div>";
    }

    echo $response;
}



if (isset($_POST['updatelinkurl'])) {
    $url = $_POST['url'];
    $dlid = $_POST['dlid'];
    $sql = "UPDATE deliverables_assign SET da_link='$url' WHERE da_id={$dlid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['updatesuggestionm'])) {
    $pridd = $_POST['pridd'];
    $note = $_POST['note'];
    $sql = "UPDATE projects_db SET project_note='$note' WHERE project_id={$pridd}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['newestatustype'])) {
    $etitle = $_POST['etitle'];
    $sql = "INSERT INTO task_status(ts_title)VALUES('$etitle')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}
