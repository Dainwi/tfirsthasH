<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$userID = $_SESSION['user_id'];

include_once('../../../config.php');

if (isset($_POST['searchclient'])) {
    $search = $_POST['searchvalue'];

    $response = "";

    $sql = "SELECT * FROM clients_db WHERE LOWER(c_name) LIKE LOWER('%$search%') OR UPPER(c_name) LIKE UPPER('%$search%') OR LOWER(c_phone) LIKE LOWER('%$search%') OR UPPER(c_phone) LIKE UPPER('%$search%') OR LOWER(c_email) LIKE LOWER('%$search%') OR UPPER(c_email) LIKE UPPER('%$search%')";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['c_id'];
            $name = $row['c_name'];
            $email = $row['c_email'];
            $phone = $row['c_phone'];
            $address = $row['c_address'];

            $response .= "<div class='input-group mb-3'>
            <input type='hidden' name='cid' value='{$id}'>
            <input type='hidden' name='cname' value='{$name}'>
            <input type='hidden' name='cphone' value='{$phone}'>
            <input type='hidden' name='cemail' value='{$email}'>
            <input type='hidden' name='caddress' value='{$address}'>
                        <input type='text' class='form-control' name='biname' value='{$row["c_name"]}({$row["c_phone"]})' disabled placeholder='' aria-label='Example text with button addon' aria-describedby='button-addon1'>
                        <button class='btn btn-outline-secondary btn-add' data-id='{$id}' type='button'>SELECT</button>
                    </div>";
        }
    }
    echo $response;
}

if (isset($_POST['newetitle'])) {
    $etitle = $_POST['etitle'];
    $sql = "INSERT INTO event_type(et_title, cus_id)VALUES('$etitle', '$userID')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['neweventtitle'])) {
    $evtitle = $_POST['evtitle'];
    $sql = "INSERT INTO events_list(el_title, cus_id)VALUES('$evtitle', '$userID')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}
