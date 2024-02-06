<?php
define('TITLE', 'My Projects');
define('PAGE', 'projects');
define('PAGE_T', 'Ongoing Projects');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
?>

<div class="container-fluid">
    <div class="row">
        <?php
        $sql_pending = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE project_status=1 AND assign_manager_db.am_user_id ={$manager_id} ORDER BY project_id DESC";
        $res_pending = mysqli_query($con, $sql_pending);

        $rows = mysqli_num_rows($res_pending);

        if ($rows > 0) {
            while ($row = mysqli_fetch_assoc($res_pending)) {

        ?>
                <div class="col-md-4">
                    <div class="card card-bg rounded">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between align-items-center m-b-15">
                                <p class="badge badge-pill"><?php echo $row['project_event']; ?></p>

                                <a class="text-info" href="view-project.php?prid=<?php echo $row['project_id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </a>

                            </div>

                            <h4 class="text-warning"><?php echo $row['c_name']; ?></h4>
                            <div class="row">
                                <div class="col-5">
                                    <span class="font-weight-semibold"> Mobile :</span>
                                </div>
                                <div class="col-7">
                                    <?php echo $row['c_phone']; ?>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-5">
                                    <span class="font-weight-semibold"> Event Date:</span>
                                </div>
                                <div class="col-7">
                                    <small>
                                        <?php
                                        $sdate = date("d M Y", strtotime($row['project_sdate']));
                                        $ldate = $row['project_ldate'];

                                        if ($ldate == "0000-00-00") {
                                            echo $sdate;
                                        } else {
                                            $ladt = date("d M Y", strtotime($ldate));
                                            echo $sdate . " " . "-" . " " . $ladt;
                                        }


                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mt-3 d-flex">
                                <a href="view-project.php?prid=<?php echo $row['project_id']; ?>" class="mx-auto btn-round px-5 btn btn-secondary btn-tone d-inline">view</a>

                            </div>


                        </div>
                    </div>
                </div>



        <?php
            }
        } else {
            echo "<p class='text-danger text-center mt-4'>no project found</p>";
        }

        ?>

    </div>
</div>




<?php
include_once('../include/footer.php');
?>