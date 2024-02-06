<?php
include_once("../../config.php");

if (isset($_POST['togglebtn1'])) {

    $clname = $_POST['clname'];
    $clemail = $_POST['clemail'];
    $clphone = $_POST['clphone'];

    if (isset($_POST['verify'])) {
        $verify = 1;
    } else {
        $verify = 0;
    }

    $clid = $_POST['clid'];

    $sql2 = "UPDATE clients_db SET c_auth = '{$verify}' WHERE c_id={$clid}";
    $res2 = mysqli_query($con, $sql2);
    if ($res2) {

        //         if ($verify == 1) {

        //             $to = $clemail;
        //             $subject = "Authentication";

        //             $message = "
        // <html>
        // <head>
        // <title>Authentication</title>
        // </head>
        // <body>
        // <h3>Hello {$clname}</h3>
        // <p>Congratulations, your authentication enabled.</p>
        // <p>check your project status on <a style='color:blue' href='{$url}/clients'>{$url}/clients</a></p>

        // <p style='margin-top:40px !important'>username : {$clemail} </p>
        // <p>password : {$clphone}</p>
        // <br>
        // <div style='margin-top: 30px;'>Thank You</div>
        // <div>Regards,</div>
        // <div>Malakar Photography</div>
        // <div>A Complete Photographic Solution</div>
        // </body>
        // </html>
        // ";

        //             // Always set content-type when sending HTML email
        //             $headers = "MIME-Version: 1.0" . "\r\n";
        //             $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        //             // More headers
        //             $headers .= 'From: contact@malakarphotography.com' . "\r\n";


        //             $resultt = mail($to, $subject, $message, $headers);

        //             if ($resultt == true) {
        //                 echo "<script>window.location.reload(history.back());</script>";
        //                 exit(0);
        //             } else {
        //                 echo "<script>window.location.reload(history.back());</script>";
        //                 exit(0);
        //             }
        //         }
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['clientid'])) {
    $clientid = $_POST['clientid'];
    $cl_name = $_POST['cl_name'];
    $cl_phone = $_POST['cl_phone'];
    $cl_email = $_POST['cl_email'];
    $cl_address = $_POST['cl_address'];

    $sql3 = "UPDATE clients_db SET c_name='$cl_name', c_phone='$cl_phone', c_email='$cl_email', c_address='$cl_address' WHERE c_id={$clientid}";
    $res3 = mysqli_query($con, $sql3);
    if ($res3) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

if (isset($_POST['btn_del_client'])) {
    $clientid = $_POST['delclientid'];


    // $sql3 = "DELETE FROM chat_db WHERE chat_client_id ={$clientid};";
    $sql3 .= "DELETE FROM events_db WHERE event_cl_id ={$clientid};";
    // $sql3 .= "DELETE FROM assign_tasks_db WHERE at_cl_id ={$clientid};";
    $sql3 .= "DELETE FROM clients_db WHERE c_id ={$clientid};";
    $sql3 .= "DELETE FROM projects_db WHERE project_client ={$clientid}";
    $res3 = mysqli_multi_query($con, $sql3);
    if ($res3) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}


if (isset($_POST['btn_del_allclients'])) {
    $sql = "DELETE * FROM clients_db";

    $sql .= "DELETE * FROM deliverables_assign;";
    $sql .= "DELETE * FROM projects_db;";
    $sql .= "DELETE * FROM billing_db;";
    $sql .= "DELETE * FROM quotation_db;";
    $sql .= "DELETE * FROM assign_requirements;";
    $sql .= "DELETE * FROM assign_manager_db;";
    $sql .= "DELETE * FROM events_db;";
    $sql .= "DELETE * FROM event_requirements;";
    $sql .= "DELETE * FROM deliverables_db;";

    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}
