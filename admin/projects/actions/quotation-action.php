<?php
include_once('../../../config.php');

if (isset($_POST['quotationpending'])) {
    $prid = $_POST['prid'];
    $today = date("Y-m-d");

    $sql = "SELECT * FROM quotation_db WHERE q_project_id = {$prid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $pack = $row['q_package_cost'];
        }
        if ($pack == 0 || $pack == "") {
            echo $url . "/admin/projects/quotation.php?prid=" . $prid;
        } else {
            echo 1;
        }
    } else {
        $sql2 = "INSERT INTO quotation_db (q_project_id,q_date,grand_total) VALUES ('$prid','$today','0')";
        $res2 = mysqli_query($con, $sql2);
        if ($res2) {
            echo $url . "/admin/projects/quotation.php?prid=" . $prid;
        } else {
            echo 0;
        }
    }
}

//requiremnts

if (isset($_POST['requirementChange'])) {
    // echo "<pre>";
    // print_r($_POST);
    $eventid = $_POST['eventid'];
    $reqtype = $_POST['reqtype'];
    $pridd = $_POST['pridd'];

    $sqld1 = "DELETE FROM assign_requirements WHERE ar_event_id={$eventid}";
    $resd1 = mysqli_query($con, $sqld1);
    if ($resd1) {
        $sqld = "DELETE FROM event_requirements WHERE er_event={$eventid} ";
        $resd = mysqli_query($con, $sqld);

        if ($resd) {
            foreach ($reqtype as $index => $reqtypes) {
                $type = $reqtypes;
                $sql = "INSERT INTO event_requirements(er_event,er_type,er_project)VALUES($eventid,$type,$pridd)";
                $res = mysqli_query($con, $sql);
            }

            if ($res) {
                echo "<script>window.location=document.referrer;</script>";
            } else {
                echo "<script>window.location=document.referrer;</script>";
            }
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}



// addons add
if (isset($_POST['addons_quotation_id'])) {

    $quotation = mysqli_real_escape_string($con, $_POST['addons_quotation_id']);
    $title = mysqli_real_escape_string($con, $_POST['add_title']);
    $price = mysqli_real_escape_string($con, $_POST['add_price']);
    $item = $_POST['add_item_desc'][0];

    $sqlq = "SELECT q_package_cost,grand_total FROM quotation_db WHERE q_id={$quotation}";
    $resq = mysqli_query($con, $sqlq);
    if ($resq) {
        while ($rowq = mysqli_fetch_assoc($resq)) {
            $package = $rowq['q_package_cost'];
            $grandTotal = $rowq['grand_total'];
        }
    }

    $sql12 = "INSERT INTO addons_db(add_title,add_price,add_quotation_id)VALUES('$title','$price','$quotation')";
    $res12 = mysqli_query($con, $sql12);
    if ($res12) {
        $add_id = mysqli_insert_id($con);
        if ($item == "") {

            $sql15 = "SELECT add_price FROM addons_db WHERE add_quotation_id ={$quotation}";
            $res15 = mysqli_query($con, $sql15);
            if ($res15) {
                $addPrice = 0;
                while ($row15 = mysqli_fetch_assoc($res15)) {
                    $addPrice += $row15['add_price'];
                }
                $total = $package + $addPrice;
                $addo = $addPrice;

                $sql16 = "UPDATE quotation_db SET grand_total='{$total}',q_addons_cost='{$addo}' WHERE q_id={$quotation}";
                $res16 = mysqli_query($con, $sql16);
                if ($res16) {
                    echo "<script>window.location=document.referrer;</script>";
                } else {
                    echo "<script>window.location=document.referrer;</script>";
                }
            }
        } else {
            $add_item = $_POST['add_item_desc'];
            foreach ($add_item as $index => $items) {
                $items_add = $items;

                $sql13 = "INSERT INTO addons_items(addons_id,addons_items_desc)VALUES('$add_id','$items_add')";
                $res13 = mysqli_query($con, $sql13);
            }
            if ($res13) {
                $sql17 = "SELECT add_price FROM addons_db WHERE add_quotation_id ={$quotation}";
                $res17 = mysqli_query($con, $sql17);
                if ($res17) {
                    while ($row17 = mysqli_fetch_assoc($res17)) {
                        $addPrice += $row17['add_price'];
                    }
                    $addd = $addPrice;
                    $total = $package + $addPrice;

                    $sql18 = "UPDATE quotation_db SET grand_total='{$total}',q_addons_cost='{$addd}' WHERE q_id={$quotation}";
                    $res18 = mysqli_query($con, $sql18);
                    if ($res18) {
                        echo "<script>window.location=document.referrer;</script>";
                    } else {
                        echo "<script>window.location=document.referrer;</script>";
                    }
                }
            } else {
                echo "<script>window.location=document.referrer;</script>";
            }
        }
    }
}


//delete addons

if (isset($_GET['deladdid'])) {
    $addonid = $_GET['deladdid'];
    $quot = $_GET['qid'];

    $sqld = "DELETE FROM addons_db WHERE add_id={$addonid};";
    $sqld .= "DELETE FROM addons_items WHERE addons_id ={$addonid}";
    $resd = mysqli_multi_query($con, $sqld);

    if ($resd) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

//save package cost and grand total

if (isset($_POST['savequotationn'])) {


    $package_value = $_POST['packagecost'];
    $total_price = $_POST['totalprice'];
    $quotation_iddd = $_POST['quotationid'];

    $sql = "UPDATE quotation_db SET q_package_cost='$package_value', grand_total='$total_price' WHERE q_id={$quotation_iddd}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}
