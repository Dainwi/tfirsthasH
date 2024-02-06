<?php
define('TITLE', 'Payment Page');
define('PAGE', 'payment');
define('PAGE_T', 'Payments');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$sql = "SELECT payment_option FROM users_db WHERE u_id={$manager_id}";
$res = mysqli_query($con, $sql);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $paymentType = $row['payment_option'];
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
        <div class="card-body">
            <?php if ($paymentType == 0) {
                $sql3 = "SELECT * FROM salary_details WHERE sd_user_id={$manager_id} ORDER BY sd_month DESC LIMIT {$offset},{$limit}";
                $res3 = mysqli_query($con, $sql3);
                if (mysqli_num_rows($res3) > 0) {
                    $ii = $offset + 1; ?>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-warning">#</th>
                                    <th scope="col" class="text-warning">Paid Month</th>
                                    <th scope="col" class="text-warning">Paid On</th>
                                     <!--<th scope="col" class="text-warning">Paid Amount</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row3 = mysqli_fetch_assoc($res3)) {
                                ?>
                                    <tr>
                                        <th scope="row" class="text-light"><?php echo $ii; ?></th>
                                        <td class="text-light">
                                            <?php
                                            $date = $row3['sd_month'];
                                            echo date("F-Y", strtotime($date));
                                            ?>
                                        </td>
                                        <td class="text-light"><?php echo $row3['sd_paid_on']; ?></td>

                                    </tr>
                                <?php $ii++;
                                } ?>

                            </tbody>
                        </table>
                    </div>
                <?php
                }  ?>

            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-warning">#</th>
                                <th scope="col" class="text-warning">Task</th>
                                <th scope="col" class="text-warning">Paid On</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql3 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$manager_id} AND ar_paid_status =1 ORDER BY ar_paid_date DESC LIMIT {$offset},{$limit}";
                            $res3 = mysqli_query($con, $sql3);
                            if (mysqli_num_rows($res3) > 0) {
                                $ii2 = $offset + 1;
                            ?>
                                <?php
                                while ($row3 = mysqli_fetch_assoc($res3)) {
                                    $evntId = $row3['ar_event_id'];
                                    $date2 = $row3['ar_paid_date'];
                                    $datee = date('d-m-Y', strtotime($date2));

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
                                        <td class="text-light"><?php echo $datee; ?></td>

                                    </tr>
                                    <?php $ii2++;
                                }
                            } else {
                                $sql4 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$manager_id} AND da_paid_status=1 ORDER BY da_paid_date DESC LIMIT {$offset},{$limit}";
                                $res4 = mysqli_query($con, $sql4);
                                if (mysqli_num_rows($res4) > 0) {
                                    $ii3 = $offset + 1;
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


            <?php } ?>

        </div>
        <div class="card-footer">
            <nav aria-label="Page navigation example">
                <?php
                if ($paymentType == 0) {
                    $sql_pg = "SELECT * FROM salary_details WHERE sd_user_id={$manager_id}";
                    $res_pg = mysqli_query($con, $sql_pg);
                    if (mysqli_num_rows($res_pg) > 0) {

                        $total_records = mysqli_num_rows($res_pg);

                        $total_page = ceil($total_records / $limit);
                        echo '<ul class="pagination justify-content-center">';
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_page; $i++) {
                            if ($i == $page) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/manager/payments/index.php?pg=' . $i . '">' . $i . '</a></li>';
                        }
                        if ($total_page > $page) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                        }

                        echo '</ul>';
                    }
                } else {
                    $sql_pg = "SELECT * FROM assign_requirements WHERE ar_paid_status =1 AND ar_assign_to ={$manager_id}";
                    $res_pg = mysqli_query($con, $sql_pg);
                    if (mysqli_num_rows($res_pg) > 0) {

                        $total_records = mysqli_num_rows($res_pg);

                        $total_page = ceil($total_records / $limit);
                        echo '<ul class="pagination justify-content-center">';
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_page; $i++) {
                            if ($i == $page) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/manager/payments/index.php?pg=' . $i . '">' . $i . '</a></li>';
                        }
                        if ($total_page > $page) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                        }

                        echo '</ul>';
                    } else {
                        $sql_pg1 = "SELECT * FROM deliverables_assign WHERE da_assign_to ={$manager_id} AND da_paid_status=1";
                        $res_pg1 = mysqli_query($con, $sql_pg1);
                        if (mysqli_num_rows($res_pg1) > 0) {

                            $total_records = mysqli_num_rows($res_pg1);

                            $total_page = ceil($total_records / $limit);
                            echo '<ul class="pagination justify-content-center">';
                            if ($page > 1) {
                                echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=pg=" . ($page - 1) . "'>Prev</a></li>";
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                            }
                            for ($i = 1; $i <= $total_page; $i++) {
                                if ($i == $page) {
                                    $active = "active";
                                } else {
                                    $active = "";
                                }
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/manager/payments/index.php?pg=' . $userId . '&pg=' . $i . '">' . $i . '</a></li>';
                            }
                            if ($total_page > $page) {
                                echo "<li class='page-item'><a class='page-link' href='{$url}/manager/payments/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                            }

                            echo '</ul>';
                        }
                    }
                }

                ?>



            </nav>

        </div>

    </div>
</div>

<?php
include_once("../include/footer.php");
?>