<?php
define('TITLE', 'Team Allocation');
define('PAGE', 'allocation');
define('PAGE_T', 'Team Allocation');
include_once('../include/header.php');
echo "<style>
.table td, .table th {
    min-width: 150px !important;
}
</style>";

if (isset($_GET['m'])) {
    $month = $_GET['m'];

    $mon = date('M', strtotime($month));
} else {
    $month = date('m');
    $mon = date('M', strtotime($month));
}


if (isset($_GET['y'])) {
    $year = $_GET['y'];
} else {
    $year = date('Y');
}

$ay = $year + 1;
$sy = $year - 1;

$am = $month + 1;

$sm = $month - 1;
if ($month == 12) {
    $urlma = $url . "/manager/team-allocation/index.php?m=1&y=" . $ay;
    $amonth = 1;
} else {
    $urlma = $url . "/manager/team-allocation/index.php?m=" . $am . "&y=" . $year;
    $amonth = $am;
}

if ($month == 1) {
    $urlms = $url . "/manager/team-allocation/index.php?m=12&y=" . $sy;
    $smonth = 12;
} else {
    $urlms = $url . "/manager/team-allocation/index.php?m=" . $sm . "&y=" . $year;
    $smonth = $sm;
}



$cdate = $year . "-" . $month . "-01";
$newdate = date($cdate);

$monthh = date('F', strtotime($newdate));

include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['cus_id'];
// $cusID = mysqli_real_escape_string($con, $cussID);
?>

<div class="card card-bg mb-1">
    <div class="card-body pt-3 pb-2 d-flex justify-content-between align-items-center">
        <h5 class="text-uppercase">
            <div class="input-group">
                <a href="<?php echo $urlms ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-minus"></i></a>
                <input type="text" class="form-control form-control-sm text-center text-uppercase border-success" disabled value="<?php echo $monthh; ?>" id="">
                <a href="<?php echo $urlma ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-plus"></i></a>
            </div>


        </h5>
        <h5>
            <div class="input-group">
                <a href="<?php echo $url . "/manager/team-allocation/index.php?m=" . $month . "&y=" . $sy ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-minus"></i></a>
                <input type="text" class="form-control form-control-sm text-center text-uppercase border border-warning" disabled value="<?php echo $year ?>" id="">
                <a href="<?php echo $url . "/manager/team-allocation/index.php?m=" . $month . "&y=" . $ay ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-plus"></i></a>
            </div>
        </h5>
    </div>
</div>

<div class="table-responsive card-bg rounded shadow mt-0">
    <table class="table table-sm table-bordered">
        <thead>
            <th class='text-warning text-center'>EventDate </th>
            <th class='text-warning text-center'>Client Name</th>
            <th class='text-warning text-center'>Comment</th>
            <th class='text-warning text-center'>Function</th>
            <th class='text-warning text-center'>Location &amp; Time</th>

            <?php

            $cusID = mysqli_real_escape_string($con, $cusID);
                                $sql = "SELECT * FROM employee_type WHERE (cus_id=$cusID OR cus_id=0) AND type_production=0";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<th class='text-warning text-center'>{$row["type_name"]}</th>";
                }
            }

            ?>



        </thead>
        <tbody>

            <?php

            $sqlm = "SELECT * FROM projects_db LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE assign_manager_db.am_user_id ={$manager_id}";
            $resm = mysqli_query($con, $sqlm);
            if (mysqli_num_rows($resm) > 0) {
                while ($rowm = mysqli_fetch_assoc($resm)) {
                    $prid = $rowm['project_id'];

                    $sqltr = "SELECT * FROM events_db LEFT JOIN clients_db ON events_db.event_cl_id=clients_db.c_id WHERE month(event_date)='$month' AND year(event_date)='$year' AND event_pr_id={$prid} ORDER BY events_db.event_date ASC";
                    $restr = mysqli_query($con, $sqltr);
                    if (mysqli_num_rows($restr) > 0) {
                        while ($rowtr = mysqli_fetch_assoc($restr)) {
                            $evntid = $rowtr['event_id'];
                            $projid = $rowtr['event_pr_id'];
                            $edate = $rowtr['event_date'];
                            $date = date("jS M Y", strtotime($edate));
            ?>
                            <tr>
                                <td class='text-center text-primary'><?php echo $date ?></td>
                                <td class='text-center text-light'><?php echo $rowtr['c_name']; ?></td>
                                <td class='text-center text-light'><small><?php echo $rowtr['event_note']; ?></small></td>
                                <td class='text-center text-info'><?php echo $rowtr['event_name']; ?></td>
                                <td class='text-center text-light'><?php echo $rowtr['event_venue']; ?></td>
                                <?php
                                // $cusID = mysqli_real_escape_string($con, $cusID);
                                $sqlet = "SELECT * FROM employee_type WHERE (cus_id=$cusID OR cus_id=0) AND type_production=0";
                                $reset = mysqli_query($con, $sqlet);
                                if (mysqli_num_rows($reset) > 0) {
                                    while ($rowet = mysqli_fetch_assoc($reset)) {
                                        $et = $rowet['type_id'];

                                        $sqler = "SELECT * FROM event_requirements WHERE er_event={$evntid} AND er_type={$et}";
                                        $reser = mysqli_query($con, $sqler);
                                        if (mysqli_num_rows($reser) > 0) {
                                            while ($rower = mysqli_fetch_assoc($reser)) {
                                                $erid = $rower['er_id'];

                                                $output = "<input type='hidden' name='projid' value='{$projid}'><input type='hidden' name='erid' value='{$erid}'><input type='hidden' name='evntid' value='{$evntid}'><input type='hidden' name='evntdate' value='{$edate}' >
                   <input type='hidden' name='emptype' value='{$et}' > <button class='btn btn-sm btn-outline-info btn-round py-1 px-3 btn-assignto'><i class='fas fa-pencil-alt'></i></button>";

                                                $sql3 = "SELECT * FROM assign_requirements LEFT JOIN users_db ON users_db.u_id = assign_requirements.ar_assign_to  WHERE ar_requirement_id={$erid}";
                                                $res3 = mysqli_query($con, $sql3);
                                                if (mysqli_num_rows($res3) > 0) {
                                                    $output .= "<div class=' cdiv{$erid}'>";
                                                    while ($row3 = mysqli_fetch_assoc($res3)) {

                                                        $output .= "<li class='me-4 text-success'>{$row3["user_name"]}</li>";
                                                    }
                                                    $output .= "</div>";
                                                } else {

                                                    $output .= "<div class='cdiv{$erid}'><small class='text-secondary'>not assigned</small></div>";
                                                }

                                                echo "<td class='text-light text-center'>{$output}</td>";
                                            }
                                        } else {
                                            echo "<td class='text-light text-center'></td>";
                                        }
                                    }
                                }

                                ?>

                            </tr>

            <?php
                        }
                    }
                }
            }

            ?>



        </tbody>
    </table>
</div>


<!-- Modal Assign Employee start-->
<form action="allocation-actions.php" id="form-req" method="post">
    <div class="modal fade" id="assign-employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalLabel">Assign Employees</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        <!--<span aria-hidden="true">&times;</span>-->
                    </button>
                </div>
                <input type="hidden" id="name-classs">

                <div class="modal-body">
                    <input type="hidden" name="projectid" id="project-idd">
                    <input type="hidden" name="req-submit" value="1">
                    <div class="modal-employee-assign" id="modal-ae">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" data-dismiss="modal">Close</button>
                    <input type="submit" id="assign-employee-btn" class="btn btn-outline-warning btn-round px-4" value="Save Changes">
                </div>

            </div>
        </div>
    </div>
</form>
<!-- Modal Assign Employee end-->

<script>
    //employee assign to
    $(".btn-assignto").click(function(e) {
        e.preventDefault();
        var rid = $(this).closest('td').find('input[name="erid"]').val();
        var evntid = $(this).closest('td').find('input[name="evntid"]').val();
        var projid = $(this).closest('td').find('input[name="projid"]').val();
        var evntdate = $(this).closest('td').find('input[name="evntdate"]').val();
        var emptype = $(this).closest('td').find('input[name="emptype"]').val();
        var classnm = ".cdiv" + rid;

        $("#name-classs").val(classnm);
        $("#project-idd").val(projid);

        $('#assign-employee').modal('show');

        $.ajax({
            type: "post",
            url: "allocation-actions.php",
            data: {
                assignreq: true,
                rid: rid,
                evntid: evntid,
                evntdate: evntdate,
                emptype: emptype,
            },
            success: function(response) {
                $("#modal-ae").html(response);

            }
        });


    });
</script>

<?php
include_once('../include/footer.php');
?>