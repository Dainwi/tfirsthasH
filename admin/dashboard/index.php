<?php
define('TITLE', 'Admin Dashboard');
define('PAGE', 'dashboard');
define('PAGE_T', 'Dashboard');
include_once('../include/header.php');
?>
<style>
    .nav-tabs {
        border-bottom: 2px solid #9a9cab;
    }

    .nav-tabs .nav-item .nav-link {
        background-color: transparent;
        color: #9a9cab;
    }

    .nav-tabs .nav-item .active {
        background-color: transparent;
        border-bottom: 2px solid #83d8ae;
        color: #83d8ae;
    }
</style>

<?php
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

$cusID = $_SESSION['user_id'];
// echo print_r($_SESSION);

$today = date("Y-m-d");

$sql1 = "SELECT project_status FROM projects_db WHERE project_status=1 AND cus_id={$cusID}";
$res1 = mysqli_query($con, $sql1);
$currenProject = mysqli_num_rows($res1);

$sql2 = "SELECT project_status FROM projects_db WHERE project_status=2 AND cus_id={$cusID}";
$res2 = mysqli_query($con, $sql2);
$projectDone = mysqli_num_rows($res2);

$sql3 = "SELECT c_id,c_phone,c_email FROM clients_db WHERE cus_id={$cusID}";
$res3 = mysqli_query($con, $sql3);
$totalClients = mysqli_num_rows($res3);


$startdate = date('Y-m-d', strtotime(date('Y-01-01')));
$enddate = date('Y-m-d', strtotime(date('Y-12-31')));


$sql4 = "SELECT projects_db.project_id, projects_db.project_status, quotation_db.q_project_id, quotation_db.grand_total, quotation_db.q_date 
FROM projects_db 
LEFT JOIN quotation_db ON projects_db.project_id = quotation_db.q_project_id 
WHERE quotation_db.q_date BETWEEN '$startdate' and '$enddate' 
AND (projects_db.project_status=1 OR projects_db.project_status=2) 
AND cus_id={$cusID}";
$res4 = mysqli_query($con, $sql4);

if (!$res4) {
    error_log("SQL error in query: " . mysqli_error($con));
    // Handle the error as per your requirement
}

$totalRevenue = "0";
if ($res4) {
    while ($row4 = mysqli_fetch_assoc($res4)) {
        $totalRevenue += $row4['grand_total'];
        // echo $totalRevenue;
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
                        <h3 class="m-b-0"><?php echo $currenProject ?></h3>
                        <p class="m-b-0 text-muted">Current Projects</p>
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
                        <h3 class="m-b-0"><?php echo $projectDone ?></h3>
                        <p class="m-b-0 text-muted">Projects Done</p>
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
                            <small>₹ <?php echo $totalRevenue; ?></small>
                        </h3>
                        <p class="m-b-0 text-muted">Revenue(year)</p>
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
                        <h3 class="m-b-0"><?php echo $totalClients ?></h3>
                        <p class="m-b-0 text-muted">Clients</p>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- events and post production  -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-bg rounded">
                <div class="card-body">
                    <p class="text-warning">Latest Events :</p>

                    <?php
                    $sqlevents = "SELECT * FROM events_db LEFT JOIN projects_db ON projects_db.project_id = events_db.event_pr_id WHERE projects_db.project_status=1 AND event_date>='$today' AND projects_db.cus_id={$cusID} ORDER BY event_date ASC LIMIT 7";
                    $resevent = mysqli_query($con, $sqlevents);
                    if (mysqli_num_rows($resevent) > 0) {
                        echo "<div class='table-responsive'><table class='table table-sm table-bordered'><thead><th class='text-info'>Date</th><th class='text-info'>Name</th><th class='text-info'>Client</th><th></th></thead><tbody>";
                        while ($rowevent = mysqli_fetch_assoc($resevent)) {
                            $clid = $rowevent['event_cl_id'];
                            $date2 = date_create($rowevent["event_date"]);
                            $dat2 = date_format($date2, "d M Y");
                            $pridd = $rowevent['event_pr_id'];
                            $urlevnt = $url . "/admin/projects/view-ongoing-project.php?prid=" . $pridd;
                            echo "<tr>";
                            echo "<td class='text-danger'><small>{$dat2}</small></td>";
                            echo "<td class='text-light'><small>{$rowevent["event_name"]}</small></td>";

                            $sqlcl = "SELECT c_name FROM clients_db WHERE c_id={$clid}";
                            $rescl = mysqli_query($con, $sqlcl);
                            if ($rescl) {
                                while ($rowcl = mysqli_fetch_assoc($rescl)) {
                                    $clname = $rowcl['c_name'];
                                }
                                $first_word = explode(' ', trim($clname))[0];
                            }
                            echo "<td class='text-light'><small>{$first_word}</small></td>";
                            echo "<td style='width:40px'><small><a href='{$urlevnt}' class='btn btn-sm btn-outline-info py-1 px-2'><i class='fas fa-eye'></i></a></small></td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table></div>";
                    } else {
                        echo "<p class='text-center'>no events</p>";
                    }

                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-bg rounded">
                <div class="card-body">
                    <p class="text-warning">Post Production :</p>

                    <?php
                    $sqlpp = "SELECT * FROM deliverables_db LEFT JOIN projects_db ON projects_db.project_id=deliverables_db.d_pr_id WHERE projects_db.project_status=1 AND projects_db.cus_id={$cusID} ORDER BY d_due_date ASC LIMIT 7";
                    $respp = mysqli_query($con, $sqlpp);
                    if (mysqli_num_rows($respp) > 0) {
                        echo "<div class='table-responsive'><table class='table table-sm table-bordered'><thead><th class='text-info'>Due Date</th><th class='text-info'>Name</th><th class='text-info'>Client</th><th></th></thead><tbody>";
                        while ($rowpp = mysqli_fetch_assoc($respp)) {
                            $pprojid = $rowpp['d_pr_id'];
                            $pclid = $rowpp['project_client'];
                            $date1 = date_create($rowpp["d_due_date"]);
                            $dat = date_format($date1, "d M Y");
                            $urlpp = $url . "/admin/projects/view-ongoing-project.php?prid=" . $pprojid;
                            $sqlcl1 = "SELECT c_name FROM clients_db WHERE c_id={$pclid}";
                            $rescl1 = mysqli_query($con, $sqlcl1);
                            if ($rescl1) {
                                while ($rowcl1 = mysqli_fetch_assoc($rescl1)) {
                                    $clname1 = $rowcl1['c_name'];
                                }
                                $first_word1 = explode(' ', trim($clname1))[0];
                            }
                            echo "<tr>";
                            echo "<td class='text-danger'><small>{$dat}</small></td>";
                            echo "<td class='text-light'><small>{$rowpp["d_layout"]}</small></td>";
                            echo "<td class='text-light'><small>{$first_word1}</small></td>";
                            echo "<td style='width:40px'><small><a href='{$urlpp}' class='btn btn-sm btn-outline-info py-1 px-2'><i class='fas fa-eye'></i></a></small></td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table></div>";
                    } else {
                        echo "<p class='text-center'>no data</p>";
                    }


                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="card shadow card-bg p-4">
        <!-- Tabs navs -->
        <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
            <li class="nav-item me-3" role="presentation">
                <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">In-house</a>
            </li>
            <li class="nav-item mx-3" role="presentation">
                <a class="nav-link" id="ex1-tab-2" data-bs-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Freelancer</a>
            </li>

        </ul>
        <!-- Tabs navs -->
        <!-- Tabs content -->
        <div class="tab-content" id="ex1-content">
            <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                <?php
                $sql = "SELECT * FROM users_db WHERE cus_id={$cusID} AND payment_option = 0 AND user_active=1 AND cus_id={$cusID}";
                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $ii = 1;
                ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-warning">#</th>
                                    <th scope="col" class="text-warning">Employee Name</th>
                                    <th scope="col" class="text-warning">Role</th>
                                    <th scope="col" class="text-warning">Unpaid Months</th>
                                    <th scope="col" class="text-warning">Unpaid Amount</th>
                                    <th scope="col" class="text-warning"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($res)) {
                                    $uid = $row['u_id'];
                                    $user_added = $row['user_added'];
                                    $salary_amount = $row['payment_amount'];
                                    $urole = $row['user_role'];
                                ?>
                                    <?php
                                    $date = date("Y/m/d");


                                    $sql_date = "SELECT * FROM salary_details WHERE sd_user_id={$uid} ORDER BY sd_month DESC LIMIT 1";
                                    $res_date = mysqli_query($con, $sql_date);
                                    $numS = mysqli_num_rows($res_date);
                                    if ($numS > 0) {
                                        while ($row_date = mysqli_fetch_assoc($res_date)) {
                                            $last = $row_date['sd_month'];
                                            $x = strtotime($last);
                                            $last_paid = date("M Y", strtotime("+1 month", $x));
                                        }
                                    } else {
                                        $last_paid = $user_added;
                                    }

                                    $start    = (new DateTime($last_paid))->modify('first day of this month');
                                    $end      = (new DateTime($date))->modify('first day of next month');
                                    $interval = DateInterval::createFromDateString('1 month');
                                    $period   = new DatePeriod($start, $interval, $end);
                                    $r = 0;
                                    foreach ($period as $dt) {
                                        $r++;
                                    }
                                    $amt_due = $salary_amount * $r;
                                    if ($amt_due == 0) {
                                    } else {

                                    ?>

                                        <tr>
                                            <th scope="row" class="text-light"><?php echo $ii; ?></th>
                                            <td class="name text-light"><input type='hidden' value='<?php echo $row['u_id'] ?>' name='emp_id'><a type="button" class="btn-view text-light"><?php echo $row['user_name']; ?><a /></td>
                                            <td class="type"><small>
                                                    <?php

                                                    if ($urole == 1) {
                                                        echo "manager";
                                                    } else {

                                                        $sql2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$uid}";
                                                        $res2 = mysqli_query($con, $sql2);
                                                        if (mysqli_num_rows($res2) > 0) {
                                                            while ($row2 = mysqli_fetch_assoc($res2)) {
                                                                echo $row2['type_name'] . ", ";
                                                            }
                                                        }
                                                    }
                                                    ?></small></td>
                                            <td class="text-light">
                                                <?php

                                                foreach ($period as $dt1) {
                                                    echo $dt1->format("F-Y") . "<br>\n";
                                                }

                                                ?>

                                            </td>
                                            <td class="text-danger"><?php echo "Rs. " . $amt_due; ?></td>
                                            <td>
                                                <input type="hidden" name="u_id" value="<?php echo $uid ?>">
                                                <input type="hidden" name="last_paid" value="<?php echo $last_paid ?>">
                                                <button class="btn btn-primary btn-tone btn-sm pay-btn">Pay</button>
                                            </td>
                                        </tr>
                                <?php $ii++;
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else {
                    echo "No employee found";
                } ?>
            </div>

            <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">

                <?php
                $sql_taskUser = "SELECT * FROM users_db WHERE user_role !=0 AND payment_option = 1 AND user_active=1 AND cus_id={$cusID}";
                $res_taskUser = mysqli_query($con, $sql_taskUser);
                if (mysqli_num_rows($res_taskUser) > 0) {
                    $itask = 1;
                ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-warning">#</th>
                                    <th scope="col" class="text-warning">Employee Name</th>
                                    <th scope="col" class="text-warning">Role</th>
                                    <th scope="col" class="text-warning text-center">Unpaid Tasks</th>

                                    <th scope="col" class="text-warning"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row_taskUser = mysqli_fetch_assoc($res_taskUser)) {
                                    $use_id = $row_taskUser['u_id'];
                                    $use_name = $row_taskUser['user_name'];

                                    $sqlar = "SELECT * FROM assign_requirements WHERE ar_assign_to={$use_id} AND ar_paid_status=0 AND ar_date<'$today'";
                                    $resar = mysqli_query($con, $sqlar);
                                    $numTask = mysqli_num_rows($resar);
                                    if ($numTask > 0) {
                                ?>
                                        <tr>
                                            <th scope="row" class="text-light"><?php echo $itask; ?></th>
                                            <td class="name1"><input type='hidden' value='<?php echo $use_id ?>' name='emp_id'><a type="button" class="btn-view text-light"><?php echo $use_name; ?></a></td>
                                            <td class="type1"><?php
                                                                $sql_role = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$use_id} AND users_db.cus_id={$cusID}";
                                                                $res_role = mysqli_query($con, $sql_role);
                                                                while ($row_role = mysqli_fetch_assoc($res_role)) {
                                                                    echo $row_role['type_name'] . ", ";
                                                                }
                                                                ?></td>
                                            <td class="text-center text-info"><?php echo $numTask; ?></td>

                                            <td><input type="hidden" name="use_id" value="<?php echo $use_id ?>">
                                                <button class="btn btn-outline-primary btn-sm pay1-btn">view &amp; pay</button>
                                            </td>
                                        </tr>

                                        <?php
                                    } else {
                                        $sqlda = "SELECT * FROM deliverables_assign WHERE da_assign_to={$use_id} AND da_paid_status=0 AND da_status=1";
                                        $resda = mysqli_query($con, $sqlda);
                                        $numTask1 = mysqli_num_rows($resda);
                                        if ($numTask1 > 0) {
                                        ?>
                                            <tr>
                                                <th scope="row" class="text-light"><?php echo $itask; ?></th>
                                                <td class="name1"><input type='hidden' value='<?php echo $use_id ?>' name='emp_id'><a type="button" class="btn-view text-light"><?php echo $use_name; ?></a></td>
                                                <td class="type1"><?php
                                                                    $sql_role = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$use_id} AND cus_id={$cusID}";
                                                                    $res_role = mysqli_query($con, $sql_role);
                                                                    while ($row_role = mysqli_fetch_assoc($res_role)) {
                                                                        echo $row_role['type_name'] . ", ";
                                                                    }
                                                                    ?></td>
                                                <td class="text-center text-info"><?php echo $numTask1; ?></td>

                                                <td><input type="hidden" name="use_id" value="<?php echo $use_id ?>">
                                                    <button class="btn btn-outline-primary btn-sm pay1-btn">view &amp; pay</button>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>
                                <?php
                                    $itask++;
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>


                <?php
                } else {
                    echo "no employee found";
                }
                ?>
            </div>

        </div>
    </div>

</div>


<!-- modal  -->
<div class="modal fade" id="modalSalary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <div class="container">
                    <h4 class="modal-title w-100 d-block font-weight-bold text-center" id="salary-user"></h4>
                    <small class="text-center d-block text-info" id="userType"></small>

                </div>

                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <form id='updateSalaryForm' method='post'>
                <div class="modal-body mx-3" id="modal-salary">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary btn-tone" id="btn-update-salary">update <i class=" anticon anticon-export ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- modal task -->
<div class="modal fade" id="modalTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <div class="container">
                    <h4 class="modal-title w-100 d-block font-weight-bold text-center text-light" id="task-user"></h4>
                    <small class="text-center d-block text-info" id="taskUserType"></small>

                </div>

                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <form id='updateTaskForm' method='post'>
                <div class="modal-body mx-3" id="modal-task">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-outline-primary" id="btn-update-task">update </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Employee Info</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-content">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.10/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $(".pay-btn").on('click', function(e) {
            e.preventDefault();
            var uid = $(this).closest("td").find("input[name='u_id']").val();
            var lpaid = $(this).closest("td").find("input[name='last_paid']").val();
            var name = $(this).closest("tr").find("td[class='name']").text();
            var type = $(this).closest("tr").find("td[class='type']").text();
            $("#modalSalary").modal("show");
            $.ajax({
                type: "post",
                url: "../salary/ajax-load.php",
                data: {

                    salary: true,
                    userid: uid,
                    lastpaid: lpaid,
                },

                success: function(response) {
                    $("#salary-user").text(name);
                    $("#userType").text(type);
                    $("#modal-salary").html(response);
                }
            });

        });

        // pay button task
        $(".pay1-btn").on('click', function(e) {
            e.preventDefault();
            var uid = $(this).closest("td").find("input[name='use_id']").val();
            var name = $(this).closest("tr").find("td[class='name1']").text();
            var type = $(this).closest("tr").find("td[class='type1']").text();
            $("#modalTask").modal("show");
            $.ajax({
                type: "post",
                url: "../salary/ajax-load.php",
                data: {

                    task: true,
                    userid: uid,

                },

                success: function(response) {
                    $("#task-user").text(name);
                    $("#taskUserType").text(type);

                    $("#modal-task").html(response);
                }
            });

        });

        //update salary form modal
        $("#updateSalaryForm").on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this);
            $.ajax({
                type: "post",
                url: "../salary/ajax-load.php",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        $("#modalSalary").modal("hide");
                        Swal.fire({
                            icon: 'success',
                            text: 'Update Successful',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false

                        });
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);


                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',

                        })
                    }
                }
            });

        });

        //update tasks form modal
        $("#updateTaskForm").on('submit', function(e) {
            e.preventDefault();
            var form1 = new FormData(this);
            $.ajax({
                type: "post",
                url: "../salary/ajax-load.php",
                data: form1,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        $("#modalTask").modal("hide");
                        Swal.fire({
                            icon: 'success',
                            text: 'Update Successful',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false

                        });
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);


                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',

                        })
                    }
                }
            });

        });





    });

    //view modal
    $(".btn-view").on('click', function(e) {
        e.preventDefault();
        var eventid = $(this).closest('td').find("input[name='emp_id']").val();

        $.ajax({
            type: "post",
            url: "../employees/ajax-action.php",
            data: {
                loadData: true,
                userid: eventid,
            },
            success: function(response) {
                $("#staticBackdrop").modal("show");
                $("#modal-content").html(response);

            }
        });

    });
</script>

<?php
include_once('../include/footer.php');
?>