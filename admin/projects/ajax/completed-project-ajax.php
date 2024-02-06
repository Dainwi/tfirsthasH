<?php
include_once('../../../config.php');


if (isset($_POST['eventsload'])) {
    $proj = $_POST['proj'];
    $output = "";
    $sql = "SELECT * FROM events_db WHERE event_pr_id={$proj}";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        $iem = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $eid = $row['event_id'];
            $ename = $row['event_name'];
            $edate1 = date_create($row['event_date']);
            $edate = date_format($edate1, "d M Y");
            $evenue = $row['event_venue'];
            $enote = $row['event_note'];
            $egather = $row['event_gathering'];
            $deliverables = $row['event_deliverables'];
            
            
            


            if ($egather == 0 || $egather == "") {
                $gather = "n/a";
            } else {
                $gather = $egather;
            }

            $output .= "<div class='mb-3 border border-dark p-3'>";

            $output .= "<h5 class='text-light'><span class='text-warning me-2 fw-bold'>{$iem} . </span>{$ename}</h5>
            <div class='border-default d-flex align-items-center p-2 mt-3 rounded'>
            <p class='text-light ms-4 py-0 my-0'><span class='text-info'>Date </span> : {$edate}</p>
            <p class='text-light ms-4 py-0 my-0'><span class='text-info'>Gathering </span> : {$egather}</p>
            <p class='text-light ms-4 py-0 my-0'><span class='text-info'>Venue </span> : {$evenue}</p>
            </div>";

            $output .= "<p class='mt-3'></p>";

            $output .= "<table class='table table-bordered' style='background-color:#181821;'><thead><th class='text-warning'>Requirements</th><th class='text-warning'>assign to</th></thead><tbody>";

            $sql1 = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type=employee_type.type_id WHERE er_event={$eid}";
            $res1 = mysqli_query($con, $sql1);

            if (mysqli_num_rows($res1) > 0) {
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $rtype = $row1['er_type'];
                    $rid = $row1['er_id'];
                    $output .= "<tr>";
                    $output .= "<td class='text-primary'>{$row1["type_name"]}</td><td>";

                    $output .= "<input type='hidden' name='erid' value='{$rid}'><input type='hidden' name='evntid' value='{$eid}'><input type='hidden' name='evntdate' value='{$edate}' >
                   <input type='hidden' name='emptype' value='{$rtype}' > ";

                    $sql3 = "SELECT * FROM assign_requirements LEFT JOIN users_db ON users_db.u_id = assign_requirements.ar_assign_to  WHERE ar_requirement_id={$rid}";
                    $res3 = mysqli_query($con, $sql3);
                    if (mysqli_num_rows($res3) > 0) {
                        $output .= "<div class='d-flex cdiv{$rid}'>";
                        while ($row3 = mysqli_fetch_assoc($res3)) {

                            $output .= "<li class='me-4'>{$row3["user_name"]}</li>";
                        }
                        $output .= "</div>";
                    } else {

                        $output .= "<div class='d-flex cdiv{$rid}'><small>Team not assign</small></div>";
                    }

                    $output .= "</td></tr>";
                }
            }

            $sqled = "SELECT * FROM event_deliverables WHERE ed_eventid={$eid}";
            $resed = mysqli_query($con, $sqled);
            $rep = "";
            if (mysqli_num_rows($resed) > 0) {

                while ($rowed = mysqli_fetch_assoc($resed)) {
                    $rep .= "<p class='text-light py-0 my-0'>{$rowed["ed_deliverables"]}</p>";
                }
            }

            $output .= "</tbody></table>";
            $output .= " <div class='row mt-4'><div class='col-md-6'>
            <div class='card' data-event='{$eid}'><small for='deliverable-e{$eid}'>Deliverable</small>
                <div class='form-control deliverable-e' disabled id='deliverable-e{$eid}' placeholder='deliverable' >{$rep}</div>
                </div></div>
                <div class='col-md-6'>
            <div class='card' data-event='{$eid}'>
             <small for='comments-e{$eid}'>Notes</small>
                <div class='form-control card comment-e' disabled id='comments-e{$eid}' placeholder='Notes'>{$enote}</div>
               </div></div></div></div>";
            $iem++;
        }
    }

    echo $output;
}




// if (isset($_POST['layoutdeliverable'])) {
//     $sql = "SELECT * FROM layout_db";
//     $res = mysqli_query($con, $sql);
//     $output = "";
//     if (mysqli_num_rows($res) > 0) {
//         $li = 1;
//         while ($row = mysqli_fetch_assoc($res)) {
//             $layoutid = $row['layout_id'];
//             $output .= "<h5 class='text-info mb-4'>{$li}. {$row["layout_name"]} <button type='button' data-layoutname='{$row["layout_name"]}' data-layout='{$layoutid}' class='ms-4 btn btn-sm btn-outline-warning py-1 btn-select-layout'>select</button></h5>";

//             $li++;
//         }
//     } else {
//         $output .= "no layout found";
//     }
//     echo $output;
// }



if (isset($_POST['deliverabletab'])) {
    $projectid = $_POST['projectid'];

    $response = "";
    $sql = "SELECT * FROM deliverables_db WHERE d_pr_id={$projectid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $di = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $did = $row['d_id'];
            $duedate = $row["d_due_date"];
            if ($duedate == "0000-00-00" || $duedate == "") {
                $dudate = "";
            } else {
                $dudate = $duedate;
            }
            $response .= " <h5 class='text-light mt-4'>{$di}. <span class='ms-3'>{$row["d_layout"]}</span>
           <small class='ms-5 me-2'>Due Date :</small><span>{$dudate}</span></h5>
             ";
            $response .= "<table class='table table-bordered' style='background-color:#181821;'><thead><th class='text-warning'>Deliverables</th><th class='text-warning'>assign to</th><th class='text-warning'>Due Date</th><th class='text-warning'>Status</th></thead><tbody>";

            $sql1 = "SELECT * FROM deliverables_assign WHERE da_d_id={$did}";
            $res1 = mysqli_query($con, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $daid = $row1['da_id'];
                    $sastatus = $row1['da_status'];
                    $ddate = $row1['da_due_date'];
                    if ($ddate == "0000-00-00") {
                        $date = "";
                    } else {
                        $date = $ddate;
                    }

                    if ($sastatus == 0) {
                        $ss = "<p class='text-danger d-inline'>pending</p>";
                    } else {
                        $ss = "<p class='text-success'>completed</p>";
                    }
                    $response .= "<tr>";
                    $response .= "<td>{$row1["da_item"]}</td>";

                    //assigned post production
                    if ($row1['da_assign_to'] == 0) {
                        $assigned = "";
                    } else {
                        $sqln = "SELECT * FROM deliverables_assign WHERE da_assign_to={$row1['da_assign_to']} AND da_status=0";
                        $resn = mysqli_query($con, $sqln);
                        $numr = mysqli_num_rows($resn);

                        if ($numr > 1) {
                            $task = "tasks - p";
                        } else {
                            $task = "task - p";
                        }

                        $sqlu = "SELECT user_name FROM users_db WHERE u_id={$row1['da_assign_to']}";
                        $resu = mysqli_query($con, $sqlu);
                        while ($rowu = mysqli_fetch_assoc($resu)) {
                            $usernamee = $rowu['user_name'];
                        }
                        $assigned =  "{$usernamee}";
                    }

                    $response .= "<td>{$assigned}</td>";
                    $response .= "<td class='text-info'>{$date}</td>";
                    $response .= "<td>{$ss}</td>";

                    $response .= "</tr>";
                }
            }


            $response .= "</tbody></table>";
            $di++;
        }
    }

    $sqle = "SELECT * FROM events_db WHERE event_pr_id={$projectid}";
    $rese = mysqli_query($con, $sqle);
    if (mysqli_num_rows($rese) > 0) {
        while ($rowe = mysqli_fetch_assoc($rese)) {

            $sqled = "SELECT * FROM event_deliverables WHERE ed_eventid={$rowe['event_id']}";
            $resed = mysqli_query($con, $sqled);
            $rep = "";
            if (mysqli_num_rows($resed) > 0) {

                while ($rowed = mysqli_fetch_assoc($resed)) {
                    $rep .= "<p class='text-light py-0 my-0'>{$rowed["ed_deliverables"]}</p>";
                }
            }
            $response .= "<p class='text-info mt-3 pb-0 mb-1'>{$rowe['event_name']} ({$rowe['event_date']})</p>";
            $response .= "<div class='row border-default'>
            <div class='col-md-6 border-end border-secondary'>
            <small>Deliverables : </small>
           {$rep}
            </div>
            <div class='col-md-6'>
            <small>Notes : </small>
            <p class='text-light'>{$rowe['event_note']}</p>
            </div>
            </div>";
        }
    }

    $sqlm = "SELECT * FROM quotation_db WHERE q_project_id={$projectid}";
    $resm = mysqli_query($con, $sqlm);
    if (mysqli_num_rows($resm) > 0) {
        while ($rowm = mysqli_fetch_assoc($resm)) {
            $edeliverable = $rowm['extra_deliverables'];
            $photobook = $rowm['q_photobook'];
        }
        $response .= "";
        $response .= "<div class='row border-default mt-5'>
            <div class='col-md-6 border-end border-secondary'>
            <h6 class='text-info mt-3 pb-0 mb-1'>Extra Deliverables :</h6>
            <p class='text-light'>{$edeliverable}</p>
            </div>
            <div class='col-md-6'>
            <h6 class='text-info mt-3 pb-0 mb-1'>Photobook :</h6>
            <p class='text-light'>{$photobook}</p>
            </div>
            </div>";
    }


    echo $response;
}




if (isset($_POST['assigndeliverablep'])) {
    $assigndid = $_POST['assigndid'];
    $output = "";
    $sql = "SELECT * FROM deliverables_assign WHERE da_id={$assigndid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $aemployee = $row['da_assign_to'];
        }

        $sql1 = "SELECT * FROM employee_position LEFT JOIN employee_type ON employee_position.ep_type=employee_type.type_id WHERE employee_type.type_production=1 GROUP BY employee_position.ep_user_id";
        $res1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($res1) > 0) {
            $output .= "<select class='form-control' id='select-deliverables'>";
            $output .= "<option value='0'>SELECT EMPLOYEE</option>";
            while ($row1 = mysqli_fetch_assoc($res1)) {
                $userid = $row1['ep_user_id'];

                $sqln = "SELECT * FROM deliverables_assign WHERE da_assign_to={$userid}";
                $resn = mysqli_query($con, $sqln);
                $rownn = mysqli_num_rows($resn);
                if ($rownn > 1) {
                    $tasks = "tasks pending";
                } else {
                    $tasks = "task pending";
                }

                if ($aemployee == $userid) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }

                $sql2 = "SELECT * FROM users_db WHERE u_id={$userid}";
                $res2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($res2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($res2)) {
                        $name = $row2['user_name'];
                    }
                }

                $output .= "<option value='{$userid}' {$selected}>{$name} ({$rownn} {$tasks})</option>";
            }

            $output .= "</select>";
        } else {
            $output .= "no employee";
        }
    } else {
        $output .= 0;
    }

    echo $output;
}


//financials
if (isset($_POST['financialstabb'])) {
    $project = $_POST['project'];

    $response = "";
    $response1 = "";

    $sql_q = "SELECT * FROM quotation_db WHERE q_project_id={$project}";
    $res_q = mysqli_query($con, $sql_q);
    if ($res_q) {
        while ($row_q = mysqli_fetch_assoc($res_q)) {
            $tprice = $row_q['grand_total'];
        }
    }
    $response .= "<h5 class='text-light' id='total-amt'>Total Amount : Rs. {$tprice}</h5>";


    $sql_payment = "SELECT * FROM billing_db WHERE b_project_id={$project}";
    $res_payment = mysqli_query($con, $sql_payment);
    $total_pending = 0;
    if (mysqli_num_rows($res_payment) > 0) {
        $i = 1;
        $response1 .= "<div class='card table-responsive'><table class='table table-bordered'><thead><th class='text-info'>Sl. no.</th><th class='text-info'>Date</th><th class='text-info'>Bill Type</th><th class='text-info'>Amount Paid</th><th></th></thead><tbody>";
        while ($row_p = mysqli_fetch_assoc($res_payment)) {
            $typeb = $row_p['b_type'];
            if ($typeb == 0) {
                $btype = "One Time Settlement";
            } elseif ($typeb == 1) {
                $btype = "advanced payment";
            } elseif ($typeb == 2) {
                $btype = "Mid Payment";
            } else {
                $btype = "Final Settlement";
            }
            $fdat = date_create($row_p['b_date']);
            $fdate = date_format($fdat, "d M Y");
            $total_pending += $row_p['b_amount'];
            $response1 .= "<tr>";
            $response1 .= "<td class='text-light'>{$i}</td>";
            $response1 .= "<td class='text-light'>{$fdate}</td>";
            $response1 .= "<td class='text-light'>{$btype}</td>";
            $response1 .= "<td class='text-light'>Rs. {$row_p['b_amount']}</td>";
            $response1 .= "<td><button class='btn btn-sm btn-outline-primary py-1 btn-view-bill' data-id='{$row_p["billing_id"]}'>view</button></td>";

            $response1 .= "</tr>";

            $i++;
        }

        $response1 .= "</tbody></table></div>";
        $pendingAmt = $tprice - $total_pending;
        $response .= "<h6 class='text-danger' id='pending-amt'>Pending Amount : Rs. {$pendingAmt}</h6>";

        $response .= "<div class='d-flex justify-content-between'><p class='mt-4 mb-0'>Billing History :</p></div>";

        $response .= $response1;
    } else {
        $pendingAmt = $tprice - $total_pending;
        $response .= "<h6 class='text-danger' id='pending-amt'>Pending Amount : Rs. {$pendingAmt}</h6>";
        $response .= "<div class='d-flex justify-content-between'><p class='mt-4 mb-0'>Billing History :</p></div>";
        $response .= "<small></small>";
    }

    echo $response;
}



if (isset($_POST['viewbillbody'])) {
    $billid = $_POST['billid'];
    $response = "";

    $sql = "SELECT * FROM billing_db WHERE billing_id={$billid}";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $bproject = $row['b_project_id'];
            $type = $row['b_type'];
            $bdate = $row['b_date'];
            $bamount = $row['b_amount'];
        }

        if ($type == 0) {
            $text = "One Time Payment";
        } elseif ($type == 1) {
            $text = "Advance Payment";
        } elseif ($type == 2) {
            $text = "Mid Payment";
        } elseif ($type == 3) {
            $text = "Final Settlement";
        }

        $sql1 = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$bproject}";
        $res1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($res1) > 0) {
            while ($row = mysqli_fetch_assoc($res1)) {
                $cname = $row['c_name'];
                $caddress = $row['c_address'];
                $event = $row['project_event'];
                $cemail = $row['c_email'];
                $cphone = $row['c_phone'];
            }
        }
    }

    $response .= "<div style='background-color:#181821;'><div class='container px-5 py-4' style='background-color:#181821;'><div class='d-flex justify-content-between'><span><img class='img-fluid d-inline' style='width:45px' src='{$url}/assets/images/logo/logo.png' /><h4 class='text-warning d-inline mt-3 mx-3'>FIRST HASH</h4></span><h3 class='text-light'>INVOICE</h3></div></div>";
    $response .= "<div class='container px-5 py-4 mb-0 ' style='background-color:#181821;'><div class='col-6' style=' border-left: 1px solid #3d3d3d !important;'>
                    <h6 class='ms-2 pb-0 mb-0 text-info'>INVOICE TO:</h6>
                    <h5 class='ms-2 pt-0 pb-0 mb-3 mt-0 text-info'>{$cname}, {$caddress}</h5>
                    <h5 class='ms-2 pb-0 mb-0 text-info'>Date : {$bdate}</h5>
                </div></div>";
    $response .= "<div class='container mt-0' style='background-color:#181821;'><div class='table-responsive mt-5 mx-4'>
                    <table class='table table-bordered'><thead><th class='text-center' scope='col'>ITEM</th><th class='text-center' scope='col'>AMOUNT</th></thead><tbody>
                    <tr>
                    <td class='text-center'>{$text}</td>
                    <td class='text-center'>Rs. {$bamount}</td>
                    </tr>
                    </tbody></table>
                    </div></div>
                    <div class='container card text-end mt-2 mb-5'><h4 class='card-body text-light'>Total : Rs. {$bamount}</h4></div>
                    </div>";

    echo $response;
}
