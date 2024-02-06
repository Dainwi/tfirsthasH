<?php
define('TITLE', 'Projects');
define('PAGE', 'project-tasks');
define('PAGE_T', 'Project Tasks');
include_once('../include/header.php');

$assigned='';
$limit = 20;
if (isset($_GET['pg'])) {
    $page = $_GET['pg'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;

include_once('../include/sidebar.php');
include_once('../include/top-nav.php');



$cusID=$_SESSION['user_id'];

// echo "hello". $cusID;

$response = "";





$sql1 = "SELECT * FROM deliverables_assign LEFT JOIN deliverables_db ON deliverables_db.d_id=deliverables_assign.da_d_id LEFT JOIN projects_db ON deliverables_assign.da_pr_id = projects_db.project_id WHERE da_status=0 AND project_status = 1 AND deliverables_db.cus_id={$cusID} ORDER BY da_due_date ASC LIMIT {$offset},{$limit}";
$res1 = mysqli_query($con, $sql1);
if (mysqli_num_rows($res1) > 0) {
    echo "<table class='table table-bordered' style='background-color:#181821;'>
    <thead>
    <th class='text-warning'>Client</th>
        <th class='text-warning'>Tasks to be done</th>
        <th class='text-warning'>assign to</th>
        <th class='text-warning'>Due Date</th>
        <th class='text-warning'>Task Status</th>
        <th class='text-warning'>Status</th>
        <th class='text-warning'>Final Link</th>
        <th class='text-warning'></th>
    </thead>
    <tbody>";
    while ($row1 = mysqli_fetch_assoc($res1)) {
        $daid = $row1['da_id'];
        $sastatus = $row1['da_status'];
        $ustatus = $row1['da_completed'];

        $prid = $row1['da_pr_id'];
        $sqlcl = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_id={$prid} AND projects_db.cus_id=$cusID";
        $rescl = mysqli_query($con, $sqlcl);
        if (mysqli_num_rows($rescl) > 0) {
            while ($rowcl = mysqli_fetch_assoc($rescl)) {
                $clientname = $rowcl['c_name'];
                $pstatus = $rowcl['project_status'];
            }
        }

        $linkk = $row1['da_link'];

        if ($linkk == "" || $linkk == null) {
            $urll = "<small>n/a</small>";
        } else {
            $urll = "<a target='_blank' href='{$linkk}' class='text-primary'><i class='fas fa-link'></i></a>";
        }

        if ($ustatus == 0) {
            $prog = "Not Completed";
        } elseif ($ustatus == 1) {
            $prog = "Completed";
        } elseif ($ustatus == 2) {
            $prog = "In-progress";
        } elseif ($ustatus == 3) {
            $prog = "First Cut Shared with Client";
        } elseif ($ustatus == 4) {
            $prog = "Change in Process";
        }

        $ddate = $row1['da_due_date'];
        if ($ddate == "0000-00-00") {
            $date = "";
        } else {
            $date = $ddate;
        }

        if ($sastatus == 0) {
            $ss = "<p class='text-danger d-inline'>pending</p><input type='hidden' name='daid' value='{$daid}'> <button class='btn btn-outline-success btn-sm py-1 btn-setc'>set complete</button>";
        } else {
            $ss = "<p class='text-success'>completed</p>";
        }
        $response .= "<tr>";
        $response .= "<td class='text-center'><a href='{$url}/admin/projects/view-ongoing-project.php?prid={$prid}'>{$clientname}</a></td>";
        $response .= "<td>{$row1["da_item"]} ({$row1["d_layout"]})</td>";

        //assigned post production
        if ($row1['da_assign_to'] == 0) {
            $assigned = "<button class='btn btn-sm btn-outline-primary py-1 assign-da-user'><i class='fas fa-plus'></i></button>";
        } else {
            $sqln = "SELECT * FROM deliverables_assign WHERE da_assign_to={$row1['da_assign_to']} AND da_status=0";
            $resn = mysqli_query($con, $sqln);
            $numr = mysqli_num_rows($resn);

            if ($numr > 1) {
                $task = "tasks - p";
            } else {
                $task = "task - p";
            }

            $sqlu = "SELECT user_name FROM users_db WHERE u_id={$row1['da_assign_to']} AND cus_id={$cusID}";
            $resu = mysqli_query($con, $sqlu);
            if (mysqli_num_rows($resu) > 0) {
                
                while ($rowu = mysqli_fetch_assoc($resu)) {
                    $usernamee = $rowu['user_name'];
                }
                $assigned = "{$usernamee} ({$numr} {$task})<button class='ms-2 px-2 btn btn-sm btn-outline-primary py-1 assign-da-user'><i class='fas fa-pencil-alt'></i></button>";
            }
        }
        $response .= "<td><input type='hidden' name='delid' value='{$daid}'>{$assigned}</td>";
        $response .= "<td><input type='hidden' name='daitemid' value='{$daid}'><input type='date' class='form-control deliverable-date text-light' style='width:150px; background:#1c1c1c;' value='{$date}'></td>";
        $response .= "<td><small>{$prog}</small></td>";
        $response .= "<td>{$ss}</td>";

        $response .= "<td class='text-center'>{$urll}</td>";
        $response .= "<td><input type='hidden' name='daidc' value='{$daid}'><button class='btn btn-outline-danger btn-sm py-1 btn-delete-da'><i class='fas fa-trash'></i></button></td>";

        $response .= "</tr>";
    }




    $response .= "</tbody>
</table>";
}




echo $response;

?>

<div class="card-footer">
    <nav aria-label="Page navigation example">
        <?php

        $sql_pg = "SELECT * FROM deliverables_assign WHERE da_status=0";
        $res_pg = mysqli_query($con, $sql_pg);
        if (mysqli_num_rows($res_pg) > 0) {

            $total_records = mysqli_num_rows($res_pg);

            $total_page = ceil($total_records / $limit);
            echo '<ul class="pagination justify-content-center">';
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='{$url}/admin/project-tasks/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
            } else {
                echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
            }
            for ($i = 1; $i <= $total_page; $i++) {
                if ($i == $page) {
                    $active = "active";
                } else {
                    $active = "";
                }
                echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/project-tasks/index.php?pg=' . $i . '">' . $i . '</a></li>';
            }
            if ($total_page > $page) {
                echo "<li class='page-item'><a class='page-link' href='{$url}/admin/project-tasks/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
            } else {
                echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
            }

            echo '</ul>';
        }


        ?>



    </nav>
</div>


<!-- Modal Assign post production start-->
<div class="modal fade" id="assign-postproduction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Assign Employee</h5>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <!--<span aria-hidden="true">&times;</span>-->
                </button>
            </div>
            <form action="ajax/ongoing-project-ajax.php" method="post" id="form-app">
                <div class="modal-body">
                    <input type="hidden" name="deliverablea_name" id="deliverablea_id" value="">

                    <div class="modal-pp-assign" id="modal-app">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-outline-gray" data-dismiss="modal">Close</button>-->
                    <input type="button" id="deliverable-assign-btn" class="btn btn-outline-warning btn-round px-4"
                        value="Assign" name="assign_manager">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal post production end-->

<!-- modal delete start -->
<div class="modal fade" id="staticDeleteda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                <p class="text-center text-info">You won't be able to revert this!</p>

                <div class="text-center mt-4">
                    <input type="hidden" id="daid-id" value="">
                    <!-- <input type="hidden" name="btn_del_ongoing" value="1"> -->
                    <button type="button" class="me-2 btn btn-danger btn-del-da">Yes, delete it!</button>
                    <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- modal delete end  -->

<script>
$(".btn-del-da").click(function(e) {
    e.preventDefault();
    var daidd = $('#daid-id').val();
    var urll = "<?php echo $url . '/admin/projects/ajax/ongoing-project-ajax.php ' ?>";
    $.ajax({
        type: "post",
        url: urll,
        data: {
            deletedassign: true,
            daidd: daidd,
        },
        success: function(response) {
            if (response == 0) {
                alert('something went wrong');
                $('#staticDeleteda').modal('hide');
            } else {
                $('#staticDeleteda').modal('hide');
                location.reload();
            }
        }
    });


});

$('.deliverable-date').change(function() {
    var daid = $(this).closest('td').find('input[name="daitemid"]').val();
    var ddate = $(this).val();
    var urll = "<?php echo $url . '/admin/projects/ajax/ongoing-project-ajax.php ' ?>";
    $.ajax({
        type: "post",
        url: urll,
        data: {
            dadate: true,
            daid: daid,
            ddate: ddate,
        },
        success: function(response) {
            if (response == 1) {
                location.reload();

            } else {
                alert("something went wrong");
            }
        }
    });

});

$(".btn-setc").click(function(e) {
    e.preventDefault();
    var daid = $(this).closest('td').find('input[name="daid"]').val();
    var urll = "<?php echo $url . '/admin/projects/ajax/ongoing-project-ajax.php ' ?>";

    $.ajax({
        type: "post",
        url: urll,
        data: {
            statusda: true,
            daid: daid,
        },
        success: function(response) {
            if (response == 0) {
                alert('something went wrong');
            } else {
                location.reload();

            }

        }
    });

});

$(".assign-da-user").click(function(e) {
    e.preventDefault();
    var assigndid = $(this).closest('td').find('input[name="delid"]').val();
    // alert(assigndid);
    var urll = "<?php echo $url . '/admin/projects/ajax/ongoing-project-ajax.php ' ?>";
    $("#deliverablea_id").val(assigndid);
    $('#assign-postproduction').modal('show');

    $.ajax({
        type: "post",
        url: urll,
        data: {
            assigndeliverablep: true,
            assigndid: assigndid,
        },
        success: function(response) {
            if (response == 0) {
                alert('something went wrong');
            } else {
                $("#modal-app").html(response);
            }
        }
    });

});


//assign button
$("#deliverable-assign-btn").click(function(e) {
    e.preventDefault();
    var selectUserVal = $("#select-deliverables option:selected").val();
    var delassid = $("#deliverablea_id").val();
    var urll = "<?php echo $url . '/admin/projects/ajax/ongoing-project-ajax.php ' ?>";
    if (selectUserVal == 0) {
        alert('please choose employee');
    } else {

        $.ajax({
            type: "post",
            url: urll,
            data: {
                assignemployeedel: true,
                employeeid: selectUserVal,
                delassid: delassid,
            },
            success: function(response) {
                if (response == 0) {
                    alert('something went wrong');
                } else {
                    $('#assign-postproduction').modal('hide');
                    location.reload();
                }
            }
        });
    }

});

$(".btn-delete-da").click(function(e) {
    e.preventDefault();
    var daidc = $(this).closest('td').find('input[name="daidc"]').val();
    $('#daid-id').val(daidc);
    $('#staticDeleteda').modal('show');
});
</script>

<?php

include_once('../include/footer.php');
?>