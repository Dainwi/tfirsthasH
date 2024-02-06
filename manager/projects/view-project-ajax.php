<?php
include_once('../../config.php');
session_start();
$cusID = $_SESSION['cus_id'];

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
            $edate = date("d M Y", strtotime($row['event_date']));
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
            <p class='text-light ms-4 py-0 my-0'><span class='text-info'>Gathering </span> : <input type='hidden' name='eventid' value='{$eid}' >
             <input style='width:85px;' class='form-control d-inline g-input' type='number' value='{$egather}'></p>
           <p class='text-light ms-4 py-0 my-0'><span class='text-info d-inline'>Venue </span> : <input type='hidden' name='eventid' value='{$eid}' > <input style='max-width:200px;' class='form-control d-inline ven-input' type='text' value='{$evenue}'></p>
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
                   <input type='hidden' name='emptype' value='{$rtype}' > <button class='btn btn-sm btn-outline-info btn-round py-1 px-3 btn-assignto'><i class='fas fa-pencil-alt'></i></button>";

                    $sql3 = "SELECT * FROM assign_requirements LEFT JOIN users_db ON users_db.u_id = assign_requirements.ar_assign_to  WHERE ar_requirement_id={$rid} AND users_db.user_active=1";
                    $res3 = mysqli_query($con, $sql3);
                    if (mysqli_num_rows($res3) > 0) {
                        $output .= "<div class='d-flex cdiv{$rid}'>";
                        while ($row3 = mysqli_fetch_assoc($res3)) {

                            $output .= "<li class='me-4'>{$row3["user_name"]}</li>";
                        }
                        $output .= "</div>";
                    } else {

                        $output .= "<div class='d-flex cdiv{$rid}'><small>employee not assign</small></div>";
                    }

                    $output .= "</td></tr>";
                }
            }

            $sqld = "SELECT * FROM event_deliverables WHERE ed_eventid={$eid}";
            $resd = mysqli_query($con, $sqld);
            $roe = "";
            if (mysqli_num_rows($resd) > 0) {
                $roe .= "<ul>";
                while ($rowd = mysqli_fetch_assoc($resd)) {
                    $roe .= "<li>
{$rowd["ed_deliverables"]} <button data-id='{$rowd["ed_id"]}' data-text='{$rowd["ed_deliverables"]}' class='btn btn-sm btn-outline-secondary py-1 px-2 btn-edit-ditem'><i class='fas fa-pencil-alt'></i></button>
                                                        <button data-id='{$rowd["ed_id"]}' class='btn btn-sm btn-outline-danger py-1 px-2 btn-del-ditem'><i class='fas fa-trash-alt'></i></button>
                                                        </li>";
                }
                $roe .= "</ul>";
            }

            $output .= "</tbody></table>";
            $output .= " <div class='row mt-4'>
           <div class='col-md-6'>
            <div class='card' data-event='{$eid}'>
            <label for='deliverable-e{$eid}'>Deliverable
            <button class='btn btn-outline-secondary btn-sm ms-2 py-1 btn-add-deliverables' data-id='{$eid}'>Add More</button></label>
                <div class='form-control deliverable-e' id='deliev{$eid}' placeholder='deliverable' style='min-height: 80px'>{$roe}</div>
                </div></div>
                <div class='col-md-6'>
            <div class='card' data-event='{$eid}'>
            <label for='comments-e{$eid}' class='d-block'>Data Stored In</label>
                <textarea class='form-control comment-e' id='comments-e{$eid}' placeholder='Notes' style='height: 80px'>{$enote}</textarea>
               </div></div></div></div>";
            $iem++;
        }
    }

    echo $output;
}
if (isset($_POST['editvenuec'])) {
    $evinput = $_POST['evinput'];
    $eventid = $_POST['eventid'];
    $sql = "UPDATE events_db SET event_venue='$evinput' WHERE event_id={$eventid}";
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


//edit gathering

if (isset($_POST['gatheringc'])) {
    $ginput = $_POST['ginput'];
    $eventid = $_POST['eventid'];

    $sql = "UPDATE events_db SET event_gathering='$ginput' WHERE event_id={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


//assign requirements
// if (isset($_POST['assignreq'])) {
//     $rid = $_POST['rid'];
//     $evntid = $_POST['evntid'];
//     $evntdate = $_POST['evntdate'];
//     $emptype = $_POST['emptype'];

//     $date = date($evntdate);
//     $response = "";
//     $sql = "SELECT * FROM employee_position LEFT JOIN users_db ON employee_position.ep_user_id=users_db.u_id WHERE ep_type={$emptype} AND users_db.user_active=1";
//     $res = mysqli_query($con, $sql);

//     if (mysqli_num_rows($res) > 0) {
//         $response .= "<input type='hidden' name='erid' value='{$rid}'> <input type='hidden' name='eventid' value='{$evntid}'><input type='hidden' name='evntdate' value='{$evntdate}'>";
//         while ($row = mysqli_fetch_assoc($res)) {
//             $userid = $row['u_id'];
//             $username = $row['user_name'];
//             $uname = $row['user_username'];
//             $sql1 = "SELECT * FROM assign_requirements WHERE ar_date='$date' AND ar_assign_to={$userid} AND ar_requirement_id={$rid} AND ar_event_id={$evntid}";
//             $res1 = mysqli_query($con, $sql1);
//             if (mysqli_num_rows($res1) > 0) {
//                 $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' checked type='checkbox' value='{$userid}' id='checkr{$userid}'>
//                                         <label class='form-check-label text-light' for='checkr{$userid}'>
//                                           {$username} <small>({$uname})</small>
//                                         </label>
//                                       </div>";
//             } else {
//                 // $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' type='checkbox' value='{$userid}' id='checkr{$userid}'>
//                 //                         <label class='form-check-label text-light' for='checkr{$userid}'>
//                 //                           {$username} <small>({$uname})</small>
//                 //                         </label>
//                 //                       </div>";

//                 $sql2 = "SELECT * FROM assign_requirements WHERE ar_date ='$date' AND ar_assign_to={$userid}";
//                 $res2 = mysqli_query($con, $sql2);
//                 if (mysqli_num_rows($res2) > 0) {
//                 } else {
//                     $response .= "<div class='form-check mb-3'><input class='form-check-input border border-light' name='assignto[]' type='checkbox' value='{$userid}' id='checkr{$userid}'>
//                                         <label class='form-check-label text-light' for='checkr{$userid}'>
//                                           {$username} <small>({$uname})</small>
//                                         </label>
//                                       </div>";
//                 }
//             }
//         }
//     } else {
//         $response .= "No employee found";
//     }

//     echo $response;
// }
if (isset($_POST['assignreq'])) {
    $rid = mysqli_real_escape_string($con, $_POST['rid']);
    $evntid = mysqli_real_escape_string($con, $_POST['evntid']);
    $evntdate = mysqli_real_escape_string($con, $_POST['evntdate']);
    $emptype = mysqli_real_escape_string($con, $_POST['emptype']);
  
    // echo $cusID;
    

    $date = date('Y-m-d', strtotime($evntdate)); // Format the date correctly
    $response = "";

    $sql = "SELECT users_db.u_id, users_db.user_name, users_db.user_username, employee_type.type_name 
            FROM employee_position 
            LEFT JOIN users_db ON employee_position.ep_user_id = users_db.u_id 
            LEFT JOIN employee_type ON employee_position.ep_type = employee_type.type_id 
            WHERE ep_type = '{$emptype}' AND users_db.user_active = 1 AND users_db.cus_id = '{$cusID}'";

    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        $response .= "<input type='hidden' name='erid' value='{$rid}'> 
                      <input type='hidden' name='eventid' value='{$evntid}'>
                      <input type='hidden' name='evntdate' value='{$evntdate}'>";

        while ($row = mysqli_fetch_assoc($res)) {
            $userid = $row['u_id'];
            $username = $row['user_name'];
            $userType = $row['type_name']; // Assuming type_name is the employee type

            $sql1 = "SELECT * FROM assign_requirements 
                     WHERE ar_date = '{$date}' AND ar_assign_to = '{$userid}' 
                     AND ar_requirement_id = '{$rid}' AND ar_event_id = '{$evntid}'";
            $res1 = mysqli_query($con, $sql1);

            if (mysqli_num_rows($res1) > 0) {
                $checked = 'checked';
            } else {
                $checked = '';
            }

            $response .= "<div class='form-check mb-3'>
                          <input class='form-check-input border border-light' name='assignto[]' {$checked} type='checkbox' value='{$userid}' id='checkr{$userid}'>
                          <label class='form-check-label text-light' for='checkr{$userid}'>
                            {$username} <small>({$userType})</small>
                          </label>
                          </div>";
        }
    } else {
        $response .= "No employee found";
    }

    echo $response;
};



if (isset($_POST['req-submit'])) {
    // echo "<pre>";
    // echo print_r($_POST);
    $erid = $_POST['erid'];
    $prid = $_POST['project_id'];
    $eventid = $_POST['eventid'];
    $evntdate = $_POST['evntdate'];


    $datee = date_create($evntdate);

    $output = "";
    $sql = "DELETE FROM assign_requirements WHERE ar_requirement_id={$erid}";
    $res = mysqli_query($con, $sql);

    if ($res) {
        if (isset($_POST['assignto'])) {
            $assignto = $_POST['assignto'];
            foreach ($assignto as $index => $item_id) {
                $assigntos = $item_id;

                $sql1 = "INSERT INTO assign_requirements(ar_requirement_id,ar_event_id,ar_date,ar_assign_to,ar_project_id)VALUES($erid,$eventid,'$evntdate',$assigntos,$prid)";
                $res1 = mysqli_query($con, $sql1);
            }

            $sql3 = "SELECT * FROM assign_requirements LEFT JOIN users_db ON users_db.u_id = assign_requirements.ar_assign_to  WHERE ar_requirement_id={$erid} AND users_db.user_active=1";
            $res3 = mysqli_query($con, $sql3);

            if (mysqli_num_rows($res3) > 0) {

                while ($row3 = mysqli_fetch_assoc($res3)) {

                    $output .= "<li class='me-4'>{$row3["user_name"]}</li>";
                }
            } else {

                $output .= "<small>employee not assign</small>";
            }
        } else {
            $output .= "<small>employee not assign</small>";
        }
    }
    echo $output;
}


//deliverables
if (isset($_POST['deliverablesEvent'])) {
    $textde = $_POST['textdel'];
    $eventid = $_POST['eventid'];

    $sql = "UPDATE events_db SET event_deliverables='$textde' WHERE event_id={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

//comments
if (isset($_POST['commentEvent'])) {
    $textcomment = $_POST['textcomment'];
    $eventid = $_POST['eventid'];

    $sql = "UPDATE events_db SET event_note='$textcomment' WHERE event_id={$eventid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['l_title'])) {
    $ltitle = $_POST['l_title'];
    $sql = "INSERT INTO layout_db(layout_name,cus_id)VALUES('$ltitle','$cusID')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $layoutid = mysqli_insert_id($con);
        $item = $_POST['l_item'];

        foreach ($item as $index => $items) {
            $itemt = $items;

            $sql1 = "INSERT INTO layout_items(li_layout_id,li_title)VALUES($layoutid,'$itemt')";
            $res1 = mysqli_query($con, $sql1);
        }

        if ($res1) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}



if (isset($_POST['layoutdeliverable'])) {
    $sql = "SELECT * FROM layout_db WHERE cus_id = {$cusID}";
    $res = mysqli_query($con, $sql);
    $output = "";
    if (mysqli_num_rows($res) > 0) {
        $li = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $layoutid = $row['layout_id'];
            $output .= "<h5 class='text-info mb-4'>{$li}. {$row["layout_name"]} <button type='button' data-layoutname='{$row["layout_name"]}' data-layout='{$layoutid}' class='ms-4 btn btn-sm btn-outline-warning py-1 btn-select-layout'>select</button></h5>";

            // $output .= "<ul>";
            // $sql1 = "SELECT * FROM layout_items WHERE li_layout_id={$layoutid}";
            // $res1 = mysqli_query($con, $sql1);
            // if (mysqli_num_rows($res1) > 0) {
            //     while ($row1 = mysqli_fetch_assoc($res1)) {
            //         $output .= "<li class='my-0 py-0'>{$row1["li_title"]}</li>";
            //     }
            // }
            // $output .= "</ul>";
            $li++;
        }
    } else {
        $output .= "no layout found";
    }
    echo $output;
}

if (isset($_POST['deliverablesave'])) {
    $layout = $_POST['layout'];
    $layoutname = $_POST['layoutname'];
    $project = $_POST['project'];
    // echo "<script> console.log($cusID)</script>";
     $sql = "INSERT INTO deliverables_db(d_pr_id,d_layout,cus_id)VALUES($project,'$layoutname','$cusID')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $did = mysqli_insert_id($con);

        $sql1 = "SELECT * FROM layout_items WHERE li_layout_id={$layout}";
        $res1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($res1) > 0) {
            while ($row1 = mysqli_fetch_assoc($res1)) {
                $lititle = $row1['li_title'];

                $sql2 = "INSERT INTO deliverables_assign(da_d_id,da_pr_id,da_item,da_assign_to,da_due_date)VALUES($did,$project,'$lititle',0,'0000-00-00')";
                $res2 = mysqli_query($con, $sql2);
            }

            if ($res2) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}


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
            $response .= " <h5 class='text-light mt-4'>{$di}. <span class='ms-3'>{$row["d_layout"]}</span><input type='hidden' name='did' value='{$did}'><button class='btn btn-sm btn-outline-danger py-1 px-3 ms-3 d-inline del-did-btn'><i class='fas fa-trash'></i></button><span class='ms-5 me-2'>Final Due Date :</span><input class='form-control d-inline milestone-due-date' style='max-width:160px' type='date' value={$dudate}></h5>
             ";
            $response .= "<table class='table table-bordered' style='background-color:#181821;'><thead><th class='text-warning'>Tasks to be done</th><th class='text-warning'>assign to</th><th class='text-warning'>Due Date</th><th class='text-warning'>Task Status</th><th class='text-warning'>Status</th><th class='text-warning'>View Task</th><th class='text-warning'></th></thead><tbody>";

            $sql1 = "SELECT * FROM deliverables_assign WHERE da_d_id={$did}";
            $res1 = mysqli_query($con, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $daid = $row1['da_id'];
                    $sastatus = $row1['da_status'];
                    $ustatus = $row1['da_completed'];

                    $sqlst = "SELECT * FROM task_status WHERE ts_id={$ustatus}";
                    $resst = mysqli_query($con, $sqlst);
                    if (mysqli_num_rows($resst) > 0) {
                        while ($rowst = mysqli_fetch_assoc($resst)) {
                            $prog = $rowst['ts_title'];
                        }
                    } else {
                        $prog = "Not Completed";
                    }

                    $linkk = $row1['da_link'];

                    if ($linkk == "" || $linkk == null) {
                        $urll = "<small>n/a</small>";
                    } else {
                        $urll = "<a target='_blank' href='{$linkk}' class='text-primary'><i class='fas fa-link'></i></a>";
                    }



                    $ddate = $row1['da_due_date'];
                    if ($ddate == "0000-00-00") {
                        $date = "";
                    } else {
                        $date = $ddate;
                    }

                    if ($sastatus == 0) {
                        $ss = "<p class='text-danger d-inline'>pending</p><input type='hidden' name='daid' value='{$daid}'> <button class='btn btn-outline-success btn-sm py-1 btn-setc'>set complete</button>";
                    } else {
                        $ss = "<p class='text-success'>completed</p>";
                    }
                    $response .= "<tr>";
                    $response .= "<td>{$row1["da_item"]}</td>";

                    //assigned post production
                    if ($row1['da_assign_to'] == 0) {
                        $assigned = "<button class='btn btn-sm btn-outline-primary py-1 assign-da-user'><i class='fas fa-plus'></i></button>";
                    } else {
                        $sqln = "SELECT * FROM deliverables_assign WHERE da_assign_to={$row1['da_assign_to']} AND da_status=0";
                        $resn = mysqli_query($con, $sqln);
                        $numr = mysqli_num_rows($resn);

                        if ($numr > 1) {
                            $task = "tasks - p";
                        } else {
                            $task = "task - p";
                        }

                        $sqlu = "SELECT user_name,user_active FROM users_db WHERE u_id={$row1['da_assign_to']}";
                        
                        $resu = mysqli_query($con, $sqlu);
                        if (mysqli_num_rows($resu) > 0) {
                            while ($rowu = mysqli_fetch_assoc($resu)) {
                                $usernamee = $rowu['user_name'];
                            }
                            $assigned =  "{$usernamee} ({$numr} {$task})<button class='ms-2 px-2 btn btn-sm btn-outline-primary py-1 assign-da-user'><i class='fas fa-pencil-alt'></i></button>";
                        }
                    }
                    $response .= "<td><input type='hidden' name='delid' value='{$daid}'>{$assigned}</td>";
                    $response .= "<td><input type='hidden' name='daitemid' value='{$daid}'><input type='date' class='form-control deliverable-date text-light' style='width:150px; background:#1c1c1c;' value='{$date}'></td>";
                    $response .= "<td><small>{$prog}</small></td>";
                    $response .= "<td>{$ss}</td>";

                    $response .= "<td class='text-center'>{$urll}</td>";
                    $response .= "<td><input type='hidden' name='daidc' value='{$daid}'><button class='btn btn-outline-danger btn-sm py-1 btn-delete-da'><i class='fas fa-trash'></i></button></td>";

                    $response .= "</tr>";
                }
            }


            $response .= "</tbody></table><h5><input type='hidden' name='daidd' value='{$did}'><button class='btn btn-outline-secondary btn-sm py-1 add-did-btn'>+ add deliverable</button></h5>";
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
            <small>Data Stored In : </small>
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

if (isset($_POST['deliverableduedate'])) {
    $delivid = $_POST['delivid'];
    $dddate = $_POST['dddate'];
    $sql = "UPDATE deliverables_db SET d_due_date='$dddate' WHERE d_id={$delivid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['dadate'])) {
    $daid = $_POST['daid'];
    $ddate = $_POST['ddate'];
    $ddate = date($ddate);

    $sql = "UPDATE deliverables_assign SET da_due_date='$ddate' WHERE da_id={$daid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['statusda'])) {
    $daid = $_POST['daid'];
    $sql = "UPDATE deliverables_assign SET da_status=1 WHERE da_id={$daid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<p class='text-success'>completed</p>";
    } else {
        echo 0;
    }
}

if (isset($_POST['deletedassign'])) {
    $daidd = $_POST['daidd'];
    $sql = "DELETE FROM deliverables_assign WHERE da_id={$daidd}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['deleteddelivassign'])) {
    $didd = $_POST['didd'];

    $sql = "DELETE FROM deliverables_db WHERE d_id={$didd};";
    $sql .= "DELETE FROM deliverables_assign WHERE da_d_id={$didd}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
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

        $sqll = "SELECT * FROM users_db WHERE user_role=1 AND user_active=1 AND cus_id = '$cusID'";
        $ress = mysqli_query($con, $sqll);
        $outp = "";
        if (mysqli_num_rows($ress) > 0) {
            $man = 1;
            while ($roww = mysqli_fetch_assoc($ress)) {
                $userid = $roww['u_id'];
                $name = $roww['user_name'];
                $sqln1 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$userid}";
                $resn1 = mysqli_query($con, $sqln1);
                $rownn1 = mysqli_num_rows($resn1);
                if ($rownn1 > 1) {
                    $tasks = "tasks pending";
                } else {
                    $tasks = "task pending";
                }

                if ($aemployee == $userid) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $outp .= "<option value='{$userid}' {$selected}>{$name} ({$rownn1} {$tasks})</option>";
            }
        } else {
            $man = 0;
            $outp = "";
        }

        $sql1 = "SELECT * FROM employee_position LEFT JOIN employee_type ON employee_position.ep_type=employee_type.type_id WHERE employee_type.type_production=1 AND employee_type.cus_id = '$cusID' GROUP BY employee_position.ep_user_id";
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

                $sql2 = "SELECT * FROM users_db WHERE u_id={$userid} AND user_active=1";
                $res2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($res2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($res2)) {
                        $name = $row2['user_name'];
                    }
                }

                $output .= "<option value='{$userid}' {$selected}>{$name} ({$rownn} {$tasks})</option>";
            }
            $output .= $outp;
            $output .= "</select>";
        } elseif ($man == 1) {
            $output .= "<select class='form-control' id='select-deliverables'>";
            $output .= "<option value='0'>SELECT EMPLOYEE</option>";
            $output .= $outp;
            $output .= "</select>";
        } else {
            $output .= "no employee";
        }
    } else {
        $output .= 0;
    }

    echo $output;
}

// assign deliverables 
if (isset($_POST['assignemployeedel'])) {
    $employeeid = $_POST['employeeid'];
    $delassid = $_POST['delassid'];
    $sql = "UPDATE deliverables_assign SET da_assign_to={$employeeid} WHERE da_id={$delassid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

//add new deliverable
if (isset($_POST['addnewdeliverable'])) {
    $textinput = $_POST['textinput'];
    $deliverable = $_POST['deliverable'];
    $prid = $_POST['prid'];

    $sql = "INSERT INTO deliverables_assign(da_d_id,da_pr_id,da_item)VALUES($deliverable,$prid,'$textinput')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['layoutdeliverabledelete'])) {
    $sql = "SELECT * FROM layout_db WHERE cus_id = {$cusID} ";
    $res = mysqli_query($con, $sql);
    $output = "";
    if (mysqli_num_rows($res) > 0) {
        $li = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $layoutid = $row['layout_id'];
            $output .= "<h5 class='text-info mb-4'>- {$row["layout_name"]} <button type='button' data-layoutname='{$row["layout_name"]}' data-layout='{$layoutid}' class='ms-4 btn btn-sm btn-outline-danger py-1 btn-del-dlayout'>delete</button></h5>";


            $li++;
        }
    } else {
        $output .= "no layout found";
    }
    echo $output;
}


if (isset($_POST['deliverabledeletelayout'])) {
    $layout = $_POST['layout'];
    $sql = "DELETE FROM layout_db WHERE layout_id={$layout};";
    $sql .= "DELETE FROM layout_items WHERE li_layout_id={$layout}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['suggestions'])) {
    $prid = $_POST['prid'];
    $sugtext = $_POST['sugtext'];

    $sql = "UPDATE projects_db SET project_note='$sugtext' WHERE project_id={$prid}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}
