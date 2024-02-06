<?php
define('TITLE', 'Manager Dashboard');
define('PAGE', 'mdaily-tasks');
define('PAGE_T', 'Daily Tasks');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$date = date("Y-m-d");
?>


<div class="container-fluid">
    <div class="table-responsive">
        <table class='table table-sm table-bordered card-bg'>
            <thead>
                <th class='text-warning text-center'>Task</th>
                <th class='text-warning text-center'>Task Date</th>
                <th class='text-warning text-center'>Due Date</th>
                <th class='text-warning text-center'>Status</th>
                <th class='text-warning text-center'></th>
            </thead>
            <tbody>
                <?php
                $sqlTask = "SELECT * FROM daily_tasks WHERE dt_user_id = {$manager_id} AND (dt_date = '{$date}' OR dt_manager_status=0) ORDER BY dt_due_date ASC";
                $resTask = mysqli_query($con, $sqlTask);
                if (mysqli_num_rows($resTask) > 0) {
                    while ($rowTasks = mysqli_fetch_assoc($resTask)) {

                        $verify = $rowTasks['dt_manager_status'];
                        $status = $rowTasks['dt_completed'];

                        if ($verify == 0) {
                            $verification = "<span class='text-warning'>Pending</span>";
                        }
                        // Creates DateTime objects

                        $date1 = new DateTime($date);
                        $date2 = new DateTime($rowTasks['dt_due_date']);

                        // $date1 = new DateTime('2010-07-06');
                        // $date2 = new DateTime('2010-07-09');
                        $days  = $date1->diff($date2)->format("%r%a");



                        if ($days < 0) {
                            $day = "<span class='text-danger'>Due Date Crossed</span>";
                        } elseif ($days == 0) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } elseif ($days == 1) {
                            $day = "<span class='text-danger'>1 day left</span>";
                        } else {
                            $day = $days . " days left";
                        }

                ?>
                        <tr>
                            <td class="text-center text-white"><?php echo substr($rowTasks['dt_details'], 0, 25) . "....."; ?></td>
                            <td class="text-center text-white"><?php echo date("jS M-y", strtotime($rowTasks['dt_date'])); ?></td>
                            <td class="text-center text-white"><?php echo date("jS M-y", strtotime($rowTasks['dt_due_date'])); ?></td>
                            <td class="text-center text-danger"><?php if ($status == 0) {
                                                                    echo $day;
                                                                } else {
                                                                    echo "<span class='text-success'>completed</span>";
                                                                } ?></td>
                            <td class="text-center text-white"> <input type="hidden" name="task_id" value="<?php echo $rowTasks['dt_id']; ?>">
                                <button class="btn mt-2 px-4 py-2 btn-outline-secondary btn-vieww">view</button>
                            </td>
                        </tr>

                <?php
                    }
                } else {
                    echo "<tr><td class='text-center' colspan='5'></td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" id="modalTaskView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <div class="container">
                    <h5 class="modal-title w-100 d-block font-weight-bold text-center" id="taskdetails"></h5>

                </div>

                <button class="btn btn-sm btn-close modal-close btn-secondary">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3" id="modal-task">

            </div>
            <div class="modal-footer d-flex justify-content-center">

                <button type="button" class="btn btn-secondary modal-close">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".btn-vieww").on('click', function(e) {
        e.preventDefault();
        var taskid = $(this).closest("td").find("input[name='task_id']").val();
        $("#modalTaskView").modal("show");

        $.ajax({
            type: "post",
            url: "task-ajax.php",
            data: {
                tasks: true,
                taskId: taskid,
            },

            success: function(response) {
                $("#modal-task").html(response);
            }
        });

        $(".modal-close").on('click', function(e) {
            e.preventDefault();
            $("#modalTaskView").modal("hide");

        });

    });
</script>

<?php
include_once('../include/footer.php');
?>