<?php
define('TITLE', 'Projects');
define('PAGE', 'projects');
define('PAGE_T', 'All Projects');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$limit = 15;
if (isset($_GET['pg'])) {
    $page = $_GET['pg'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;

$cusID = $_SESSION['user_id'];

// echo $cusID;

?>
<div class="card card-bg rounded">
    <div class="card-body">

        <?php
        $cusID_escaped = mysqli_real_escape_string($con, $cusID);
$sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id = projects_db.project_client WHERE projects_db.cus_id = '$cusID_escaped' ORDER BY project_id DESC LIMIT {$offset}, {$limit}";
$res = mysqli_query($con, $sql);


        if (mysqli_num_rows($res) > 0) {
        ?>
            <p class="text-end"><button class="btn btn-outline-danger btn-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#staticBackdropAll">Delete All</button></p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-warning">#</th>
                            <th class="text-warning">Project Name</th>
                            <th class="text-warning">Client Name</th>
                            <th class="text-warning">Event</th>
                            <th class="text-warning">Date</th>
                            <th class="text-warning">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ii = $offset + 1;
                        while ($row = mysqli_fetch_assoc($res)) {

                            $project_id = $row['project_id'];
                            $pstatus = $row['project_status'];

                            if ($pstatus == 0) {
                                $status = "<span class='text-danger'>Pending</span>";
                                $url1 = $url . "/admin/projects/view-pending-project.php?prid=" . $project_id;
                            } elseif ($pstatus == 1) {
                                $status = "<span class='text-primary'>In Progress</span>";
                                $url1 = $url . "/admin/projects/view-ongoing-project.php?prid=" . $project_id;
                            } elseif ($pstatus == 2) {
                                $status = "<span class='text-success'>Completed</span>";
                                $url1 = $url . "/admin/projects/completed-project-details.php?prdetail&prid=" . $project_id;
                            }
                        ?>
                            <tr>
                                <td class="text-light"><?php echo $ii; ?></td>
                                <td class="text-light"><?php echo $row['project_name']; ?></td>
                                <td class="text-light"><?php echo $row['c_name']; ?></td>
                                <td class="text-light"><?php echo $row['project_event']; ?></td>
                                <td class="text-light"><?php
                                                        $ldate = $row['project_ldate'];
                                                        if ($ldate == "0000-00-00") {
                                                            $date1 = date_create($row['project_sdate']);
                                                            echo date_format($date1, "d M Y");
                                                        } else {
                                                            $date2 = date_create($row['project_sdate']);
                                                            $dat1 = date_format($date2, "d M Y");
                                                            $date3 = date_create($row['project_ldate']);
                                                            $dat2 = date_format($date3, "d M Y");
                                                            echo $dat1 . " - " . $dat2;
                                                        } ?></td>
                                <td><?php echo $status ?></td>
                                <td><a href="<?php echo $url1 ?>" class="btn btn-outline-primary btn-sm">View</a></td>
                            </tr>
                        <?php $ii++;
                        }  ?>

                    </tbody>
                </table>
            </div>


        <?php } else {
            echo "<div class='text-muted mx-auto'>No Result Found</div>";
        } ?>
    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

          $cusID_escaped = mysqli_real_escape_string($con, $cusID);
          $sql_pg = "SELECT * FROM projects_db WHERE cus_id='$cusID_escaped'";
          $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/projects/index.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>
</div>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdropAll" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-white mt-3">Are you sure?</h4>
                <p class="text-center text-warning">You won't be able to revert this! All projetcs will delete including pending, ongoing, and completed</p>
                <form action="actions/update-project.php" method="post">
                    <div class="text-center mt-4">

                        <input type="hidden" name="btn_del_allproj" value="1">
                        <button type="submit" class="me-2 btn btn-danger">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php

include_once('../include/footer.php');
?>