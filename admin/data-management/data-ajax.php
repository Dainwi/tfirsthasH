<?php
include_once("../../config.php");

if (isset($_POST['updatenotes'])) {
    $eid = $_POST['eid'];
    $notes = $_POST['notes'];
    $sql = "UPDATE events_db SET event_note='$notes' WHERE event_id={$eid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}
