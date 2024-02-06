<?php
define('TITLE', 'Salary');
define('PAGE', 'salary');
define('PAGE_T', 'Salary - Paid');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
if (isset($_GET['recent'])) {
    $userId = $_GET['rid'];

    $sql = "SELECT user_name FROM users_db WHERE u_id={$userId}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $name = $row['user_name'];
        }
    }

    $limit = 15;
    if (isset($_GET['pg'])) {
        $page = $_GET['pg'];
    } else {
        $page = 1;
    }
    $offset = ($page - 1) * $limit;
?>



    <div class="container-fluid">
        <div class="card card-bg rounded">
            <div class="card-header">
                <h5 class="card-title mb-0 pb-0"><?php echo $name; ?></h5>
                <p class="text-gray mt-0 pt-0 mb-3">
                    <?php
                    $sql2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userId}";
                    $res2 = mysqli_query($con, $sql2);
                    if (mysqli_num_rows($res2) > 0) {
                        while ($row2 = mysqli_fetch_assoc($res2)) {
                            echo $row2['type_name'] . ", ";
                        }
                    }
                    ?></p>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-warning">#</th>
                                <th scope="col" class="text-warning">Task</th>
                                <th scope="col" class="text-warning">Paid On</th>
                                <th scope="col" class="text-warning">Paid Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$userId} AND ar_paid_status=1 ORDER BY ar_paid_date DESC LIMIT {$offset},{$limit}";
                            $res3 = mysqli_query($con, $sql3);
                            if (mysqli_num_rows($res3) > 0) {
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
                                        }
                                    }
                                ?>
                                    <tr>
                                        <th scope="row" class="text-light"><?php echo $ii2; ?></th>
                                        <td class="text-info">
                                            <?php
                                            echo $evntProject . " : " . $evntName . " - " . $evntDate;

                                            ?>
                                        </td>
                                        <td class="text-info"><?php
                                                                $date2 = $row3['ar_paid_date'];
                                                                echo date('d M Y', strtotime($date2)); ?></td>
                                                                
                                                                <td class="text-info"><?php
                                                                echo $row3['ar_amount'];
                                                            ?></td>

                                    </tr>
                                    <?php $ii2++;
                                }
                            } else {
                                $sql4 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$userId} AND da_paid_status=1 ORDER BY da_paid_date DESC LIMIT {$offset},{$limit}";
                                $res4 = mysqli_query($con, $sql4);
                                if (mysqli_num_rows($res4) > 0) {
                                    $ii3 = 1;
                                    while ($row4 = mysqli_fetch_assoc($res4)) {
                                    ?>
                                        <tr>
                                            <th scope="row" class="text-light"><?php echo $ii3; ?></th>
                                            <td class="text-info">
                                                <?php
                                                echo $row4['da_item'];

                                                ?>
                                            </td>
                                            <td class="text-info"><?php echo $row4['da_paid_date']; ?></td>
<td class="text-info"><?php echo $row4['da_amount']; ?></td>
                                        </tr>

                            <?php
                                        $ii3++;
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                <nav aria-label="Page navigation example">
                    <?php
                    $sql_pg = "SELECT * FROM assign_requirements WHERE ar_assign_to ={$userId} AND ar_paid_status=1";
                    $res_pg = mysqli_query($con, $sql_pg);
                    if (mysqli_num_rows($res_pg) > 0) {

                        $total_records = mysqli_num_rows($res_pg);

                        $total_page = ceil($total_records / $limit);
                        echo '<ul class="pagination justify-content-center">';
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/recent-task-payment.php?recent&rid={$userId}&pg=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_page; $i++) {
                            if ($i == $page) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/salary/recent-task-payment.php?recent&rid=' . $userId . '&pg=' . $i . '">' . $i . '</a></li>';
                        }
                        if ($total_page > $page) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/recent-task-payment.php?recent&rid={$userId}&pg=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                        }

                        echo '</ul>';
                    } else {
                        $sql_pg1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$userId} AND da_paid_status=1";
                        $res_pg1 = mysqli_query($con, $sql_pg1);
                        if (mysqli_num_rows($res_pg1) > 0) {

                            $total_records = mysqli_num_rows($res_pg1);

                            $total_page = ceil($total_records / $limit);
                            echo '<ul class="pagination justify-content-center">';
                            if ($page > 1) {
                                echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/recent-task-payment.php?recent&rid={$userId}&pg=" . ($page - 1) . "'>Prev</a></li>";
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                            }
                            for ($i = 1; $i <= $total_page; $i++) {
                                if ($i == $page) {
                                    $active = "active";
                                } else {
                                    $active = "";
                                }
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/salary/recent-task-payment.php?recent&rid=' . $userId . '&pg=' . $i . '">' . $i . '</a></li>';
                            }
                            if ($total_page > $page) {
                                echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/recent-task-payment.php?recent&rid={$userId}&pg=" . ($page + 1) . "'>Next</a></li>";
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
    </div>

<?php
} else {
    echo "nothing found";
}
include_once("../include/footer.php");
?>