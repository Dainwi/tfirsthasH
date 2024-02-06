<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('TITLE', 'Daily Tasks');
define('PAGE', 'daily-tasks');
define('PAGE_T', 'Daily Tasks');
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
$cusID = $_SESSION['cus_id'];
$today = date("d-m-Y");
$date = date("Y-m-d");
?>

<div class="card shadow card-bg p-4">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
        <li class="nav-item me-3" role="presentation">
            <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">Today's Tasks</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="ex1-tab-2" data-bs-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Pending Tasks</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="ex1-tab-3" data-bs-toggle="tab" href="#ex1-tabs-3" role="tab" aria-controls="ex1-tabs-3" aria-selected="false">Completed Tasks</a>
        </li>

    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content" id="ex1-content">
        <!-- todays tasks start  -->
        <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
            <div class="card card-bg">
                <div class="card-body pt-2">
                    <div class="d-flex justify-content-between align-items-center">

                        <p class="text-right"> <button class="btn btn-outline-info btn-round btn-sm" id="add-task-btn"><i class="fas fa-plus-circle"></i> Add Task</button></p>
                    </div>
                    <div class="card-view mt-2">

                        <div class="border border-dark table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-warning">#</th>
                                        <th class="text-warning">Name</th>
                                        <th class="text-warning">type</th>
                                        <th class="text-warning">task details</th>
                                        <th class="text-warning">Due Date</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <div class="row">
                                        <?php
                                        $sql = "SELECT * FROM daily_tasks LEFT JOIN users_db ON users_db.u_id = daily_tasks.dt_user_id WHERE dt_date ='{$date}' AND users_db.cus_id = '$cusID' ORDER BY dt_id DESC";
                                        $res = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($res) > 0) {
                                            $dt = 1;
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                $userid = $row['u_id'];
                                                $duserName = $row['user_name'];
                                                $dtaskIdd = $row['dt_id'];
                                                $userrole = $row['user_role'];
                                                $tasks = $row['dt_details'];
                                                $edued = $row['dt_due_date'];
                                                $task_details = substr_replace($tasks, "...", 45);
                                                $due_date = date_create($row['dt_due_date']);
                                                $due_date = date_format($due_date, "d M Y");

                                        ?>


                                                <tr>
                                                    <td class="text-light"><?php echo $dt; ?></td>
                                                    <td class="text-light"><?php echo $row['user_name']; ?></td>
                                                    <td class="text-light"> <small class="pt-0 mt-0 text-light d-block">
                                                            <?php
                                                            if ($userrole == 1) {
                                                                echo "manager";
                                                            } else {
                                                                $sql2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userid}";
                                                                $res2 = mysqli_query($con, $sql2);
                                                                if (mysqli_num_rows($res2) > 0) {
                                                                    while ($r_type2 = mysqli_fetch_array($res2)) {
                                                                        echo $r_type2['type_name'] . ", ";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </small></td>
                                                    <td class="text-light"><?php echo $task_details ?></td>
                                                    <td class="text-light"><?php echo $due_date ?></td>

                                                    <td>
                                                        <div>
                                                            <input type="hidden" name="name_taskid" value="<?php echo $row['dt_id']; ?>">
                                                            <button class="btn btn-sm btn-outline-danger btn-deltask"><i class="fas fa-trash-alt"></i></button>
                                                            <button class="btn btn-sm btn-outline-secondary btn-viewtask"> <i class="fas fa-eye"></i></button>
                                                            <button class='btn btn-sm btn-outline-secondary btn-edittask' data-date="<?php echo $edued; ?>" data-name='<?php echo $duserName; ?>' data-id="<?php echo $dtaskIdd; ?>" data-task="<?php echo $tasks; ?>"> <i class="fas fa-pencil-alt"></i> </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                        <?php
                                                $dt++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>no task assign today</td></tr>";
                                        }
                                        ?>

                                </tbody>
                            </table>
                        </div>



                    </div>
                </div>

            </div>
        </div>

        <!-- todays tasks end  -->

        <!-- pending tasks start  -->
        <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
            <div class="card round-10">
                <div class="card-body">

                    <?php
                    $sql1 = "SELECT *,users_db.user_name,users_db.u_id FROM  daily_tasks LEFT JOIN users_db ON daily_tasks.dt_user_id = users_db.u_id WHERE dt_manager_status = 0 AND dt_date !='{$date}' AND users_db.user_active=1 AND users_db.cus_id='{$cusID}' ORDER BY dt_id DESC LIMIT 10";
                    $res1 = mysqli_query($con, $sql1);
                    if (mysqli_num_rows($res1) > 0) {
                        $i = 1;
                    ?>

                        <div class="m-t-30">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-warning">#</th>
                                            <th class="text-warning">Name</th>
                                            <th class="text-warning">type</th>
                                            <th class="text-warning">task details</th>
                                            <th class="text-warning">Date</th>
                                            <th class="text-warning">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row1 = mysqli_fetch_assoc($res1)) {
                                            $usid1 = $row1['u_id'];
                                            $userrole1 = $row1['user_role'];
                                            $uname1 = $row1['user_name'];
                                            $taskId1 = $row1['dt_id'];
                                            $taskId1 = $row1['dt_id'];

                                            $tasks1 = $row1['dt_details'];
                                            $task_details1 = substr_replace($tasks1, "...", 20);
                                            $date1 = date_create($row1['dt_date']);
                                            $date1 = date_format($date1, "d M Y");
                                            $duedate1 = $row1['dt_due_date'];
                                            $complete1 = $row1['dt_completed'];
                                            if ($complete1 == 1) {
                                                $completed1 = "<span class=''>completed</span>";
                                            } else {
                                                $completed1 = "<span class=''>not completed</span>";
                                            }
                                            $manager_status1 = $row1['dt_manager_status'];


                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td class="user-name"><?php echo $row1['user_name']; ?></td>
                                                <td class="user-role"> <small>
                                                        <?php
                                                        $sql_role21 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$usid1} AND users_db.user_active=1";
                                                        $res_role21 = mysqli_query($con, $sql_role21);
                                                        while ($row_role21 = mysqli_fetch_assoc($res_role21)) {
                                                            echo $row_role21['type_name'] . ", ";
                                                        }
                                                        ?></small></td>
                                                <td class="task-info"><input type="hidden" name="task_info" value="<?php echo $tasks1; ?>"><?php echo $task_details1;  ?></td>
                                                <td class="task-seen"><?php echo $date1; ?></td>
                                                <td class="task-completed"><?php echo $completed1; ?></td>
                                                <td>
                                                    <div class="d-flex"><input type="hidden" name="user" value="<?php echo $usid1; ?>">
                                                        <input type="hidden" name="name_taskid" value="<?php echo $taskId1; ?>">
                                                        <input type="hidden" name="manager_status" value="<?php echo $manager_status1; ?>">
                                                        <button class='btn btn-sm btn-primary btn-viewtask'>view</button>
                                                        <button class='btn btn-sm btn-secondary btn-edittask' data-date="<?php echo $duedate1; ?>" data-name='<?php echo $uname1; ?>' data-id="<?php echo $taskId1; ?>" data-task="<?php echo $tasks1; ?>"> <i class="fas fa-pencil-alt"></i> </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p class="text-center mt-4"><a href="<?php echo $url . "/manager/daily-task/pending-tasks.php"; ?>">view all</a></p>
                    <?php
                    } else {
                        echo "<p class='text-center'>No Pending Task</p>";
                    } ?>
                </div>

            </div>
        </div>
        <!-- pending tasks end  -->

        <!-- completed tasks start  -->
        <div class="tab-pane fade" id="ex1-tabs-3" role="tabpanel" aria-labelledby="ex1-tab-3">
            <div class="card shadow-box round-10">
                <div class="card-body">

                    <?php
                    $sql1 = "SELECT *,users_db.user_name,users_db.u_id FROM  daily_tasks LEFT JOIN users_db ON daily_tasks.dt_user_id = users_db.u_id WHERE dt_manager_status = 1 AND dt_date !='{$date}' AND users_db.cus_id='{$cusID}' ORDER BY dt_id DESC LIMIT 10";
                    $res1 = mysqli_query($con, $sql1);
                    if (mysqli_num_rows($res1) > 0) {
                        $i = 1;
                    ?>

                        <div class="m-t-30">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-warning">#</th>
                                            <th class="text-warning">Name</th>
                                            <th class="text-warning">type</th>
                                            <th class="text-warning">task details</th>
                                            <th class="text-warning">Date</th>
                                            <th class="text-warning">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row1 = mysqli_fetch_assoc($res1)) {
                                            $usid1 = $row1['u_id'];

                                            $taskId1 = $row1['dt_id'];
                                            $tasks1 = $row1['dt_details'];
                                            $task_details1 = substr_replace($tasks1, "...", 20);
                                            $date1 = $row1['dt_date'];
                                            $complete1 = $row1['dt_completed'];
                                            if ($complete1 == 1) {
                                                $completed1 = "<span class='badge badge-pill badge-green'>completed</span>";
                                            } else {
                                                $completed1 = "<span class='badge badge-pill badge-volcano'>not completed</span>";
                                            }
                                            $manager_status1 = $row1['dt_manager_status'];


                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td class="user-name"><?php echo $row1['user_name']; ?></td>
                                                <td class="user-role"> <small>
                                                        <?php
                                                        $sql_role21 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$usid1}";
                                                        $res_role21 = mysqli_query($con, $sql_role21);
                                                        while ($row_role21 = mysqli_fetch_assoc($res_role21)) {
                                                            echo $row_role21['type_name'] . ", ";
                                                        }
                                                        ?></small></td>
                                                <td class="task-info"><input type="hidden" name="task_info" value="<?php echo $tasks1; ?>"><?php echo $task_details1;  ?></td>
                                                <td class="task-seen"><?php echo $date1; ?></td>
                                                <td class="task-completed"><?php echo $completed1; ?></td>
                                                <td>
                                                    <div><input type="hidden" name="user" value="<?php echo $usid1; ?>">
                                                        <input type="hidden" name="name_taskid" value="<?php echo $taskId1; ?>">
                                                        <input type="hidden" name="manager_status" value="<?php echo $manager_status1; ?>">
                                                        <button class='btn btn-sm btn-primary btn-viewtask'>view</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p class="text-center mt-4"><a href="<?php echo $url . "/manager/daily-task/completed-tasks.php"; ?>">view all</a></p>
                    <?php
                    } else {
                        echo "<p class='text-center'>No Completed Task</p>";
                    } ?>
                </div>


            </div>

        </div>
        <!-- completed tasks end  -->

    </div>

</div>

<!-- modal assign employee task start -->
<div class="modal fade" id="taskModal">
    <div class="modal-dialog">
        <div class="modal-content card-bg py-4">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Create Task</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form action="form-actions.php" method="post" id="formId">
                <div class="modal-body pb-4">
                    <label for="taskDetails" class="text-secondary">Task Details :</label>
                    <div class="input-group mb-3">
                        <textarea class="form-control" id="text-area" name="task_details" required aria-label="With textarea" placeholder="enter task details"></textarea>
                        <input type="hidden" class="input_date" name="name_date" value="<?php echo $date ?>">
                    </div>

                    <label for="dueDate" class="text-secondary">Due Date :</label>
                    <div class="input-group mb-3">

                        <input type="date" class="form-control" name="due_date">
                    </div>
                    <label for="taskAssign" class="text-secondary">Assign to :</label>
                    <!-- Single select boxes -->
                    <div class="m-b-15">
                        <select class="form-control" name="emp_assign" id="emp-select">
                            <option value='0'>--- select employee ---</option>
                            <?php
                            $sql_emp = "SELECT u_id,user_name,user_photo,user_role FROM users_db WHERE users_db.cus_id = '$cusID' AND user_role !=0 AND user_active=1";
                            $res_emp = mysqli_query($con, $sql_emp);
                            if (mysqli_num_rows($res_emp) > 0) {
                                while ($row_emp = mysqli_fetch_assoc($res_emp)) {
                                    $emp_userId = $row_emp['u_id'];
                                    $user_role = $row_emp['user_role'];
                            ?>
                                    <option value="<?php echo $row_emp['u_id'] ?>">
                                        <?php echo $row_emp['user_name'];
                                        if ($user_role == 1) {
                                            echo "<small class='font-size-8 text-light'>(manager)</small>";
                                        } else {
                                            $sql_type2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$emp_userId}";
                                            $res_type2 = mysqli_query($con, $sql_type2);

                                            if ($res_type2) {
                                                echo " (";
                                                while ($r_type2 = mysqli_fetch_array($res_type2)) {
                                                    echo "<small class='font-size-8 text-light'>" . $r_type2['type_name'] . ", " . "</small>";
                                                }
                                            }
                                            echo ")";
                                        }
                                        ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline-primary" id="save-e-task" name="save_task" value="save">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- remove task modal  -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3">Are you sure?</h4>
                <p class="text-center">You won't be able to revert this!</p>
                <div class="text-center mt-4">
                    <input type="hidden" class="dailytask_id">
                    <button type="button" id="del-task" class="me-2 btn btn-secondary">Yes, delete it!</button>
                    <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- view task modal  -->
<div class="modal fade" id="staticBackdropView" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content py-4 card-bg">

            <div class="modal-body" id="load-modal-data">
                <div class='d-flex justify-content-center'>
                    <div class='spinner-border text-secondary' role='status'><span class='sr-only'>Loading...</span> </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- modal assign employee task start -->
<div class="modal fade" id="taskEditModal">
    <div class="modal-dialog">
        <div class="modal-content card-bg py-4">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Edit Task</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form action="form-actions.php" method="post" id="formId1">
                <div class="modal-body pb-4">
                    <label class="text-secondary">Assigned User:</label>
                    <div class=" mb-3">
                        <h6 class="text-warning" id="etask-user"></h6>
                    </div>
                    <label for="taskDetails" class="text-secondary">Task Details :</label>
                    <div class="input-group mb-3">
                        <textarea class="form-control" id="etask-textarea" name="task_details" required aria-label="With textarea" placeholder="enter task details"></textarea>

                    </div>
                    <input type="hidden" name="taskid" id="etask-id" value="">
                    <label for="dueDate" class="text-secondary">Due Date :</label>
                    <div class="input-group mb-3">

                        <input type="date" class="form-control" id="etask-duedate" name="due_date">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline-primary" id="update-e-task" name="update_d_task" value="update">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(".btn-edittask").click(function(e) {
        e.preventDefault();
        var ddate = $(this).attr("data-date");
        var duser = $(this).attr("data-name");
        var did = $(this).attr("data-id");
        var dtask = $(this).attr("data-task");

        $("#etask-user").text(duser);
        $("#etask-textarea").val(dtask);
        $("#etask-id").val(did);
        $("#etask-duedate").val(ddate);
        $("#taskEditModal").modal("show");

    });
    $("#add-task-btn").click(function(e) {
        e.preventDefault();
        $("#taskModal").modal('show');

    });
    $(".btn-deltask").on('click', function() {
        var taskid = $(this).closest('div').find("input[name='name_taskid']").val();
        $(".dailytask_id").val(taskid);
        $("#staticBackdrop").modal('show');

    });
    $("#del-task").on('click', function() {
        var taskk = $(".dailytask_id").val();
        $.ajax({
            type: "post",
            url: "form-actions.php",
            data: {
                deltask: true,
                taskid: taskk,
            },
            success: function(response) {
                location.reload();
            }
        });
    });
    $(".btn-viewtask").on('click', function() {
        var taskid = $(this).closest('div').find("input[name='name_taskid']").val();
        $("#staticBackdropView").modal('show');
        $.ajax({
            type: "post",
            url: "form-actions.php",
            data: {
                viewdetails: true,
                taskidd: taskid,
            },
            success: function(response) {
                $("#load-modal-data").html(response);

                $(".btn-check1").on('click', function(e) {
                    e.preventDefault();

                    var taskidd = $(this).closest('div').find("input[name='taskId']").val();

                    $.ajax({
                        type: "post",
                        url: "form-actions.php",
                        data: {
                            completeverify: true,
                            taskid: taskidd,
                        },
                        success: function(response) {
                            if (response == 1) {
                                $(".verify").html("<small class='text-success'>verified</small>");

                            } else {
                                alert("something went wrong");
                            }

                        }
                    });
                })

            }
        });


    });
</script>

<?php
include_once('../include/footer.php');
?>