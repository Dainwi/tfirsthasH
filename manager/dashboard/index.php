<?php
define('TITLE', 'Manager Dashboard');
define('PAGE', 'dashboard');
define('PAGE_T', 'Dashboard');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['cus_id'];
$manID = $_SESSION['user_id'];
// echo $cusID;

$today = date("Y-m-d");

$sql1 = "SELECT project_status FROM projects_db LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE project_status=1 AND assign_manager_db.am_user_id ={$manager_id}";
$res1 = mysqli_query($con, $sql1);
$currenProject = mysqli_num_rows($res1);

$sql2 = "SELECT project_status FROM projects_db LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE project_status=2 AND assign_manager_db.am_user_id ={$manager_id}";
$res2 = mysqli_query($con, $sql2);
$projectDone = mysqli_num_rows($res2);




$sql = "SELECT payment_option,payment_amount FROM users_db WHERE u_id={$manager_id}";
$res = mysqli_query($con, $sql);
if ($res) {
    while ($row2 = mysqli_fetch_assoc($res)) {
        $paymentType = $row2['payment_option'];
        $pamount = $row2['payment_amount'];
    }
}

if ($paymentType == 0) {
    $sql3 = "SELECT * FROM salary_details WHERE sd_user_id={$manager_id}";
    $res3 = mysqli_query($con, $sql3);
    $numrow = mysqli_num_rows($res3);
    $reven = $numrow * $pamount;
} else {
    $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$manager_id} AND ar_paid_status =1 ";
    $res3 = mysqli_query($con, $sql3);
    $reven = 0;
    if (mysqli_num_rows($res3) > 0) {
        while ($row3 = mysqli_fetch_assoc($res3)) {
            $reven += $row3['ar_amount'];
        }
    }
}

// Get the first and last day of the current month
$firstDayOfMonth = date('Y-m-01'); // First day of the current month
$lastDayOfMonth = date('Y-m-t'); // Last day of the current month

$sql4 = "SELECT COUNT(*) as project_count FROM assign_manager_db LEFT JOIN projects_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE cus_id = '$cusID' AND am_user_id = '$manID' AND project_sdate BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
$result = mysqli_query($con, $sql4);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $projectCount = $row['project_count'];
    // echo "Number of projects this month: " . $projectCount;
} else {
    // echo "Error: " . mysqli_error($con);
}

$sql5 = "SELECT COUNT(*) as project_count FROM assign_manager_db WHERE am_user_id = '$manID'";
$result5 = mysqli_query($con, $sql5);
if ($result5) {
    $row5 = mysqli_fetch_assoc($result5);
    $manProject = $row5['project_count'];
    // echo "Number of projects this month: " . $manProject;
} else {
    echo "Error: " . mysqli_error($con);
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
                            <small> <?php echo $manProject; ?></small>
                        </h3>
                        <p class="m-b-0 text-muted">Total Projectss</p>
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
                        <h3 class="m-b-0"><?php echo $projectCount ?></h3>
                        <p class="m-b-0 text-muted">Projects This Month</p>
                    </div>

                </div>
            </div>
        </div>
        <!-- <div class="col-md-3 col-lg-3">-->
        <!--    <div class="card card-bg rounded stats-card">-->
        <!--        <div class="card-body pb-2 pt-3">-->

        <!--            <div class="stats-icon change-success mx-2">-->
        <!--                <i class="material-icons">trending_up</i>-->
        <!--            </div>-->
        <!--            <div class="m-l-15">-->
        <!--                <h3 class="m-b-0"></h3>-->
        <!--                <p class="m-b-0 text-muted">Clients</p>-->
        <!--            </div>-->

        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

    </div>


    <!-- events and post production  -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-bg rounded">
                <div class="card-body">
                    <p class="text-warning">Latest Events :</p>

                    <?php
                    $sqlevents = "SELECT * FROM events_db LEFT JOIN projects_db ON projects_db.project_id = events_db.event_pr_id LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE projects_db.project_status=1 AND assign_manager_db.am_user_id ={$manager_id} AND event_date>='$today' ORDER BY event_date ASC LIMIT 7";
                    $resevent = mysqli_query($con, $sqlevents);
                    if (mysqli_num_rows($resevent) > 0) {
                        echo "<div class='table-responsive'><table class='table table-sm table-bordered'><thead><th class='text-info'>Date</th><th class='text-info'>Name</th><th class='text-info'>Client</th><th></th></thead><tbody>";
                        while ($rowevent = mysqli_fetch_assoc($resevent)) {
                            $clid = $rowevent['event_cl_id'];
                            $pridd = $rowevent['event_pr_id'];
                            $dat =  date("d M Y", strtotime($rowevent["event_date"]));
                            $urlevnt = $url . "/manager/projects/view-project.php?prid=" . $pridd;
                            echo "<tr>";
                            echo "<td class='text-danger'><small>{$dat}</small></td>";
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
                    $sqlpp = "SELECT * FROM deliverables_db LEFT JOIN projects_db ON projects_db.project_id=deliverables_db.d_pr_id LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE projects_db.project_status=1 AND assign_manager_db.am_user_id ={$manager_id} ORDER BY d_due_date ASC LIMIT 7";
                    $respp = mysqli_query($con, $sqlpp);
                    if (mysqli_num_rows($respp) > 0) {
                        echo "<div class='table-responsive'><table class='table table-sm table-bordered'><thead><th class='text-info'>Due Date</th><th class='text-info'>Name</th><th class='text-info'>Client</th><th></th></thead><tbody>";
                        while ($rowpp = mysqli_fetch_assoc($respp)) {
                            $pprojid = $rowpp['d_pr_id'];
                            $pclid = $rowpp['project_client'];
                            $datt =  date("d M Y", strtotime($rowpp["d_due_date"]));
                            $urlpp = $url . "/manager/projects/view-project.php?prid=" . $pprojid;
                            $sqlcl1 = "SELECT c_name FROM clients_db WHERE c_id={$pclid}";
                            $rescl1 = mysqli_query($con, $sqlcl1);
                            if ($rescl1) {
                                while ($rowcl1 = mysqli_fetch_assoc($rescl1)) {
                                    $clname1 = $rowcl1['c_name'];
                                }
                                $first_word1 = explode(' ', trim($clname1))[0];
                            }
                            echo "<tr>";
                            echo "<td class='text-danger'><small>{$datt}</small></td>";
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



</div>

<?php
include_once('../include/footer.php');
?>