<?php
define('TITLE', 'Manager Dashboard');
define('PAGE', 'completed-tasks');
define('PAGE_T', 'Completed Tasks');
include_once('../include/header.php');
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
    <div class="row">
        <table class='table table-sm table-bordered card-bg'>
            <?php
            $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$manager_id} AND ar_date < '$today' ORDER BY ar_date ASC LIMIT {$offset},{$limit}";
            $res3 = mysqli_query($con, $sql3);
            if (mysqli_num_rows($res3) > 0) {
                echo "<thead><th class='text-warning'>sl no.</th><th class='text-warning'>Task</th><th class='text-warning'>Date</th><th class='text-warning'>venue</th></thead><tbody>";

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
                        }
                    }
                ?>
                    <tr>
                        <td class="text-light"><?php echo $ii2; ?></td>
                        <td class="text-light"><small><?php echo $evntProject . " : " . $evntName; ?></small></td>
                        <td class="text-light"><?php echo date("jS M-y", strtotime($evntDate)); ?></td>
                        <td class="text-light"><?php echo $evenue ?></td>
                    </tr>



                    <?php $ii2++;
                }
            } else {
                echo "<table class='table table-sm table-bordered card-bg'><thead><th class='text-warning'>sl no.</th><th class='text-warning'>Task</th><th class='text-warning'>Due date</th><th class='text-warning'>Status</th></thead><tbody>";
                $sql4 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$manager_id} AND da_status=1 ORDER BY da_due_date ASC LIMIT {$offset},{$limit}";
                $res4 = mysqli_query($con, $sql4);
                if (mysqli_num_rows($res4) > 0) {
                    $ii3 = $offset + 1;
                    while ($row4 = mysqli_fetch_assoc($res4)) {

                    ?>
                        <tr>
                            <td class="text-light"><?php echo $ii3; ?></td>
                            <td class="text-light"><?php echo $row4['da_item']; ?></td>
                            <td class="text-light"><?php echo date("jS M-y", strtotime($row4['da_due_date'])); ?></td>
                            <td class="text-success">completed</td>
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

            $sql_pg = "SELECT * FROM assign_requirements WHERE ar_date < '$today' AND ar_assign_to ={$manager_id}";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/completed-tasks.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/employee/tasks/completed-tasks.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/completed-tasks.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            } else {
                $sql_pg1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$manager_id} AND da_status=1";
                $res_pg1 = mysqli_query($con, $sql_pg1);
                if (mysqli_num_rows($res_pg1) > 0) {

                    $total_records = mysqli_num_rows($res_pg1);

                    $total_page = ceil($total_records / $limit);
                    echo '<ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/completed-tasks.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = "";
                        }
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/employee/tasks/completed-tasks.php?pg=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/employee/tasks/completed-tasks.php?pg=" . ($page + 1) . "'>Next</a></li>";
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

<?php
include_once('../include/footer.php');
?>