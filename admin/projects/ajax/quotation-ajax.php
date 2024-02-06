<?php
include_once('../../../config.php');
session_start();

$cusID = $_SESSION['user_id'];


if (isset($_POST['gathering'])) {
    $input = $_POST['input'];
    $eventid = $_POST['eventid'];
    $sql = "UPDATE events_db SET event_gathering='$input' WHERE event_id ={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['deliverables'])) {
    $deltext = $_POST['deltext'];
    $eventid = $_POST['eventid'];
    $sql = "UPDATE events_db SET event_deliverables='$deltext' WHERE event_id ={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['requirements'])) {
    // Assuming $con is your database connection and $cusID is already defined and sanitized
    $eid = mysqli_real_escape_string($con, $_POST['eid']); // Sanitize the input

    $sql = "SELECT type_id, type_name FROM employee_type WHERE (cus_id=" . intval($cusID) . " OR cus_id=0) AND type_production=0";
    $res = mysqli_query($con, $sql);

    $response = "";
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $typeid = $row['type_id'];
            
            // Sanitize the typeid before using it in the SQL query
            $sanitizedTypeId = mysqli_real_escape_string($con, $typeid);

            $sql2 = "SELECT * FROM event_requirements WHERE er_event=$eid AND er_type=$sanitizedTypeId";
            $res2 = mysqli_query($con, $sql2);
            if (mysqli_num_rows($res2) > 0) {
                $checked = "checked";
            } else {
                $checked = "";
            }

            // Construct the checkbox HTML
            $response .= "<div class='form-check'>
                              <input class='text-light form-check-input' name='reqtype[]' {$checked} type='checkbox' value='{$row["type_id"]}' id='req{$row["type_id"]}'>
                              <label class='text-light form-check-label' for='req{$row["type_id"]}'>
                                " . htmlspecialchars($row["type_name"], ENT_QUOTES, 'UTF-8') . "
                              </label>
                          </div>";
        }
    }

    echo $response;
}



if (isset($_POST['photobooksave'])) {
    $qid = $_POST['qid'];
    $photobook = $_POST['photobook'];
    $sql = "UPDATE quotation_db SET q_photobook='$photobook' WHERE q_id={$qid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['deliverablesave'])) {
    $qid = $_POST['qid'];
    $deliverable = $_POST['deliverable'];
    $sql = "UPDATE quotation_db SET extra_deliverables='$deliverable' WHERE q_id={$qid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['quotationviewajax'])) {
    $output = "";
    $projid = $_POST['projid'];
    $sqlq = "SELECT * FROM quotation_db WHERE q_project_id={$projid}";
    $resq = mysqli_query($con, $sqlq);
    if (mysqli_num_rows($resq) > 0) {
        while ($rowq = mysqli_fetch_assoc($resq)) {
            $q_id = $rowq['q_id'];
            $q_date = $rowq['q_date'];
            $q_photobook = $rowq['q_photobook'];
            $extra_deliverables = $rowq['extra_deliverables'];
            $package_cost = $rowq['q_package_cost'];
            $addon_cost = $rowq['q_addons_cost'];
            $grand_total = $rowq['grand_total'];
        }
    }
    $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$projid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cname = $row['c_name'];
            $caddress = $row['c_address'];
            $event = $row['project_event'];
            $cemail = $row['c_email'];
        }
    }

    $output .= "<div style='background-color:#181821;'><div class='container px-5 py-4' style='background-color:#181821;'><div class='d-flex justify-content-between'><span><img class='img-fluid d-inline' style='width:45px' src='{$url}/assets/images/logo/logo.png' /><h4 class='text-warning d-inline mt-3 mx-3'>FIRST HASH</h4></span><h3 class='text-light'>QUOTATION</h3></div></div>";
    $output .= "<div class='container px-5 py-4 mb-0 ' style='background-color:#181821;'><div class='col-6' style=' border-left: 1px solid #3d3d3d !important;'>
                    <p class='ms-2 pb-0 mb-0 text-warning'>QUOTATION TO:</p>
                    <h5 class='ms-2 pt-0 pb-0 mb-3 mt-0 text-info'>{$cname}, {$caddress}</h5>
                    <h5 class='ms-2 pb-0 mb-2 text-info'><span class='text-light'>Date : </span> {$q_date}</h5>
                    <h6 class='ms-2 pb-0 mb-0 text-info'><span class='text-light'>Event : </span> {$event}</h6>
                </div>";
    $output .= "<p class='text-primary mt-4'>Event Details :</p>";
    $sql1 = "SELECT * FROM events_db WHERE event_pr_id = {$projid}";
    $res1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($res1) > 0) {
        $output .= "<table class='table table-bordered table-sm'>
                            <thead>
                                <tr>
                                    <th class='text-warning'>Dates</th>
                                    <th class='text-warning'>Event and Venue</th>
                                    <th class='text-warning'>Gathering</th>
                                    <th class='text-warning'>Requirements</th>
                                    <th class='text-warning'>Deliverables</th>

                                </tr>
                            </thead>
                            <tbody>";
        while ($row1 = mysqli_fetch_assoc($res1)) {
            $eve = strtotime($row1['event_date']);
            $eventdate = date("d/m/Y", $eve);
            $eid = $row1['event_id'];

            $output .= "<tr>";
            $output .= "<td class='text-light'>{$eventdate}</td>";
            $output .= "<td class='text-light'>{$row1['event_name']} | {$row1['event_venue']}</td>";
            $gather = $row1['event_gathering'];
            if ($gather == 0 || $gather == "") {
                $gathering = "";
            } else {
                $gathering = $gather;
            }
            $output .= "<td class='text-light'>{$gathering}</td>";
            $sqlr = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type = employee_type.type_id WHERE er_event=$eid";
            $resr = mysqli_query($con, $sqlr);
            $output1 = "";
            if (mysqli_num_rows($resr) > 0) {
                $output1 .= "<ul>";
                while ($rowr = mysqli_fetch_assoc($resr)) {
                    $output1 .= " <li> {$rowr['type_name']}</li>";
                }
                $output1 .= "</ul>";
            }

            $sqldel = "SELECT * FROM event_deliverables WHERE ed_eventid={$eid}";
            $resdel = mysqli_query($con, $sqldel);
            $deliver = "";
            if (mysqli_num_rows($resdel) > 0) {
                $deliver .= "<ul>";
                while ($rowdel = mysqli_fetch_assoc($resdel)) {
                    $deliver .= "<li> {$rowdel['ed_deliverables']} </li>";
                }
                $deliver .= "</ul>";
            } else {
                $deliver = "";
            }

            $output .= "<td class='text-light'>{$output1}</td>";
            $output .= "<td class='text-light'>{$deliver}</td>";

            $output .= "</tr>";
        }

        $output .= "</tbody></table>";
    }

    $output .= "<div class='row mt-3'>";
    if ($q_photobook != "") {
        $output .= "<div class='col-md-6'>
                <p class='text-info mt-2 mb-0'>Photobook</p><textarea class='form-control card-bg textarea-class' readonly disabled>{$q_photobook}</textarea></div>";
    }

    if ($extra_deliverables != "") {
        $output .= "<div class='col-md-6'>
                <p class='text-info mt-2 mb-0'>Extra Deliverables</p><textarea class='form-control card-bg textarea-class' readonly disabled>{$extra_deliverables}</textarea></div>";
    }

    $output .= "</div>";

    $sql_add = "SELECT * FROM addons_db WHERE add_quotation_id ={$q_id}";
    $res_add = mysqli_query($con, $sql_add);
    if (mysqli_num_rows($res_add) > 0) {
        $output .= "<p class='text-info mt-3 mb-0'>Expense Details :</p>";
        while ($row_add = mysqli_fetch_assoc($res_add)) {
            $addid = $row_add['add_id'];

            $output .= "<ul>";
            $output .= "<li class='text-light'>{$row_add['add_title']} (Rs. {$row_add['add_price']})</li>";
            $output .= "<ol>";
            $sql_add_items = "SELECT * FROM addons_items WHERE addons_id ={$addid}";
            $res_add_items = mysqli_query($con, $sql_add_items);
            if (mysqli_num_rows($res_add_items) > 0) {
                while ($row_add_items = mysqli_fetch_assoc($res_add_items)) {
                    $output .= "<li class='text-light'>{$row_add_items['addons_items_desc']}</li>";
                }
            }
            $output .= "</ol>";

            $output .= "</ul>";
        }
    }
    $output .= "<div class='row'><div class='col-md-6'></div><div class='col-md-6 text-end'>
                    
                    <h5 class='text-light'>Package cost ₹ : Rs  {$package_cost} </h5>
                    <p class='text-light'>AddOns : Rs  {$addon_cost} </p>
                    <hr>
                    <h3 class='text-warning'><span class='font-weight-semibold'>Grand Total : Rs </span> <span class=' grand_totall'>{$grand_total}</span></h3>
                </div></div>";


    $output .= "</div></div>";
    echo $output;
}


// if (isset($_POST['quotationviewajax'])) {
//     $output = "";
//     $projid = $_POST['projid'];
//     $sqlq = "SELECT * FROM quotation_db WHERE q_project_id={$projid}";
//     $resq = mysqli_query($con, $sqlq);
//     if (mysqli_num_rows($resq) > 0) {
//         while ($rowq = mysqli_fetch_assoc($resq)) {
//             $q_id = $rowq['q_id'];
//             $q_date = $rowq['q_date'];
//             $q_photobook = $rowq['q_photobook'];
//             $extra_deliverables = $rowq['extra_deliverables'];
//             $package_cost = $rowq['q_package_cost'];
//             $addon_cost = $rowq['q_addons_cost'];
//             $grand_total = $rowq['grand_total'];
//         }
//     }
//     $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$projid}";
//     $res = mysqli_query($con, $sql);
//     if (mysqli_num_rows($res) > 0) {
//         while ($row = mysqli_fetch_assoc($res)) {
//             $cname = $row['c_name'];
//             $caddress = $row['c_address'];
//             $event = $row['project_event'];
//             $cemail = $row['c_email'];
//         }
//     }

//     $output .= "<div style='background-color:#181821;'><div class='container px-5 py-4' style='background-color:#181821;'><div class='d-flex justify-content-between'><span><img class='img-fluid d-inline' style='width:45px' src='{$url}/assets/images/logo/logo.png' /><h4 class='text-warning d-inline mt-3 mx-3'>FIRST HASH</h4></span><h3 class='text-light'>QUOTATION</h3></div></div>";
//     $output .= "<div class='container px-5 py-4 mb-0 ' style='background-color:#181821;'><div class='col-6' style=' border-left: 1px solid #3d3d3d !important;'>
//                     <p class='ms-2 pb-0 mb-0 text-warning'>QUOTATION TO:</p>
//                     <h5 class='ms-2 pt-0 pb-0 mb-3 mt-0 text-info'>{$cname}, {$caddress}</h5>
//                     <h5 class='ms-2 pb-0 mb-2 text-info'><span class='text-light'>Date : </span> {$q_date}</h5>
//                     <h6 class='ms-2 pb-0 mb-0 text-info'><span class='text-light'>Event : </span> {$event}</h6>
//                 </div>";
//     $output .= "<p class='text-primary mt-4'>Event Details :</p>";
//     $sql1 = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type = employee_type.type_id WHERE er_project=$projid GROUP BY er_type";
//     $res1 = mysqli_query($con, $sql1);
//     if (mysqli_num_rows($res1) > 0) {
//         $output .= "<table class='table table-bordered table-sm'>
//                             <thead>
//                                 <tr>
//                                     <th class='text-warning text-center'>EVENTS </th>";
//         while ($row1 = mysqli_fetch_assoc($res1)) {

//             $eve = strtotime($row1['event_date']);
//             $eventdate = date("d/m/Y", $eve);
//             $output .= "<th class='text-warning text-center'>{$eventdate} - {$row1['event_name']}</th>";
//             $eid = $row1['event_id'];
//         }
//         $output .= "</tr></thead><tbody>";
//         $sqlreqt = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type = employee_type.type_id WHERE er_project=$projid GROUP BY er_type ";
//         $resreqt = mysqli_query($con, $sqlreqt);
//         if (mysqli_num_rows($resreqt) > 0) {
//             while ($rowreqt = mysqli_fetch_assoc($resreqt)) {
//                 $reqid = $rowreqt['er_id'];
//                 $output .= "<tr>";
//                 $output .= "<td class='text-white'>{$rowreqt["type_name"]}</td>";

//                 $sqlty = "SELECT * FROM events_db WHERE event_pr_id={$projid} ORDER BY event_id ASC";
//                 $resty = mysqli_query($con, $sqlty);
//                 if (mysqli_num_rows($resty) > 0) {
//                     while ($rowty = mysqli_fetch_assoc($resty)) {
//                         $evid = $rowty['event_id'];

//                         $s = "SELECT * FROM event_requirements WHERE er_id=$reqid AND er_event=$evid";
//                         $r = mysqli_query($con, $s);
//                         if (mysqli_num_rows($r) > 0) {
//                             $output .= "<td>required</td>";
//                         } else {

//                             $output .= "<td></td>";
//                         }
//                     }
//                 }
//                 $output .= "</tr>";
//             }
//         }
//         $output .= "</tbody></table>";
//     }

//     $output .= "<div class='row mt-3'>";
//     if ($q_photobook != "") {
//         $output .= "<div class='col-md-6'>
//                 <p class='text-info mt-2 mb-0'>Photobook</p><textarea class='form-control card-bg textarea-class' readonly disabled>{$q_photobook}</textarea></div>";
//     }

//     if ($extra_deliverables != "") {
//         $output .= "<div class='col-md-6'>
//                 <p class='text-info mt-2 mb-0'>Extra Deliverables</p><textarea class='form-control card-bg textarea-class' readonly disabled>{$extra_deliverables}</textarea></div>";
//     }

//     $output .= "</div>";

//     $sql_add = "SELECT * FROM addons_db WHERE add_quotation_id ={$q_id}";
//     $res_add = mysqli_query($con, $sql_add);
//     if (mysqli_num_rows($res_add) > 0) {
//         $output .= "<p class='text-info mt-3 mb-0'>Expense Details :</p>";
//         while ($row_add = mysqli_fetch_assoc($res_add)) {
//             $addid = $row_add['add_id'];

//             $output .= "<ul>";
//             $output .= "<li class='text-light'>{$row_add['add_title']} (Rs. {$row_add['add_price']})</li>";
//             $output .= "<ol>";
//             $sql_add_items = "SELECT * FROM addons_items WHERE addons_id ={$addid}";
//             $res_add_items = mysqli_query($con, $sql_add_items);
//             if (mysqli_num_rows($res_add_items) > 0) {
//                 while ($row_add_items = mysqli_fetch_assoc($res_add_items)) {
//                     $output .= "<li class='text-light'>{$row_add_items['addons_items_desc']}</li>";
//                 }
//             }
//             $output .= "</ol>";

//             $output .= "</ul>";
//         }
//     }
//     $output .= "<div class='row'><div class='col-md-6'></div><div class='col-md-6 text-end'>

//                     <h5 class='text-light'>Package cost ₹ : Rs  {$package_cost} </h5>
//                     <p class='text-light'>AddOns : Rs  {$addon_cost} </p>
//                     <hr>
//                     <h3 class='text-warning'><span class='font-weight-semibold'>Grand Total : Rs </span> <span class=' grand_totall'>{$grand_total}</span></h3>
//                 </div></div>";


//     $output .= "</div></div>";
//     echo $output;
// }



if (isset($_POST['importdeliv'])) {
    $eid = $_POST['eid'];
    $sql = "SELECT * FROM d_layout WHERE cus_id={$cusID}";
    $res = mysqli_query($con, $sql);
    $output = "";
    if (mysqli_num_rows($res) > 0) {
        $li = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $layoutid = $row['dl_id'];
            $output .= "<h5 class='text-info mb-1'>{$li}. {$row["dl_name"]} <button type='button' data-layoutname='{$row["dl_name"]}' data-layout='{$layoutid}' data-event='{$eid}' class='ms-4 btn btn-sm btn-outline-warning py-1 btn-select-layout'>select</button></h5>";

            $output .= "<ul class='mb-5'>";
            $sql1 = "SELECT * FROM dl_items WHERE dli_dlid={$layoutid}";
            $res1 = mysqli_query($con, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $output .= "<li class='my-0 py-0 text-light'>{$row1["dli_text"]}</li>";
                }
            }
            $output .= "</ul>";
            $li++;
        }
    } else {
        $output .= "no deliverable";
    }
    echo $output;
}


if (isset($_POST['savedeliverableevent'])) {
    $layoutid = $_POST['layoutid'];
    $eventid = $_POST['eventid'];
    $sql = "SELECT * FROM dl_items WHERE dli_dlid={$layoutid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $item = $row['dli_text'];
            $sql1 = "INSERT INTO event_deliverables(ed_eventid,ed_deliverables) VALUES('$eventid','$item')";
            $res1 = mysqli_query($con, $sql1);
        }
        if ($res1) {
            echo 1;
        } else {
            echo 0;
        }
    }
}

if (isset($_POST['btn_snew_deliverable'])) {
    $delivtext = $_POST['delivtext'];
    $delivid = $_POST['delivid'];
    $sql = "INSERT INTO event_deliverables(ed_eventid,ed_deliverables) VALUES('$delivid','$delivtext')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['btn_del_devent'])) {
    $delivid = $_POST['delivid'];
    $sql = "DELETE FROM event_deliverables WHERE ed_id={$delivid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['btn_supdate_deliverable'])) {
    $delivid = $_POST['delivid'];
    $delivtext = $_POST['delivtext'];
    $sql = "UPDATE event_deliverables SET ed_deliverables='$delivtext' WHERE ed_id={$delivid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}
