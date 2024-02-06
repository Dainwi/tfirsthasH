<?php
define('TITLE', 'Completed Projects');
define('PAGE', 'completed-project');
define('PAGE_T', 'Completed Projects');
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
?>

<div class="card card-bg rounded">
    <div class="card-body">
        <?php
        $sql_completed = "SELECT*FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE projects_db.cus_id={$cusID} AND project_status=2 ORDER BY project_id DESC LIMIT {$offset},{$limit}";
        $res_completed = mysqli_query($con, $sql_completed);
        if (mysqli_num_rows($res_completed) > 0) {
            $ii = $offset + 1;
        ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-warning">#</th>
                            <th class="text-warning">Project Name</th>
                            <th class="text-warning">Client Name</th>
                            <th class="text-warning">Event</th>
                            <th class="text-warning">Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row3 = mysqli_fetch_assoc($res_completed)) {

                            $project_id = $row3['project_id'];
                        ?>
                            <tr>
                                <td class="text-light"><?php echo $ii; ?></td>
                                <td class="text-light"><?php echo $row3['project_name']; ?></td>
                                <td class="text-light"><?php echo $row3['c_name']; ?></td>
                                <td class="text-light"><?php echo $row3['project_event']; ?></td>
                                <td class="text-light"><?php
                                                        $sdate = date_create($row3['project_sdate']);
                                                        $sdt = date_format($sdate, "d M Y");
                                                        $ldt1 = date_create($row3['project_ldate']);
                                                        $ldt = date_format($ldt1, "d M Y");
                                                        $ldate = $row3['project_ldate'];

                                                        if ($ldate == "0000-00-00") {
                                                            echo $sdt;
                                                        } else {

                                                            echo $sdt . " " . "-" . " " . $ldt;
                                                        } ?></td>
                                <td>

                                    <a href="<?php echo $url . "/admin/projects/completed-project-details.php?prdetail&prid=" . $project_id; ?>" class="btn btn-secondary btn-tone btn-sm">view</a>
                                    <button class="btn btn-outline-danger btn-tone btn-sm btn-delete" data-id="<?php echo $project_id  ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php $ii++;
                        }  ?>

                    </tbody>
                </table>
            </div>
        <?php

        } else {
            echo "<div class='text-muted mx-auto'>No Result Found</div>";
        } ?>

    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            $sql_pg = "SELECT * FROM projects_db WHERE project_status=2";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/completed-projects.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/projects/completed-projects.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/completed-projects.php?pg=" . ($page + 1) . "'>Next</a></li>";
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
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-info mt-3">Are you sure?</h4>
                <p class="text-center text-light">You won't be able to revert this!</p>
                <form action="actions/update-project.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="prjid" id="proj-com" value="">
                        <input type="hidden" name="btn_del_completedproj" value="1">
                        <button type="submit" class="me-2 btn btn-warning btn-firsthash">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(".btn-delete").on('click', function(e) {
        e.preventDefault();
        var ddd = $(this).attr('data-id');

        $("#proj-com").val(ddd);

        $("#staticBackdrop").modal('show');
    });
</script>

<?php
include_once('../include/footer.php');
?>