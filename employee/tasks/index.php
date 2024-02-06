<?php
define('TITLE', 'Employee Dashboard');
define('PAGE', 'my-tasks');
define('PAGE_T', 'My Tasks');
include_once('../include/header.php'); ?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<?php
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$today = date("Y-m-d");

$limit = 15;
if (isset($_GET['pg'])) {
    $page = $_GET['pg'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
?>

<div class="container-fluid">
    <div class="table-responsive">
        <table class='table table-sm table-bordered card-bg'>
            <?php
            $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$employee_id} AND ar_date > '$today' ORDER BY ar_date ASC LIMIT {$offset},{$limit}";
            $res3 = mysqli_query($con, $sql3);
            if (mysqli_num_rows($res3) > 0) {
                echo "  <thead>
                <th class='text-warning text-center'>Task</th>
                <th class='text-warning text-center'>Event Date</th>
                <th class='text-warning text-center'>Venue</th>
               
                <th class='text-warning text-center'></th>
            </thead>
            <tbody>";
                $ii2 = $offset + 1;
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
                $sql4 = "SELECT * FROM deliverables_assign LEFT JOIN deliverables_db ON deliverables_db.d_id=deliverables_assign.da_d_id WHERE da_assign_to={$employee_id} AND da_status=0 ORDER BY da_due_date ASC LIMIT {$offset},{$limit}";
                $res4 = mysqli_query($con, $sql4);
                if (mysqli_num_rows($res4) > 0) {
                    $ii3 = $offset + 1;
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

                        //             if ($status == 0) {
                        //                 $rstatus = "<span class='text-warning'>not completed</span><form action='task-ajax.php' method='post'>
                        //    <input type='hidden' name='delivId' id='task-id' value='{$row4["da_id"]}'><input type='submit' class='btn btn-outline-success py-1 btn-sm' name='setcompletep' value='set completed'> </form>";
                        //             } else {
                        //                 $rstatus = "<span class='text-success'>completed</span>";
                        //             }


                        $sverify = $row4['da_status'];
                        if ($sverify == 0) {
                            $verify = "<span class='text-warning'>pending</span>";
                        } else {
                            $verify = "<span class='text-success'>completed</span>";
                        }
                    ?>

                        <tr>
                            <td class="text-center text-info"> <a href="#" class="view-clpr" data-pr="<?php echo $row4['da_pr_id']; ?>"><?php echo $row4['da_item'] . " | " . $row4['d_layout']; ?></a> </td>
                            <td class="text-center text-danger"><?php echo date("jS M-y", strtotime($row4['da_due_date'])); ?></td>
                            <td class="text-center text-info"><?php echo $day; ?></td>
                            <td class="text-center text-info"><?php echo $verify; ?></td>
                            <td class="text-center text-info">
                                <input type='hidden' name='delivId' id='task-id' value='<?php echo $row4["da_id"] ?>'>
                                <select class="form-select input-state text-info taskType" aria-label="Default select example" name="empType">
                                    <?php
                                    $sqlet = "SELECT * FROM task_status";
                                    $reset = mysqli_query($con, $sqlet);
                                    if (mysqli_num_rows($reset) > 0) {
                                        while ($rowet = mysqli_fetch_assoc($reset)) {
                                            $eid = $rowet['ts_id'];
                                            if ($eid == $status) {
                                                $sele = "selected";
                                            } else {
                                                $sele = "";
                                            }
                                            $etitle = $rowet['ts_title'];
                                            echo "<option value='{$eid}' {$sele}>{$etitle}</option>";
                                        }
                                    }
                                    ?>

                                    <option value="0" class='text-white'>+ Add new</option>
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


    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            $sql_pg = "SELECT * FROM assign_requirements WHERE ar_date > '$today' AND ar_assign_to ={$employee_id}";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/employee/tasks/index.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            } else {
                $sql_pg1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$employee_id} AND da_status=0";
                $res_pg1 = mysqli_query($con, $sql_pg1);
                if (mysqli_num_rows($res_pg1) > 0) {

                    $total_records = mysqli_num_rows($res_pg1);

                    $total_page = ceil($total_records / $limit);
                    echo '<ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = "";
                        }
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/employee/tasks/index.php?pg=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                    }

                    echo '</ul>';
                }
            }


            ?>



        </nav>

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

<!-- Modal -->
<div class="modal fade" id="neweventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="staticBackdropLabel">Task Status</h5>
                <button type="button" class="btn-close btn-secondary btn-closee" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body px-5">
                    <input type="text" class="form-control mb-4" id="newevent-type" value="" placeholder="task status">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-closee" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="save_eventype">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".btn-closee").click(function(e) {
        e.preventDefault();
        location.reload();
    });
    $(".taskType").on("change", function() {
        var pType1 = $(this).val();

        if (pType1 == 0) {

            $('#neweventModal').modal('show');
        }
    });

    $("#save_eventype").click(function(e) {
        e.preventDefault();
        var ettile = $("#newevent-type").val();


        $.ajax({
            type: "post",
            url: "<?php echo $url . "/employee/tasks/task-ajax.php"; ?>",
            data: {
                newestatustype: true,
                etitle: ettile,
            },
            success: function(response) {
                if (response == 1) {
                    location.reload();
                } else {
                    alert('something went wrong');
                }
            }
        });

    });
    $("#save_suggestions").click(function(e) {
        e.preventDefault();
        var pridd = $("#projtidd").val();
        var note = $(".ql-editor").html();

        $.ajax({
            type: "post",
            url: "task-ajax.php",
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
            url: "task-ajax.php",
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

    $(".input-state").on("change", function() {
        var feildOption = $(this).val();
        var deliv = $(this).closest('td').find("input[name='delivId']").val();
        $.ajax({
            type: "post",
            url: "task-ajax.php",
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
            url: "task-ajax.php",
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
                    var note = $(".div-suggestion").html();
                    $(".ql-editor").html(note);
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