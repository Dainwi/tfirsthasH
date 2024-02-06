<?php
define('TITLE', 'Salary');
define('PAGE', 'salary');
define('PAGE_T', ' Salary - Paid');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

if (isset($_GET['recent'])) {
    $userId = $_GET['rid'];

    $sql = "SELECT user_name,user_role FROM users_db WHERE u_id={$userId}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $name = $row['user_name'];
            $role = $row['user_role'];
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
        <div class="card card-bg round-10">
            <div class="card-header pt-2 ps-3">
                <h5 class="card-title mb-0 pb-0 text-warning"><?php echo $name; ?></h5>
                <p class="text-light mt-0 pt-0 mb-3">
                    <?php
                    if ($role == 1) {
                        echo "manager";
                    } else {
                        $sql2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userId}";
                        $res2 = mysqli_query($con, $sql2);
                        if (mysqli_num_rows($res2) > 0) {
                            while ($row2 = mysqli_fetch_assoc($res2)) {
                                echo $row2['type_name'] . ", ";
                            }
                        }
                    }  ?></p>
            </div>
            <div class="card-body">
                <?php
                $sql3 = "SELECT * FROM salary_details WHERE sd_user_id={$userId} ORDER BY sd_month DESC LIMIT {$offset},{$limit}";
                $res3 = mysqli_query($con, $sql3);
                if (mysqli_num_rows($res3) > 0) {
                    $ii = 1;
                ?>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-info">#</th>
                                    <th scope="col" class="text-info">Paid Month</th>
                                    <th scope="col" class="text-info">Paid On</th>
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


            </div>
            <div class="card-footer">
                <nav aria-label="Page navigation example">
                    <?php
                    $sql_pg = "SELECT * FROM salary_details WHERE sd_user_id={$userId}";
                    $res_pg = mysqli_query($con, $sql_pg);
                    if (mysqli_num_rows($res_pg) > 0) {

                        $total_records = mysqli_num_rows($res_pg);

                        $total_page = ceil($total_records / $limit);
                        echo '<ul class="pagination justify-content-center">';
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/all-recent-salary.php?recent&rid={$userId}&pg=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_page; $i++) {
                            if ($i == $page) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/salary/all-recent-salary.php?recent&rid=' . $userId . '&pg=' . $i . '">' . $i . '</a></li>';
                        }
                        if ($total_page > $page) {
                            echo "<li class='page-item'><a class='page-link' href='{$url}/admin/salary/all-recent-salary.php?recent&rid={$userId}&pg=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                        }

                        echo '</ul>';
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