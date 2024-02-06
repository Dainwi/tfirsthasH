<?php
define('TITLE', 'Employees');
define('PAGE', 'employee');
define('PAGE_T', 'Team Members');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
?>
<!-- <style>
    input[type="checkbox"] {
        cursor: pointer;
        border-radius: 4px;

        opacity: 1;
        filter: invert(0.2);
    }
</style> -->
<?php
$cusID = $_SESSION['user_id'];

$limit = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;

$sql_pg = "SELECT * FROM users_db WHERE (user_role = 1 OR user_role =2) AND user_active=1 AND cus_id={$cusID}";
$res_pg = mysqli_query($con, $sql_pg);
$numRow = mysqli_num_rows($res_pg);

$sql = "SELECT * FROM users_db WHERE (user_role = 1 OR user_role =2) AND user_active=1 AND cus_id={$cusID} ORDER BY u_id DESC LIMIT {$offset},{$limit}";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {

?>
    <div class="container mt-0">
        <div class="d-flex justify-content-between align-items-center pb-2">
            <h6 class="mt-0 text-center">Total Members : <span class="text-danger"><?php echo $numRow ?></span></h6>
            <button class="btn btn-outline-danger btn-sm py-1 btn-delete-all" data-bs-target="#staticDeleteAll" data-bs-toggle="modal">Delete All</button>
        </div>

        <div class="table-responsive rounded">
            <table class="table table-bordered card-bg rounded">
                <thead>
                    <tr>
                        <th class="text-center text-warning">NAME</th>
                        <th class="text-center text-warning">TYPE</th>
                        <th class="text-center text-warning">ROLE</th>
                        <th class="text-center text-warning">AUTH</th>
                        <th class="text-center text-warning">ACTION</th>

                    </tr>
                </thead>
                <tbody>

                    <!-- Card View -->
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user_id = $row['u_id'];
                        $etype = $row['payment_option'];
                        $eauth = $row['user_online'];
                        if ($eauth == 0) {
                            $sele = "text-danger";
                        } else {
                            $sele = "text-success";
                        }

                        if ($etype == 0) {
                            $emptype = "In-house";
                        } else {
                            $emptype = "Freelancer";
                        }

                    ?>

                        <tr>
                            <td class="text-info">
                                <div class="d-inline-block avatar avatar-image mb-0 pb-1 " style="height: 45px; width: 45px;">
                                    <img src="<?php
                                                $pic = $row['user_photo'];

                                                if ($pic == 0) {
                                                    echo $url . "/assets/images/avatars/profile-image.png";
                                                } else {
                                                    echo $url . "/assets/images/profile/" . $pic;
                                                }
                                                ?>" alt="" class="img-fluid w-75">
                                </div><span><?php echo $row['user_name']; ?></span>
                            </td>
                            <td class="text-center"><?php echo $emptype ?></td>
                            <td class="text-center text-info">
                                <?php
                                if ($row['user_role'] == 1) {
                                    echo "<small>manager</small>";
                                } else {
                                    $sql_type = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$user_id};";
                                    $res_type = mysqli_query($con, $sql_type);

                                    if ($res_type) {
                                        while ($r_type = mysqli_fetch_assoc($res_type)) {
                                            echo "<small>" . $r_type['type_name'] . "</small><br> ";
                                        }
                                    }
                                }


                                ?> </td>
                            <td class="text-center text-info">
                                <input type="hidden" name="emp_id" value="<?php echo $user_id ?>">
                                <select class="m-auth form-select form-select-sm <?php echo $sele ?>" aria-label="form-select-sm example" style="max-width: 110px;">

                                    <option class="text-success" <?php if ($eauth == 1) {
                                                                        echo "selected";
                                                                    } ?> value="1">Enabled</option>
                                    <option class="text-danger" <?php if ($eauth == 0) {
                                                                    echo "selected";
                                                                } ?> value="0">Disabled</option>

                                </select>
                            </td>
                            <td class="text-center text-info">
                                <a href="<?php echo $url . "/admin/attendance/view-attendance.php?user=" . $user_id; ?>" class="btn btn-outline-warning btn-round btn-sm py-1">
                                    <span>Attendance</span>
                                </a>
                                <input type="hidden" name="emp_id" value="<?php echo $user_id ?>">
                                <button class="btn text-primary btn-sm py-1 px-2 btn-view">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?php echo $url . "/admin/employees/edit-member.php?mid=" . $user_id; ?>" class="btn text-secondary btn-sm py-1 px-2">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button class="btn text-danger btn-sm py-1 px-2 btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>


                    <?php  } ?>



                </tbody>
            </table>
        </div>


    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/employees/index.php?page=" . ($page - 1) . "'><i class='fas fa-angle-left'></i> Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/employees/index.php?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/employees/index.php?page=" . ($page + 1) . "'>Next <i class='fas fa-angle-right'></i></a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>

<?php } else {
    echo "<p class='text-center'>no employee</p>";
} ?>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdropDeluser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-white">Are you sure?</h4>
                <p class="text-center text-info">You won't be able to revert this!</p>
                <form action="employee-actions.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="deluserid" id="del-user" value="">
                        <input type="hidden" name="btn_del_user" value="1">
                        <button type="submit" class="mr-2 btn btn-secondary">Yes, delete it!</button>
                        <button type="button" class="ml-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="staticBackdropLabel">Employee Info</h5>
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

<!-- Modal -->
<form action='employee-actions.php' method='post'>
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="staticBackdropLabel">Edit Employee Info</h5>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-content1">
                    ...
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update-emp" value="1">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Update</button>

                </div>
            </div>
        </div>
    </div>
</form>

<!-- modal delete  -->
<div class="modal fade" id="staticDeleteAll" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-white mt-3">Are you sure?</h4>
                <p class="text-center text-warning">You won't be able to revert this! All Team Members will delete including tasks, salary and more.</p>
                <form action="employee-actions.php" method="post">
                    <div class="text-center mt-4">

                        <input type="hidden" name="btn_del_allteam" value="1">
                        <button type="submit" class="me-2 btn btn-danger">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(".m-auth").change(function(e) {
        e.preventDefault();
        var authh = $(this).closest('td').find(".m-auth option:selected").val();
        var empid = $(this).closest("td").find("input[name='emp_id']").val();
        // var authe = $(".m-auth option:selected").val();
        $(this).closest('td').find('.m-auth').toggleClass("text-danger");
        $(this).closest('td').find('.m-auth').toggleClass("text-success");

        $.ajax({
            type: "post",
            url: "employee-actions.php",
            data: {
                mauthentication: true,
                auth: authh,
                mid: empid,
            },
            success: function(response) {
                if (response == 0) {
                    alert("somthing went wrong");
                }
            }
        });


    });

    //view modal
    $(".btn-view").on("click", function(e) {
        e.preventDefault();
        var eventid = $(this).closest("td").find("input[name='emp_id']").val();

        $.ajax({
            type: "post",
            url: "ajax-action.php",
            data: {
                loadData: true,
                userid: eventid,
            },
            success: function(response) {
                $("#staticBackdrop").modal("show");
                $("#modal-content").html(response);
            },
        });
    });

    //del user
    $(".btn-delete").on("click", function(e) {
        e.preventDefault();
        var empid = $(this).closest("td").find("input[name='emp_id']").val();
        $("#del-user").val(empid);
        $("#staticBackdropDeluser").modal("show");
    });

    //edit modal
    $(".btn-edit").on("click", function(e) {
        e.preventDefault();
        var eventid = $(this).closest("td").find("input[name='emp_id']").val();

        $.ajax({
            type: "post",
            url: "ajax-action.php",
            data: {
                editData: true,
                userid: eventid,
            },
            success: function(response) {
                $("#staticBackdrop1").modal("show");
                $("#modal-content1").html(response);
            },
        });
    });
</script>

<?php
include_once('../include/footer.php');
?>