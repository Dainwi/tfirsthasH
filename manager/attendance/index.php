<?php
define('TITLE', 'Attendance');
define('PAGE', 'attendance');
define('PAGE_T', 'Attendance');
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
$today = date("d-m-Y");
$date = date("Y-m-d");
$cusID = $_SESSION['cus_id'];
?>

<div class="card shadow card-bg p-4">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
        <li class="nav-item me-3" role="presentation">
            <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">Today's Attendance</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="ex1-tab-2" data-bs-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Previous Attendance</a>
        </li>

    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content" id="ex1-content">
        <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
            <div class="mt-3 table-responsive">
                <table class="table ">
                    <thead>
                        <tr class="border">
                            <th scope="col" class="text-light font-size-12">#</th>
                            <th scope="col" class="text-light font-size-12">Employee <i class="anticon anticon-caret-down"></i></th>
                            <th scope="col" class="text-light font-size-12">In Time</th>
                            <th scope="col" class="text-light text-center font-size-12">Out Time</th>
                            <th scope="col" class="text-center text-light font-size-12">status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_emp = "SELECT u_id,user_name,user_photo,user_role FROM users_db WHERE cus_id={$cusID} AND user_role !=0 AND user_active=1 ORDER BY payment_option ASC";
                        $res_emp = mysqli_query($con, $sql_emp);
                        if (mysqli_num_rows($res_emp) > 0) {
                            $i = 1;
                            while ($row_emp = mysqli_fetch_assoc($res_emp)) {
                                $emp_userId = $row_emp['u_id'];
                                $user_role = $row_emp['user_role'];
                        ?>
                                <tr class="border">
                                    <td class="text-light"><?php echo $i; ?></td>

                                    <td class="emp-name">
                                        <input type="hidden" name="emp_userid" value="<?php echo $emp_userId ?>">
                                        <a type="button" class="text-light btn-view">
                                            <?php echo $row_emp['user_name'];
                                            if ($user_role == 1) {
                                                echo "<small class='font-size-10 text-light'>(manager)</small>";
                                            } else {
                                                $sql_type2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$emp_userId} AND users_db.user_active=1";
                                                $res_type2 = mysqli_query($con, $sql_type2);

                                                if ($res_type2) {
                                                    echo "<small class='font-size-10 text-light'> (";
                                                    while ($r_type2 = mysqli_fetch_array($res_type2)) {
                                                        echo $r_type2['type_name'] . ", ";
                                                    }
                                                }
                                                echo ")</small>";
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <td>

                                        <?php
                                        $sql_a = "SELECT * FROM attendance_db WHERE a_date='{$date}' AND e_id={$emp_userId}";
                                        $res_a = mysqli_query($con, $sql_a);
                                        if (mysqli_num_rows($res_a) > 0) {
                                            while ($row_a = mysqli_fetch_assoc($res_a)) {
                                                $aid = $row_a['a_id'];
                                                $astatus = $row_a['a_status'];
                                                $atime = $row_a['a_time'];
                                                $otime = $row_a['out_time'];
                                            }
                                            if ($astatus == 1) {
                                                $action = "<span class='text-success'>Present</span> <button class='ms-2 btn-sm btn btn-icon btn-secondary btn-rounded btn-tone btn-aedit'><i class='fas fa-pencil-alt'></i></button>";
                                            } else {
                                                $action = "<span class='text-danger'>On Leave</span> <button class='ms-2 btn-sm btn btn-icon btn-secondary btn-rounded btn-tone btn-aedit'><i class='fas fa-pencil-alt'></i></button>";
                                            }

                                            if ($atime == 0) {
                                                $intime = "";
                                            } else {
                                                $intime = "<span class='text-light'>{$atime}</span>";
                                            }

                                            if ($astatus == 1 && $otime == 0) {
                                                $outtime = "<button class='ms-2 btn-sm btn btn-secondary btn-tone add-out-time'>add</button>";
                                            } elseif ($astatus == 0) {
                                                $outtime = "";
                                            } else {
                                                $outtime = "<span class='text-light'>{$otime}</span><button class='ms-2 btn-sm btn btn-icon btn-secondary btn-rounded btn-tone add-out-time'><i class='fas fa-pencil-alt'></i></button>";
                                            }
                                        } else {

                                            $intime = "";
                                            $outtime = "";

                                            $action = "<button class='btn-sm btn btn-success btn-tone btn-present'>Present</button>
                                        <button class='btn-sm btn btn-danger btn-tone btn-absent'>on leave</button>";
                                        }
                                        echo $intime;
                                        ?>

                                    </td>
                                    <td class="text-center"><input type="hidden" name="attend_id1" value="<?php echo $aid; ?>"><?php echo $outtime; ?></td>
                                    <td class="text-center">
                                        <input type="hidden" name="emp_id" value="<?php echo $emp_userId ?>">
                                        <input type="hidden" name="attend_id" value="<?php echo $aid; ?>">
                                        <?php echo $action; ?>

                                    </td>
                                </tr>

                        <?php
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
            <?php
            $sql = "SELECT * FROM users_db WHERE cus_id={$cusID} AND (user_role = 1 OR user_role =2) AND user_active=1 ORDER BY payment_option ASC";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                $eno = 1;
            ?>

                <div class="container mt-3">
                    <div class="card border border-dark">

                        <div class=" table-responsive">
                            <table class="table ">
                                <thead>
                                    <tr class="border-bottom">
                                        <th scope="col" class="text-light font-size-12">#</th>
                                        <th scope="col" class="text-light font-size-12">profile</th>
                                        <th scope="col" class="text-light font-size-12">Employee <i class="anticon anticon-caret-down"></i></th>
                                        <th scope="col" class="text-light font-size-12">phone</th>
                                        <th scope="col" class="text-center text-light font-size-12">Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="row">
                                        <!-- Card View -->
                                        <?php
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $user_id = $row['u_id'];

                                            if ($row['user_role'] == 1) {
                                                $etype = "<span class='small text-light'>manager</span>";
                                            } else {
                                                $sql_type = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$user_id} AND users_db.user_active=1;";
                                                $res_type = mysqli_query($con, $sql_type);

                                                if ($res_type) {
                                                    while ($r_type = mysqli_fetch_assoc($res_type)) {
                                                        $etype = "<span class='small text-light'>" . $r_type['type_name'] . "," . "</span> ";
                                                    }
                                                }
                                            }

                                        ?>


                                            <tr class="border">
                                                <td><?php echo $eno; ?></td>
                                                <td>
                                                    <div class="avatar avatar-image" style="height: 50px; width: 50px;">
                                                        <img src="<?php
                                                                    $pic = $row['user_photo'];
                                                                    if ($pic == 0) {
                                                                        echo $url . "/assets/images/avatars/profile-image.png";
                                                                    } else {
                                                                        echo $url . "/assets/images/profile/" . $pic;
                                                                    }
                                                                    ?>" alt="" class="img-fluid w-75">
                                                    </div>
                                                </td>

                                                <td class="text-uppercase"><?php echo $row['user_name'] . "<small class='text-light'>(" . $etype . ")<small>"; ?></td>
                                                <td><?php echo $row['user_phone']; ?></td>
                                                <td class="text-center"><a href="<?php echo $url . "/manager/attendance/view-attendance.php?user=" . $user_id; ?>" class="btn btn-secondary btn-sm ">View</a></td>

                                            </tr>


                                        <?php $eno++;
                                        } ?>


                                    </div>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            <?php } ?>
        </div>

    </div>
    <!-- Tabs content -->

</div>


<!-- modal attendance present  -->
<div class="modal fade" id="modalAttendance" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body shadow">

                <form action="a-actions.php" method="post" id="form-p">
                    <div class="text-center my-4">
                        <input type="hidden" name="empid" id="emp-id" value="">
                        <input type="hidden" name="adate" value="<?php echo $date ?>">
                        <input type="hidden" name="astatus" id="a-status" value="1">
                        <div class="mb-5" id="present-part">

                            <h3 class="text-center mb-4" id="emp_name"></h3>
                            <p class="text-center text-info">Please Enter Employee 'In Time'</p>
                            <div class="px-4 d-flex justify-content-center align-items-center">
                                <input type="time" class="form-control col-6" name="in_time" required placeholder="Enter time">
                            </div>
                        </div>

                        <input type="submit" id="btn-p-submit" class="me-2 btn btn-success" value="save it." name="present_a">
                        <button type="button" class="ms-2 btn btn-secondary btn-close-modal" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- modal attendance absent  -->
<div class="modal fade" id="modalAttendanceAbsent" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg shadow">

            <div class="modal-body my-4">
                <div>
                    <h1 class="text-center text-info display-1"><i class="fas fa-exclamation-circle"></i></h1>
                    <h4 class="text-center mt-3">Are you sure?</h4>
                    <p class="text-center">You won't be able to revert this!</p>
                </div>
                <form action="a-actions.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="empid" id="emp-id1" value="">
                        <input type="hidden" name="adate" value="<?php echo $date ?>">
                        <input type="hidden" name="astatus" id="a-status" value="0">

                        <input type="submit" class="me-2 btn btn-secondary" value="Yes! save it." name="absent_a">
                        <button type="button" class="ms-2 btn btn-light btn-close-modal" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- modal attendance edit  -->
<div class="modal fade" id="modalAttendanceEdit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg ">

            <div class="modal-body my-4">
                <h3 class="text-center mb-4" id="emp_name1"></h3>
                <form action="a-actions.php" method="post" id="form-e">
                    <div class="text-center mt-4">
                        <input type="hidden" name="atdid" id="atd-id" value="">
                        <div class="mb-3 px-4 ">
                            <small for="statusType" class="text-light">Select Status</small>
                            <div class="px-4 d-flex justify-content-center align-items-center">
                                <select id="statusType" class=" form-control col-8 " style="width:100%" name="statusType">
                                    <option value="2" selected>Choose...</option>
                                    <option value="1">Present</option>
                                    <option value="0">On Leave</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5" id="edit-body">


                        </div>

                        <input type="submit" id="btn-edit-submit" disabled class="me-2 btn btn-secondary" value="save it." name="edit_a">
                        <button type="button" class="ms-2 btn btn-light btn-close-edit">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- modal attendance out time  -->
<div class="modal fade" id="modalAttendanceOutTime" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body my-4">
                <h3 class="text-center mb-4" id="emp_name2"></h3>
                <form action="a-actions.php" method="post" id="form-tout">
                    <div class="text-center mt-4">
                        <input type="hidden" name="atdid" id="atd-id1" value="">
                        <p class="text-center">Please Enter Employee 'Out Time'</p>
                        <div class="px-4 py-4 d-flex justify-content-center align-items-center">
                            <input type="time" class="form-control col-6" name="out_time" required placeholder="Enter time">
                        </div>

                        <input type="submit" id="btn-out-submit" class="me-2 btn btn-secondary" value="save it." name="timeout_a">
                        <button type="button" class="ms-2 btn btn-light btn-close-out">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdropE" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="staticBackdropLabel">Employee Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
    $(document).ready(function() {
        $("#form-e").trigger("reset");
        $("#edit-body").html("");
        $('#btn-edit-submit').attr('disabled', true);
        $(".btn-present").click(function(e) {
            e.preventDefault();
            var empid = $(this).closest('td').find('input[name="emp_id"]').val();
            var empname = $(this).closest('tr').find('td[class="emp-name"]').html();
            $("#emp-id").val(empid);
            $('#emp_name').html(empname);
            $("#modalAttendance").modal('show');

        });
        $(".btn-absent").click(function(e) {
            e.preventDefault();
            var empid = $(this).closest('td').find('input[name="emp_id"]').val();
            $("#emp-id1").val(empid);
            $("#modalAttendanceAbsent").modal('show');

        });

        $(".btn-aedit").click(function(e) {
            e.preventDefault();
            var attendid = $(this).closest('td').find('input[name="attend_id"]').val();
            var empname1 = $(this).closest('tr').find('td[class="emp-name"]').html();
            $('#emp_name1').html(empname1);
            $("#atd-id").val(attendid);
            $("#modalAttendanceEdit").modal('show');

            $(".btn-close-edit").click(function(e) {
                e.preventDefault();
                $("#modalAttendanceEdit").modal('hide');
                $("#form-e").trigger("reset");
                $("#edit-body").html("");
                $('#btn-edit-submit').attr('disabled', true);
            });

        });

        $(".add-out-time").click(function(e) {
            e.preventDefault();
            var attendid1 = $(this).closest('td').find('input[name="attend_id1"]').val();
            var employname1 = $(this).closest('tr').find('td[class="emp-name"]').html();
            $('#emp_name2').html(employname1);
            $("#atd-id1").val(attendid1);
            $("#modalAttendanceOutTime").modal('show');

            $(".btn-close-out").click(function(e) {
                e.preventDefault();
                $("#modalAttendanceOutTime").modal('hide');
                $("#form-tout").trigger("reset");

            });

        });

        $('#statusType').on('change', function() {
            var statusOption = $('#statusType option:selected').val();

            if (statusOption == 1) {
                $("#btn-edit-submit").removeAttr('disabled');
                $("#edit-body").html("<small class='text-center'>*Please Enter Employee 'In Time'</small>\
                            <div class='px-4 d-flex justify-content-center align-items-center'>\
                                <input type='time' class='form-control col-6' name='in_time' required placeholder='Enter time'>\
                            </div>");

            } else if (statusOption == 0) {
                $("#btn-edit-submit").removeAttr('disabled');
                $("#edit-body").html("");

            } else {
                $('#btn-edit-submit').attr('disabled', true);
                $("#edit-body").html("");
            }
        });

        $(".btn-view").click(function(e) {
            e.preventDefault();
            var employid = $(this).closest('td').find('input[name="emp_userid"]').val();
            $.ajax({
                type: "post",
                url: "a-actions.php",
                data: {
                    loadData: true,
                    userid: employid,
                },
                success: function(response) {
                    $("#staticBackdropE").modal("show");
                    $("#modal-content").html(response);

                }
            });

        });

    });
</script>


<?php
include_once('../include/footer.php');
?>