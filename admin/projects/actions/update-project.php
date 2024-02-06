<?php
include_once('../../../config.php');
session_start();

$cusID = $_SESSION['user_id'];

if (isset($_POST['updateP'])) {

    $prid = $_POST['prid'];
    $prname = $_POST['prname'];

    $prid = mysqli_real_escape_string($con, $prid);
    $prname = mysqli_real_escape_string($con, $prname); // Corrected this line

    $sql = "UPDATE projects_db SET project_name='$prname' WHERE project_id=$prid";
    $res = mysqli_query($con, $sql);

    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['evntdel'])) {
    $evntId = $_POST['evntId'];
    $prid = $_POST['prid'];

    $sql1 = "DELETE FROM events_db WHERE event_id={$evntId}";
    $res1 = mysqli_query($con, $sql1);
    if ($res1) {
        $sql2 = "SELECT * FROM events_db WHERE event_pr_id={$prid}";
        $res2 = mysqli_query($con, $sql2);
        if ($res2) {
            while ($row = mysqli_fetch_array($res2)) {
                $date[] = $row['event_date'];
            }
            $ldate = max($date);
            $sdate = min($date);

            $sql3 = "UPDATE projects_db SET project_sdate='$sdate', project_ldate='$ldate' WHERE project_id={$prid}";
            $res3 = mysqli_query($con, $sql3);
            if ($res3) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }
}



if (isset($_POST['add_event'])) {
    // echo "<pre>";
    // print_r($_POST);
    $c_id = $_POST['c_id'];
    $c_project = $_POST['c_project'];
    $e_name = $_POST['e_name'];
    $e_date = $_POST['e_date'];
    if (isset($_POST['e_venue'])) {
        $e_venue = $_POST['e_venue'];
    } else {
        $e_venue = "";
    }


    $sql = "INSERT INTO events_db(event_name,event_date,event_venue,event_cl_id,event_pr_id, cus_id)VALUES('$e_name','$e_date','$e_venue',$c_id,$c_project,$cusID)";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $sql2 = "SELECT * FROM events_db WHERE event_pr_id={$c_project}";
        $res2 = mysqli_query($con, $sql2);
        if ($res2) {
            while ($row = mysqli_fetch_array($res2)) {
                $date[] = $row['event_date'];
            }
            $ldate = max($date);
            $sdate = min($date);

            $sql3 = "UPDATE projects_db SET project_sdate='$sdate', project_ldate='$ldate' WHERE project_id={$c_project}";
            $res3 = mysqli_query($con, $sql3);
            if ($res3) {
                echo "<script>window.location=document.referrer;</script>";
            } else {
                echo "<script>window.location=document.referrer;</script>";
            }
        }
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}



//update project status ongoing
if (isset($_GET['updateprj']) && isset($_GET['prid'])) {
    $prid = $_GET['prid'];

    $sql = "SELECT * FROM quotation_db WHERE q_project_id = {$prid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $pack = $row['q_package_cost'];
        }

        if ($pack == 0 || $pack == "") {
            $_SESSION['approve-error'] = "please make the quotation before approve";
            echo "<script>window.location=document.referrer;</script>";
        } else {
            $sql5 = "UPDATE projects_db SET project_status=1 WHERE project_id={$prid}";
            $res5 = mysqli_query($con, $sql5);
            if ($res5) {
                echo "<script> location.href ='{$url}/admin/projects/view-ongoing-project.php?prid={$prid}'; </script>";
                exit(0);
            }
        }
    } else {
        $_SESSION['approve-error'] = "please make the quotation before approve";
        echo "<script>window.location=document.referrer;</script>";
    }
}

//delete pending project
if (isset($_POST['btn_del_project'])) {
    $prjid = $_POST['prjid'];

    $sql = "SELECT * FROM quotation_db WHERE q_project_id={$prjid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $qid = $row['q_id'];
        }

        $sql1 = "DELETE FROM projects_db WHERE project_id={$prjid};";
        $sql1 .= "DELETE FROM events_db WHERE event_pr_id={$prjid};";
        $sql1 .= "DELETE FROM quotation_db WHERE q_project_id={$prjid};";
        $sql1 .= "DELETE FROM addons_db WHERE add_quotation_id={$qid}";

        $res1 = mysqli_multi_query($con, $sql1);
        if ($res1) {
            echo "<script> location.href ='{$url}/admin/projects/pending-projects.php'; </script>";
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    } else {
        $sql1 = "DELETE FROM projects_db WHERE project_id={$prjid};";
        $sql1 .= "DELETE FROM events_db WHERE event_pr_id={$prjid}";


        $res1 = mysqli_multi_query($con, $sql1);
        if ($res1) {
            echo "<script> location.href ='{$url}/admin/projects/pending-projects.php'; </script>";
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}


//assign manager
if (isset($_POST['assign_manager'])) {
    $projectId = mysqli_real_escape_string($con, $_POST['project_id']);
    if (isset($_POST['manager_id'])) {
        $managerId = mysqli_real_escape_string($con, $_POST['manager_id']);

        $sql_manager = "INSERT INTO assign_manager_db(am_user_id,am_project_id) VALUES('$managerId','$projectId')";
        $res_manager = mysqli_query($con, $sql_manager);

        if ($res_manager) {
            echo "<script>window.location.reload(history.back());</script>";
            exit(0);
        }
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

//update manager
if (isset($_POST['update_manager'])) {
    $manager_id = $_POST['manager_id'];
    $project_id = $_POST['project_id'];

    $sql = "UPDATE assign_manager_db SET am_user_id=$manager_id WHERE am_project_id={$project_id}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

//btn set complete
if (isset($_POST['btn-set-complete'])) {
    $projid = $_POST['projectidd'];
    $urll = $url . "/admin/projects/completed-project-details.php?prid=" . $projid;
    $sql = "UPDATE projects_db SET project_status=2 WHERE project_id={$projid} ;";
    $sql .= "UPDATE deliverables_assign SET da_completed= 1 WHERE da_pr_id={$projid}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script> location.replace('{$urll}');</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}

//btn delete ongoing project
if (isset($_POST['btn_del_ongoing'])) {
    $prjid = $_POST['prjid'];
    $sql1 = "DELETE FROM projects_db WHERE project_id={$prjid};";
    $sql1 .= "DELETE FROM events_db WHERE event_pr_id={$prjid};";
    $sql1 .= "DELETE FROM quotation_db WHERE q_project_id={$prjid};";
    $sql1 .= "DELETE FROM assign_requirements WHERE ar_project_id={$prjid};";
    $sql1 .= "DELETE FROM deliverables_assign WHERE da_pr_id={$prjid};";
    $sql1 .= "DELETE FROM billing_db WHERE b_project_id={$prjid}";
    $res1 = mysqli_multi_query($con, $sql1);
    if ($res1) {
        echo "<script>window.location.reload(history.back());</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}


// btn delete all 
if (isset($_POST['btn_del_allproj'])) {
    $sql1 = "DELETE FROM projects_db ;";
    $sql1 .= "DELETE FROM events_db ;";
    $sql1 .= "DELETE FROM addons_db ;";
    $sql1 .= "DELETE FROM addons_items ;";
    $sql1 .= "DELETE FROM quotation_db ;";
    $sql1 .= "DELETE FROM deliverables_db ;";
    $sql1 .= "DELETE FROM assign_requirements ;";
    $sql1 .= "DELETE FROM event_requirements ;";
    $sql1 .= "DELETE FROM assign_manager ;";
    $sql1 .= "DELETE FROM deliverables_assign ;";
    $sql1 .= "DELETE FROM billing_db ";
    $res1 = mysqli_multi_query($con, $sql1);
    if ($res1) {
        echo "<script>window.location.reload(history.back());</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}

//delete complete proj
if (isset($_POST['btn_del_completedproj'])) {
    $prjid = $_POST['prjid'];
    $sql1 = "DELETE FROM projects_db WHERE project_id={$prjid};";
    $sql1 .= "DELETE FROM events_db WHERE event_pr_id={$prjid};";
    $sql1 .= "DELETE FROM quotation_db WHERE q_project_id={$prjid};";
    $sql1 .= "DELETE FROM assign_requirements WHERE ar_project_id={$prjid};";
    $sql1 .= "DELETE FROM deliverables_assign WHERE da_pr_id={$prjid};";
    $sql1 .= "DELETE FROM billing_db WHERE b_project_id={$prjid}";
    $res1 = mysqli_multi_query($con, $sql1);
    if ($res1) {
        echo "<script>window.location.reload(history.back());</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}

if (isset($_POST['edit_eventvenue'])) {
    $e_venue = $_POST['e_venue'];
    $c_eventid = $_POST['c_eventid'];
    $sql = "UPDATE events_db SET event_venue='$e_venue' WHERE event_id={$c_eventid}";
    $res1 = mysqli_multi_query($con, $sql);
    if ($res1) {
        echo "<script>window.location.reload(history.back());</script>";
    } else {
        echo "<script>window.location.reload(history.back());</script>";
    }
}
