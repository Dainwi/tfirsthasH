<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../../../config.php');
session_start();
$cusID = $_SESSION['user_id'];

// echo $url;


if (isset($_POST['submit-project'])) {

    $dates = $_POST['date'];
    $ldate = max($dates);
    $sdate = min($dates);

    if ($sdate == $ldate) {
        $ldate = "0000-00-00";
    }

    $client = $_POST['select-client'];

    if ($client == 0) {
        $_SESSION['msg'] = "please select client type";
        echo "<script>window.location=document.referrer;</script>";
    } elseif ($client == 1) {

        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        $project_name = $_POST['project_name'];
        $selectevent = $_POST['select-event'];

        if ($selectevent == 0) {
            echo "<script>window.location=document.referrer;</script>";
        } elseif ($selectevent == 1) {
            $eventcat = $_POST['event_type'];
        } else {
            $eventcat = $selectevent;
        }


        $sql1 = "INSERT INTO clients_db(c_name, c_phone, c_email, c_address, c_auth, c_online_status, cl_time, cus_id) VALUES ('$name', '$phone', '$email', '$address', '0', '0', NOW(), '$cusID')";
        $res1 = mysqli_query($con, $sql1);

        if ($res1) {
            $clid = mysqli_insert_id($con);

$project_name = mysqli_real_escape_string($con, $project_name);
$eventcat = mysqli_real_escape_string($con, $eventcat);
$sdate = mysqli_real_escape_string($con, $sdate);
$ldate = mysqli_real_escape_string($con, $ldate);
$cusID = mysqli_real_escape_string($con, $cusID);

$sql2 = "INSERT INTO projects_db (project_client, project_name, project_event, project_sdate, project_ldate, project_status, cus_id) VALUES ($clid, '$project_name', '$eventcat', '$sdate', '$ldate', 0, '$cusID')";
$res2 = mysqli_query($con, $sql2);

            

            if ($res2) {
                $projectid = mysqli_insert_id($con);
                $e_name = $_POST['event_name'];
                $e_date = $_POST['date'];
                if (isset($_POST['add_venue'])) {
                    $e_text = $_POST['add_venue'];
                } else {
                    $e_text = "";
                }

                foreach ($e_name as $index => $names) {
                    $ename = $names;
                    $edate = $e_date[$index];
                    $etext = $e_text[$index];


                    $sql3 = "INSERT INTO events_db(event_name, event_date, event_venue, event_cl_id, event_pr_id, cus_id) VALUES ('$ename', '$edate', '$etext', '$clid', '$projectid', $cusID)";
$res3 = mysqli_query($con, $sql3);

if (!$res3) {
    // Log or handle the error as per your requirement
    error_log("SQL error in insert query: " . mysqli_error($con));
    // Additional error handling
}

                }
                if ($res3) {
                    header("Location: {$url}/admin/projects/pending-projects.php");
                    exit(0);
                }
            }
        }
    } else {


        $project_name = $_POST['project_name'];
        $selectevent = $_POST['select-event'];
        $clid = $_POST['clid'];

        if ($selectevent == 0) {
            echo "<script>window.location=document.referrer;</script>";
        } elseif ($selectevent == 1) {
            $eventcat = $_POST['event_type'];
        } else {
            $eventcat = $selectevent;
        }

        // $sql2 = "INSERT INTO projects_db(project_client,project_name,project_event,project_sdate,project_ldate,project_status, cus_id)VALUES($clid,'$project_name','$eventcat','$sdate','$ldate',0, $cusID)";
        // $res2 = mysqli_query($con, $sql2);
        
        
        
        
        $clid_escaped = mysqli_real_escape_string($con, $clid);
$project_name_escaped = mysqli_real_escape_string($con, $project_name);
$eventcat_escaped = mysqli_real_escape_string($con, $eventcat);
$sdate_escaped = mysqli_real_escape_string($con, $sdate);
$ldate_escaped = mysqli_real_escape_string($con, $ldate);
$userID_escaped = mysqli_real_escape_string($con, $cusID);

$sql2 = "INSERT INTO projects_db (project_client, project_name, project_event, project_sdate, project_ldate, project_status, cus_id) VALUES ('$clid_escaped', '$project_name_escaped', '$eventcat_escaped', '$sdate_escaped', '$ldate_escaped', 0, '$userID_escaped')";
$res2 = mysqli_query($con, $sql2);
        
        
        

        if($res2){
            if ($res2) {
            $projectid = mysqli_insert_id($con);
            $e_name = $_POST['event_name'];
            $e_date = $_POST['date'];
            if (isset($_POST['add_venue'])) {
                $e_text = $_POST['add_venue'];
            } else {
                $e_text = "";
            }

            foreach ($e_name as $index => $names) {
                $ename = $names;
                $edate = $e_date[$index];
                $etext = $e_text[$index];


                $sql3 = "INSERT INTO events_db(event_name,event_date,event_venue,event_cl_id,event_pr_id, cus_id)VALUES('$ename','$edate','$etext','$clid','$projectid', '$cusID')";
                $res3 = mysqli_query($con, $sql3);
            }
            if ($res3) {
                header("Location: {$url}/admin/projects/pending-projects.php");
                exit(0);
            }
        }
        }
    }
}

if (isset($_POST['deliverablesave'])) {
    // echo "<pre>";
    // print_r($_POST);
    $title = $_POST['title'];
    // $cus_id = mysqli_real_escape_string($con, $_SESSION['user_id']);

    $sql = "INSERT INTO d_layout(dl_name, cus_id) VALUES ('$title', '$cusID')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $lid = mysqli_insert_id($con);
        $item = $_POST['l_item'];


        foreach ($item as $index => $items) {
            $eitem = $items;

            $sql3 = "INSERT INTO dl_items(dli_dlid,dli_text)VALUES('$lid','$eitem')";
            $res3 = mysqli_query($con, $sql3);
        }
        if ($res3) {
            echo "<script>window.location=document.referrer;</script>";
            exit(0);
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}


if (isset($_POST['viewdeliverable'])) {
    $did = $_POST['did'];
    $output = "";
    $sql = "SELECT * FROM dl_items WHERE dli_dlid={$did}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $output .= "<ul>";
        while ($row = mysqli_fetch_assoc($res)) {
            $output .= "<li class='text-white'>{$row["dli_text"]}</li>";
        }
        $output .= "</ul>";
    } else {
        $output .= "no data";
    }
    echo $output;
}


if (isset($_POST['editdeliverable'])) {
    $did = $_POST['did'];
    $output = "";
    $sql = "SELECT * FROM dl_items WHERE dli_dlid={$did}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $output .= "<div>";
        while ($row = mysqli_fetch_assoc($res)) {
            $output .= "<input class='form-control input-item mb-3' data-id='{$row["dli_id"]}' value='{$row["dli_text"]}' >";
        }
        $output .= "</div><form id='form-deliv' action='actions/new-project-action.php' method='post'><input type='hidden' name='newdlivitem' value='{$did}'><div id='form-body'></div></form><div><button class='btn btn-info btn-sm py-2 mt-3' id='btn-addmoree'>Add more</button></div>";
    } else {
        $output .= "no data";
    }
    echo $output;
}


if (isset($_POST['updatedeliv'])) {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $sql = "UPDATE dl_items SET dli_text='$data' WHERE dli_id={$id}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['newdlivitem'])) {
    $lid = $_POST['newdlivitem'];
    $item = $_POST['l_item'];
    foreach ($item as $index => $items) {
        $eitem = $items;

        $sql3 = "INSERT INTO dl_items(dli_dlid,dli_text)VALUES('$lid','$eitem')";
        $res3 = mysqli_query($con, $sql3);
    }
    if ($res3) {
        echo "<script>window.location=document.referrer;</script>";
        exit(0);
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}


if (isset($_POST['btn_del_deliverable'])) {
    $delivid = $_POST['delivid'];
    $sql = "DELETE FROM d_layout WHERE dl_id={$delivid} ;";
    $sql .= "DELETE FROM dl_items WHERE dli_dlid={$delivid}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}
