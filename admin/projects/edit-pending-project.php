<?php
define('TITLE', 'Projects');
define('PAGE', 'pending-project');
define('PAGE_T', 'Edit Project');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$prid = $_GET['pid'];

$sql = "SELECT * FROM projects_db WHERE project_id={$prid}";
$res = mysqli_query($con, $sql);
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $pName = $row['project_name'];
        $clId = $row['project_client'];
        $pEvent = $row['project_event'];
    }
}

// $sql2 = "SELECT * FROM events_db WHERE event_pr_id={$prid}";
// $res2 = mysqli_query($con, $sql2);
// if ($res2) {
//     while ($row = mysqli_fetch_array($res2)) {
//         $date[] = $row['event_date'];
//     }

//     $ldate = max($date);
//     $sdate = min($date);


// }

?>

<div class="container mt-3">
    <div class="card shadow card-bg">
        <div class="card-body">
            <h5 class="card-title">Project Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <small for="formGroupExampleInput">Project Name</small>
                        <input type="text" class="form-control" id="pr_name" value="<?php echo $pName; ?>" id="formGroupExampleInput" placeholder="Project Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group invisible">
                        <small for="formGroupExampleInput">Event</small>
                        <input type="text" readonly class="form-control" id="formGroupExampleInput" value="<?php echo $pEvent; ?>">
                    </div>
                </div>


                <div class="col-md-6">
                    <input type="hidden" name="prid" value="<?php echo $prid ?>">
                    <button class="btn btn-sm btn-outline-warning mt-2 btn-round px-5" id="update-project">update</button>
                </div>

            </div>

        </div>
    </div>

    <div class="card shadow card-bg">
        <div class="card-body">
            <h5 class="card-title">Events Information</h5>

            <?php
            $sql_load = "SELECT * FROM events_db WHERE event_pr_id =$prid";
            $res_load = mysqli_query($con, $sql_load);
            if (mysqli_num_rows($res_load) > 0) {
                $i = 1;
            ?>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Event name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Event Venue</th>
                                <th scope="col">delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($load_row = mysqli_fetch_assoc($res_load)) {
                                $dte = date_create($load_row['event_date']);
                                $datee = date_format($dte, "d M Y");

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $load_row['event_name']; ?></td>
                                    <td><?php echo $datee; ?></td>
                                    <td> <input type="hidden" name="event_id" value="<?php echo $load_row['event_id']; ?>">
                                        <?php
                                        if ($load_row['event_venue'] == "") {
                                            echo "<button class='btn btn-outline-secondary py-1 btn-edit-venue'><i class='fas fa-pencil-alt'></i></button>";
                                        } else {
                                            echo $load_row['event_venue'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="event_id" value="<?php echo $load_row['event_id']; ?>">
                                        <button type="button" class="btn btn-outline-danger btn-sm py-2 d-event">delete</button>
                                    </td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>


            <div class="d-flex">
                <button class="btn btn-sm btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus mr-1"></i> add event</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form action="actions/update-project.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="c_id" value="<?php echo $clId ?>">
                    <input type="hidden" name="c_project" value="<?php echo $prid ?>">
                    <div class="form-group mb-2">
                        <input type="text" placeholder="event name" class="form-control" name="e_name" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="date" placeholder="event date" class="form-control" name="e_date" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" placeholder="event venue" class="form-control" name="e_venue">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="add_event" value="add event" class="btn btn-warning btn-firsthash">
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="editvenueModal">
    <div class="modal-dialog">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Event Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form action="actions/update-project.php" method="post">
                <div class="modal-body">

                    <input type="hidden" name="c_eventid" id="eventd" value="">

                    <div class="form-group mb-2">
                        <input type="text" placeholder="event venue" class="form-control" name="e_venue" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="edit_eventvenue" value="add event" class="btn btn-warning btn-firsthash">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.10/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {

        $(".btn-edit-venue").click(function(e) {
            e.preventDefault();
            var evid = $(this).closest('td').find("input[name='event_id']").val();
            $("#eventd").val(evid);
            $("#editvenueModal").modal('show');

        });

        $("#update-project").click(function(e) {
            e.preventDefault();
            var prid = $(this).closest('div').find("input[name='prid']").val();
            var prname = $("#pr_name").val();


            $(this).addClass('is-loading');

            $.ajax({
                type: "post",
                url: "actions/update-project.php",
                data: {
                    updateP: true,
                    prid: prid,
                    prname: prname,

                },
                success: function(response) {
                    if (response == 1) {
                        Swal.fire({
                            icon: 'success',
                            text: 'Update Successful',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false

                        });
                    } else if (response == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'something went wrong',

                        });
                    }
                    $("#update-project").removeClass('is-loading');
                }
            });

        });




        $(".d-event").on('click', function(e) {
            e.preventDefault();
            var evntid = $(this).closest('td').find("input[name='event_id']").val();
            var prd = "<?php echo $prid ?>";

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "actions/update-project.php",
                        data: {
                            evntdel: true,
                            evntId: evntid,
                            prid: prd,
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'Your file has been deleted.',
                                    timer: 1500,
                                    showCancelButton: false,
                                    showConfirmButton: false

                                });
                                location.reload();
                            } else {
                                alert("something went wrong");
                            }

                        }
                    });

                }
            })
        })
    });
</script>


<?php
include_once('../include/footer.php');
?>