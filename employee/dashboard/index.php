<?php


define('TITLE', 'Employee Dashboard');
define('PAGE', 'dashboard');
define('PAGE_T', 'Dashboard');
include_once('../include/header.php'); ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?php
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$today = date("Y-m-d");

$sqlTask = "SELECT * FROM daily_tasks WHERE dt_user_id = {$employee_id} AND dt_manager_status=0";
$resTask = mysqli_query($con, $sqlTask);
$dtasks = mysqli_num_rows($resTask);

$sql_pg = "SELECT * FROM assign_requirements WHERE ar_date > '$today' AND ar_assign_to ={$employee_id}";
$res_pg = mysqli_query($con, $sql_pg);
$penproj = mysqli_num_rows($res_pg);
if ($penproj > 0) {
    $ptasks = $penproj;
} else {
    $sql_pg1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$employee_id} AND da_status=0";
    $res_pg1 = mysqli_query($con, $sql_pg1);

    $ptasks = mysqli_num_rows($res_pg1);
}


$sql_pgc = "SELECT * FROM assign_requirements WHERE ar_date < '$today' AND ar_assign_to ={$employee_id}";
$res_pgc = mysqli_query($con, $sql_pgc);
$completed = mysqli_num_rows($res_pgc);
if ($completed > 0) {
    $ctasks = $completed;
} else {
    $sql_pgc1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$employee_id} AND da_status=1";
    $res_pgc1 = mysqli_query($con, $sql_pgc1);
    $ctasks = mysqli_num_rows($res_pgc1);
}


// payments 

$sql2 = "SELECT payment_option,payment_amount FROM users_db WHERE u_id={$employee_id}";
$res2 = mysqli_query($con, $sql2);
if ($res2) {
    while ($row2 = mysqli_fetch_assoc($res2)) {
        $paymentType = $row2['payment_option'];
        $pamount = $row2['payment_amount'];
    }
}

if ($paymentType == 0) {
    $sql3 = "SELECT * FROM salary_details WHERE sd_user_id={$employee_id}";
    $res3 = mysqli_query($con, $sql3);
    $numrow = mysqli_num_rows($res3);
    $reven = $numrow * $pamount;
} else {
    $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$employee_id} AND ar_paid_status =1 ";
    $res3 = mysqli_query($con, $sql3);
    $reven = 0;
    if (mysqli_num_rows($res3) > 0) {
        while ($row3 = mysqli_fetch_assoc($res3)) {
            $reven += $row3['ar_amount'];
        }
    }
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="card card-bg rounded stats-card">
                <div class="card-body pb-2 pt-3">

                    <div class="stats-icon change-success mx-2">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="m-l-15">
                        <h3 class="m-b-0"><?php echo $dtasks . "<small class='ms-2' style='font-size:14px'>pending</small>" ?></h3>
                        <p class="m-b-0 text-muted">Daily Tasks</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card card-bg rounded stats-card">
                <div class="card-body pb-2 pt-3">

                    <div class="stats-icon change-success mx-2">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="m-l-15">
                        <h3 class="m-b-0"><?php echo $ptasks . "<small class='ms-2' style='font-size:14px'>pending</small>" ?></h3>
                        <p class="m-b-0 text-muted">Project Tasks</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card card-bg rounded stats-card">
                <div class="card-body pb-2 pt-3">

                    <div class="stats-icon change-success mx-2">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="m-l-15">
                        <h3 class="m-b-0"><?php echo $ctasks  ?></h3>
                        <p class="m-b-0 text-muted">Complete Tasks</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card card-bg rounded stats-card">
                <div class="card-body pb-2 pt-3">

                    <div class="stats-icon change-success mx-2">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="m-l-15">
                        <h3 class="m-b-0">
                            <small>₹ <?php echo $reven; ?></small>
                        </h3>
                        <p class="m-b-0 text-muted">Revenue</p>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>

<div class="container-fluid border border-dark">
    <div class="d-flex justify-content-between py-2 px-3 border-dark mb-3">
        <p class="text-warning">Project Tasks</p>

    </div>
    <div class="table-responsive">
        <table class='table table-sm table-bordered card-bg'>

            <?php
            $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$employee_id} AND ar_date > '$today' ORDER BY ar_date ASC";
            $res3 = mysqli_query($con, $sql3);
            if (mysqli_num_rows($res3) > 0) {
                echo "  <thead>
                <th class='text-warning text-center'>Task</th>
                <th class='text-warning text-center'>Event Date</th>
                <th class='text-warning text-center'>Venue</th>
               
                <th class='text-warning text-center'></th>
            </thead>
            <tbody>";
                $ii2 = 1;
            ?>
                <?php
                while ($row3 = mysqli_fetch_assoc($res3)) {
                    $evntId = $row3['ar_event_id'];


                    $sqll = "SELECT * FROM events_db LEFT JOIN projects_db ON events_db.event_pr_id = projects_db.project_id WHERE event_id={$evntId}";
                    $resl = mysqli_query($con, $sqll);
                    if ($resl) {
                        while ($rowl = mysqli_fetch_assoc($resl)) {

                            $evntName = $rowl['event_name'];
                            $evntDate = $rowl['event_date'];
                            $evntProject = $rowl['project_name'];
                            $evenue = $rowl['event_venue'];

                            $date1 = new DateTime($today);
                            $date2 = new DateTime($evntDate);

                            // $date1 = new DateTime('2010-07-06');
                            // $date2 = new DateTime('2010-07-09');
                            $days  = $date1->diff($date2)->format("%r%a");



                            if (
                                $days < 0
                            ) {
                                $day = "<span class='text-danger'>Due Date Crossed</span>";
                            } elseif ($days == 0) {
                                $day = "<span class='text-danger'>1 day left</span>";
                            } elseif ($days == 1) {
                                $day = "<span class='text-danger'>1 day left</span>";
                            } else {
                                $day = $days . " days left";
                            }
                        }
                    }
                ?>
                    <tr>
                        <td class="text-center text-info"><?php echo $evntProject . " : " . $evntName; ?></td>
                        <td class="text-center text-info"><?php echo date("jS M-y", strtotime($evntDate)); ?></td>
                        <td class="text-center text-info"><?php echo $evenue; ?></td>
                        <td class="text-center text-warning"><?php echo $day ?></td>
                    </tr>



                    <?php $ii2++;
                }
            } else {
                echo "  <thead>
                <th class='text-warning text-center'>Task</th>
                <th class='text-warning text-center'>Due Date</th>
                <th class='text-warning text-center'>Status</th>
                <th class='text-warning text-center'>Verification</th>
                <th class='text-warning text-center'>Task Status</th>
                <th class='text-warning text-center'>Submit Task</th>
            </thead>
            <tbody>";
                $sql4 = "SELECT * FROM deliverables_assign LEFT JOIN deliverables_db ON deliverables_db.d_id=deliverables_assign.da_d_id WHERE da_assign_to={$employee_id} AND da_status=0 ORDER BY da_due_date ASC";
                $res4 = mysqli_query($con, $sql4);
                if (mysqli_num_rows($res4) > 0) {
                    $ii3 = 1;
                    while ($row4 = mysqli_fetch_assoc($res4)) {
                        $date1 = new DateTime($today);
                        $date2 = new DateTime($row4['da_due_date']);

                        // $date1 = new DateTime('2010-07-06');
                        // $date2 = new DateTime('2010-07-09');
                        $days  = $date1->diff($date2)->format("%r%a");



                        if (
                            $days < 0
                        ) {
                            $day = "<span class='text-danger'>Due Date Crossed</span>";
                        } elseif ($days == 0) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } elseif ($days == 1) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } else {
                            $day = $days . " days left";
                        }

                        $status = $row4['da_completed'];

                        if ($status == 0) {
                            $rstatus = "<span class='text-warning'>not completed</span><form action='../tasks/task-ajax.php' method='post'>
               <input type='hidden' name='delivId' id='task-id' value='{$row4["da_id"]}'><input type='submit' class='btn btn-outline-success py-1 btn-sm' name='setcompletep' value='set completed'> </form>";
                        } else {
                            $rstatus = "<span class='text-success'>completed</span>";
                        }

                        $sverify = $row4['da_status'];
                        if ($sverify == 0) {
                            $verify = "<span class='text-warning'>pending</span>";
                        } else {
                            $verify = "<span class='text-success'>completed</span>";
                        }
                    ?>
                        <tr>
                            <td class="text-center text-info"><a href="#" class="view-clpr" data-pr="<?php echo $row4['da_pr_id']; ?>"><?php echo $row4['da_item'] . " | " . $row4['d_layout']; ?></a></td>
                            <td class="text-center text-danger"><?php echo date("jS M-y", strtotime($row4['da_due_date'])); ?></td>
                            <td class="text-center text-info"><?php echo $day; ?></td>
                            <td class="text-center text-info"><?php echo $verify; ?></td>
                            <td class="text-center text-info"><input type='hidden' name='delivId' id='task-id' value='<?php echo $row4["da_id"] ?>'>
                                <select class="form-select input-state text-info" aria-label="Default select example" name="empType">
                                    <option value="0" class="text-white" <?php if ($status == 0) {
                                                                                echo  "selected";
                                                                            } ?>>Not Completed</option>
                                    <option value="2" class="text-white" <?php if ($status == 2) {
                                                                                echo  "selected";
                                                                            } ?>>In-Progress</option>
                                    <option value="3" class="text-white" <?php if ($status == 3) {
                                                                                echo  "selected";
                                                                            } ?>>First Cut Shared with Client</option>
                                    <option value="4" class="text-white" <?php if ($status == 4) {
                                                                                echo  "selected";
                                                                            } ?>>Change in Process</option>
                                    <option value="1" class="text-white" <?php if ($status == 1) {
                                                                                echo  "selected";
                                                                            } ?>>Completed</option>
                                </select>
                            </td>
                            <td class="text-center text-info"><button type="button" data-id="<?php echo $row4['da_id']; ?>" data-link="<?php echo $row4['da_link']; ?>" class="btn btn-outline-primary btn-link"><i class="fas fa-link"></i></button></td>

                        </tr>



            <?php
                        $ii3++;
                    }
                } else {
                    echo "<tr><td class='text-center' colspan='4'>no task</td></tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="container-fluid border border-dark mt-4">
    <div class="d-flex justify-content-between py-2 px-3 mb-3">
        <p class="text-warning">Daily Tasks</p>

    </div>

    <div class="table-responsive">
        <table class='table table-sm table-bordered card-bg'>
            <thead>
                <th class='text-warning text-center'>Task</th>
                <th class='text-warning text-center'>Task Date</th>
                <th class='text-warning text-center'>Due Date</th>
                <th class='text-warning text-center'>Status</th>
                <th class='text-warning text-center'></th>
            </thead>
            <tbody>
                <?php
                $sqlTask = "SELECT * FROM daily_tasks WHERE dt_user_id = {$employee_id} AND (dt_date = '{$today}' OR dt_manager_status=0) ORDER BY dt_due_date ASC";
                $resTask = mysqli_query($con, $sqlTask);
                if (mysqli_num_rows($resTask) > 0) {
                    while ($rowTasks = mysqli_fetch_assoc($resTask)) {

                        $verify = $rowTasks['dt_manager_status'];
                        $status = $rowTasks['dt_completed'];

                        if ($verify == 0) {
                            $verification = "<span class='text-warning'>Pending</span>";
                        }
                        // Creates DateTime objects

                        $date1 = new DateTime($today);
                        $date2 = new DateTime($rowTasks['dt_due_date']);

                        // $date1 = new DateTime('2010-07-06');
                        // $date2 = new DateTime('2010-07-09');
                        $days  = $date1->diff($date2)->format("%r%a");



                        if ($days < 0) {
                            $day = "<span class='text-danger'>Due Date Crossed</span>";
                        } elseif ($days == 0) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } elseif ($days == 1) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } else {
                            $day = $days . " days left";
                        }

                ?>
                        <tr>
                            <td class="text-center text-white"><?php echo substr($rowTasks['dt_details'], 0, 25) . "....."; ?></td>
                            <td class="text-center text-white"><?php echo date("jS M-y", strtotime($rowTasks['dt_date'])); ?></td>
                            <td class="text-center text-white"><?php echo date("jS M-y", strtotime($rowTasks['dt_due_date'])); ?></td>
                            <td class="text-center text-danger"><?php if ($status == 0) {
                                                                    echo $day;
                                                                } else {
                                                                    echo "<span class='text-success'>completed</span>";
                                                                } ?></td>
                            <td class="text-center text-white"> <input type="hidden" name="task_id" value="<?php echo $rowTasks['dt_id']; ?>">
                                <button class="btn mt-2 px-4 py-2 btn-outline-secondary btn-vieww">view</button>
                            </td>
                        </tr>


                <?php
                    }
                } else {
                    echo "<tr><td class='text-center' colspan='5'></td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>



</div>

<div class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" id="modalTaskView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <div class="container">
                    <h5 class="modal-title w-100 d-block font-weight-bold text-center" id="taskdetails"></h5>

                </div>

                <button class="btn btn-sm btn-close modal-close btn-secondary">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3" id="modal-task">

            </div>
            <div class="modal-footer d-flex justify-content-center">

                <button type="button" class="btn btn-secondary modal-close">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal  -->
<div class="modal fade" id="modalViewClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content card-bg">

            <div class="modal-body mx-3 pt-4">

                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">close <i class="fas fa-export ms-1"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdateLink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">

            <div class="modal-body mx-3 pt-4">
                <small class="text-info">ENTER URL:</small>
                <input type="text" class="form-control" id="urlId" value="">
                <input type="hidden" id="delivId" value="">

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">close <i class="fas fa-export ms-1"></i></button>
                <button class="btn btn-outline-primary btn-submit-url">Submit <i class="fas fa-export ms-1"></i></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="nsuggestionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-info">Edit Suggestions</h5>
                <button type="button" class="btn-close btn-secondary btn-closee" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body1 card-body">
                <div class=" border border-dark">

                    <div id="editors1" class="border border-secondary" style="min-height: 140px;">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="projid" id="projtidd" value="">
                <button type="button" class="btn btn-secondary btn-closee" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="save_suggestions">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_suggestions").click(function(e) {
        e.preventDefault();
        var pridd = $("#projtidd").val();
        var note = $(".ql-editor").html();

        $.ajax({
            type: "post",
            url: "<?php echo $url . "/employee/tasks/task-ajax.php"  ?>",
            data: {
                updatesuggestionm: true,
                pridd: pridd,
                note: note,
            },
            success: function(response) {
                if (response == 1) {
                    $(".div-suggestion").html(note);
                    $("#nsuggestionModal").modal("hide");
                } else {
                    alert("something went wrong");
                }
            }
        });
    });

    $(".btn-link").click(function(e) {
        e.preventDefault();
        var did = $(this).attr("data-id");
        var dlink = $(this).attr("data-link");
        $("#urlId").val(dlink);
        $("#delivId").val(did);

        $("#modalUpdateLink").modal('show');
    });

    $(".btn-submit-url").click(function(e) {
        e.preventDefault();
        var url = $("#urlId").val();
        var dlid = $("#delivId").val();
        $.ajax({
            type: "post",
            url: "<?php echo $url . "/employee/tasks/task-ajax.php"  ?>",
            data: {
                updatelinkurl: true,
                url: url,
                dlid: dlid,
            },
            success: function(response) {
                if (response == 1) {
                    location.reload();
                } else {
                    alert("something went wrong");
                }
            }
        });
    });

    $(".btn-vieww").on('click', function(e) {
        e.preventDefault();
        var taskid = $(this).closest("td").find("input[name='task_id']").val();
        $("#modalTaskView").modal("show");

        $.ajax({
            type: "post",
            url: "../tasks/task-ajax.php",
            data: {
                tasks: true,
                taskId: taskid,
            },

            success: function(response) {
                $("#modal-task").html(response);
            }
        });

        $(".modal-close").on('click', function(e) {
            e.preventDefault();
            $("#modalTaskView").modal("hide");

        });

    });

    $(".input-state").on("change", function() {
        var feildOption = $(this).val();
        var deliv = $(this).closest('td').find("input[name='delivId']").val();
        $.ajax({
            type: "post",
            url: "<?php echo $url . "/employee/tasks/task-ajax.php"  ?>",
            data: {
                setcompletep: true,
                feildOption: feildOption,
                deliv: deliv,
            },
            success: function(response) {
                if (response == 0) {
                    alert("something went wrong");
                }
            }
        });
    });

    $(".view-clpr").click(function(e) {
        e.preventDefault();
        var prid = $(this).attr("data-pr");
        $("#modalViewClient").modal('show');

        $.ajax({
            type: "post",
            url: "<?php echo $url . "/employee/tasks/task-ajax.php"  ?>",
            data: {
                viewclientinfo: true,
                prid: prid,
            },
            success: function(response) {
                $(".modal-body").html(response);

                $(".btn-edit-suggestion").click(function(e) {
                    // $("#modalViewClient").modal('hide');
                    e.preventDefault();
                    var id = $(this).attr("data-id");
                    // var note = $(this).attr("data-note");
                    var notes = $(".div-suggestion").html();
                    $(".ql-editor").html(notes);
                    $("#projtidd").val(id);
                    $("#nsuggestionModal").modal("show");

                });
            }
        });

    });

    var eee1 = "#editors1";
    var quill1 = new Quill(eee1, {
        theme: 'snow'
    });
</script>


<?php
include_once('../include/footer.php');
?>