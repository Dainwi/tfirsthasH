<?php
define('TITLE', 'Salary');
define('PAGE', 'salary');
define('PAGE_T', 'Salary - Paid');
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

$cusID = $_SESSION['user_id'];
?>

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
            $sql = "SELECT * FROM users_db WHERE user_role !=0 AND user_active=1 AND payment_option = 0 AND cus_id={$cusID}";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                $ii = 1;
            ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class='text-warning'>#</th>
                                <th scope="col" class='text-warning'>Name</th>
                                <th scope="col" class='text-warning'>Role</th>
                                <th scope="col" class='text-warning'>Last Paid</th>
                                <th scope="col" class='text-warning'></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($res)) {
                                $user_id = $row['u_id'];
                                $user_role = $row['user_role'];
                            ?>
                                <tr>
                                    <th scope="row" class="text-light"><?php echo $ii; ?></th>
                                    <td><input type='hidden' value='<?php echo $row["u_id"] ?>' name='emp_id'><a type="button" class='text-light btn-view'><?php echo $row['user_name']; ?></a></td>
                                    <td><small><?php
                                                if ($user_role == 1) {
                                                    echo "manager";
                                                } else {
                                                    $sql_role = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$user_id}";
                                                    $res_role = mysqli_query($con, $sql_role);
                                                    while ($row_role = mysqli_fetch_assoc($res_role)) {
                                                        echo $row_role['type_name'] . ", ";
                                                    }
                                                }
                                                ?></small></td>
                                    <td class="text-light">
                                        <?php
                                        $sqll = "SELECT * FROM salary_details WHERE sd_user_id={$user_id} ORDER BY sd_month DESC LIMIT 1";
                                        $resl = mysqli_query($con, $sqll);
                                        if (mysqli_num_rows($resl) > 0) {
                                            while ($rowl = mysqli_fetch_assoc($resl)) {
                                                $date = $rowl['sd_month'];
                                            }
                                            $m = 1;
                                            echo date('F-Y', strtotime($date));
                                        } else {
                                            $m = 0;
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    <td><?php if ($m == 1) {
                                            $linkk = $url . "/admin/salary/all-recent-salary.php?recent&rid=" . $user_id;
                                            echo "<a href='{$linkk}' class='btn btn-secondary btn-tone btn-sm'>View All</a>";
                                        } else {
                                            echo "";
                                        } ?></td>
                                </tr>
                            <?php $ii++;
                            } ?>

                        </tbody>
                    </table>
                </div>
            <?php } else {
                echo "no employee found";
            } ?>
        </div>

        <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
            <?php
            $sql2 = "SELECT * FROM users_db WHERE user_role !=0 AND payment_option = 1 AND user_active=1 AND cus_id={$cusID}";
            $res2 = mysqli_query($con, $sql2);
            if (mysqli_num_rows($res2) > 0) {
                $ii2 = 1;
            ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-warning">#</th>
                                <th scope="col" class="text-warning">Name</th>
                                <th scope="col" class="text-warning">Role</th>
                                <th scope="col" class="text-warning">Last Paid</th>
                                <th scope="col" class="text-warning"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($res2)) {
                                $user_id2 = $row2['u_id'];
                            ?>
                                <tr>
                                    <th scope="row" class="text-light"><?php echo $ii2; ?></th>
                                    <td><input type='hidden' value='<?php echo $user_id2 ?>' name='emp_id'><a class="btn-view text-light" type="button"><?php echo $row2['user_name']; ?></a></td>
                                    <td>
                                        <?php
                                        $sql_role2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$user_id2}";
                                        $res_role2 = mysqli_query($con, $sql_role2);
                                        while ($row_role2 = mysqli_fetch_assoc($res_role2)) {
                                            echo $row_role2['type_name'] . ", ";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-info">
                                        <?php
                                        $sqll2 = "SELECT * FROM assign_requirements WHERE ar_assign_to={$user_id2} AND ar_paid_status = 1 ORDER BY ar_paid_date DESC LIMIT 1";
                                        $resl2 = mysqli_query($con, $sqll2);
                                        if (mysqli_num_rows($resl2) > 0) {
                                            while ($rowl2 = mysqli_fetch_assoc($resl2)) {
                                                $date2 = $rowl2['ar_paid_date'];
                                                echo date('d M Y', strtotime($date2));
                                            }
                                            $m1 = 1;
                                        } else {
                                            $sqll3 = "SELECT * FROM deliverables_assign WHERE da_assign_to={$user_id2} AND da_paid_status= 1 ORDER BY da_paid_date DESC LIMIT 1";
                                            $resll3 = mysqli_query($con, $sqll3);
                                            if (mysqli_num_rows($resll3) > 0) {
                                                while ($rowl3 = mysqli_fetch_assoc($resll3)) {
                                                    $date2 = $rowl3['da_paid_date'];
                                                    echo date('d M Y', strtotime($date2));
                                                }
                                                $m1 = 1;
                                            } else {
                                                $m1 = 0;
                                                echo "N/A";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($m1 == 1) {
                                            $linkk = $url . "/admin/salary/recent-task-payment.php?recent&rid=" . $user_id2;
                                            echo "<a href='{$linkk}' class='btn btn-outline-secondary btn-sm'>View All</a>";
                                        } else {
                                            echo "";
                                        }
                                        ?></td>
                                </tr> <?php $ii2++;
                                    } ?>
                        </tbody>
                    </table>
                </div>

            <?php
            }
            ?>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="staticBackdropLabel">Team Member Info</h5>
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

<script>
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


<?php include_once("../include/footer.php"); ?>