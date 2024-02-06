<?php
define('TITLE', 'Daily Tasks');
define('PAGE', 'daily-tasks');
define('PAGE_T', 'Pending Tasks');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$today = date("d-m-Y");
$date = date("Y-m-d");

$cusID = $_SESSION['cus_id'];

$limit = 12;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
?>

<div class="container-fluid">
    <div class="card shadow card-bg rounded">
        <div class="card-body">

            <?php
            $sql1 = "SELECT *,users_db.user_name,users_db.u_id FROM  daily_tasks LEFT JOIN users_db ON daily_tasks.dt_user_id = users_db.u_id WHERE dt_manager_status = 0 AND dt_date !='{$date}' AND users_db.user_active=1 AND users_db.cus_id={$cusID} ORDER BY dt_id DESC LIMIT {$offset},{$limit}";
            $res1 = mysqli_query($con, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                $i = 1;
            ?>

                <div class="m-t-30">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>type</th>
                                    <th>task details</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row1 = mysqli_fetch_assoc($res1)) {
                                    $usid1 = $row1['u_id'];
                                    $uname1 = $row1['user_name'];
                                    $userrole1 = $row1['user_role'];
                                    $taskId1 = $row1['dt_id'];
                                    $tasks1 = $row1['dt_details'];
                                    $task_details1 = substr_replace($tasks1, "...", 20);
                                    $date1 = date_create($row1['dt_date']);
                                    $date1  = date_format($date1, "d M Y");
                                    $duedate1 = $row1['dt_due_date'];
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
                                        <td class="user-name text-info"><?php echo $row1['user_name']; ?></td>
                                        <td class="user-role"> <small>
                                                <?php
                                                $sql_role21 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$usid1}";
                                                $res_role21 = mysqli_query($con, $sql_role21);
                                                while ($row_role21 = mysqli_fetch_assoc($res_role21)) {
                                                    echo $row_role21['type_name'] . ", ";
                                                }
                                                ?></small></td>
                                        <td class="task-info text-light"><input type="hidden" name="task_info" value="<?php echo $tasks1; ?>"><?php echo $task_details1;  ?></td>
                                        <td class="task-seen text-light"><?php echo $date1; ?></td>
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
            <?php
            } else {
                echo "<p class='text-center'>No Pending Task</p>";
            } ?>
        </div>


    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            $sql_pg = "SELECT * FROM daily_tasks WHERE dt_manager_status = 0 AND dt_date !='{$date}'";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/manager/daily-task/pending-tasks.php?page=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/manager/daily-task/pending-tasks.php?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/manager/daily-tasks/pending-tasks.php?page=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>
</div>

<!-- view task modal  -->
<div class="modal fade" id="staticBackdropView" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

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

                $(".btn-check").on('click', function(e) {
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
                                location.reload();
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