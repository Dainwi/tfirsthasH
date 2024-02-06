<?php
define('TITLE', 'Projects');
define('PAGE', 'ongoing-project');
define('PAGE_T', 'Ongoing Project');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];
?>

<div class="row">
    <?php

    $sql_pending = "SELECT*FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_status=1  AND projects_db.cus_id={$cusID} ORDER BY project_id DESC";
    $res_pending = mysqli_query($con, $sql_pending);

    $rows = mysqli_num_rows($res_pending);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($res_pending)) {

    ?>
            <div class="col-md-4">
                <div class="card shadow rounded card-bg">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <small class="text-warning"><?php echo $row['project_event']; ?></small>
                            <div class="card-toolbar">

                                <a class="text-primary mx-2" href="view-ongoing-project.php?prid=<?php echo $row['project_id']; ?>">
                                    <i class="fas fa-eye font-size-20"></i>
                                </a>
                                <a class="text-danger del-ongoing-pr mx-2" href="<?php echo $row['project_id']; ?>" type="button">
                                    <i class="fas fa-trash-alt font-size-20"></i>
                                </a>

                            </div>
                        </div>

                        <h5><?php echo $row['project_name']; ?></h5>


                        <div class="row">
                            <div class="col-4">
                                <small class="text-info"> Client</small>
                            </div>
                            <div class="col-8">
                                <small>: <?php echo $row['c_name']; ?> </small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <small class="text-info"> Mobile</small>
                            </div>
                            <div class="col-8">
                                <small>: <?php echo $row['c_phone']; ?></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <small class="text-info"> Event Date</small>
                            </div>
                            <div class="col-8">
                                <small>:
                                    <?php
                                    $sdate = date_create($row['project_sdate']);
                                    $sdt = date_format($sdate, "d M Y");
                                    $ldt1 = date_create($row['project_ldate']);
                                    $ldt = date_format($ldt1, "d M Y");
                                    $ldate = $row['project_ldate'];

                                    if ($ldate == "0000-00-00") {
                                        echo $sdt;
                                    } else {

                                        echo $sdt . " " . "-" . " " . $ldt;
                                    }


                                    ?>
                                </small>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="view-ongoing-project.php?prid=<?php echo $row['project_id']; ?>" class="btn btn-sm btn-round px-5 btn-outline-warning d-inline">View</a>
                        </div>


                    </div>
                </div>
            </div>



    <?php
        }
    } else {
        echo "<p class='text-danger text-center mt-4'>No Pending Projects</p>";
    }

    ?>

</div>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdropOngoing" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3">Are you sure?</h4>
                <p class="text-center">You won't be able to revert this!</p>
                <form action="actions/update-project.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="prjid" id="proj-ong" value="">
                        <input type="hidden" name="btn_del_ongoing" value="1">
                        <button type="submit" class="me-2 btn btn-danger">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(".del-ongoing-pr").on('click', function(e) {
        e.preventDefault();
        var ddd = $(this).attr('href');

        $("#proj-ong").val(ddd);

        $("#staticBackdropOngoing").modal('show');
    });
</script>

<?php
include_once('../include/footer.php');
?>