<?php
define('TITLE', 'My Projects');
define('PAGE', 'completed-projects');
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
?>

<div class="container mb-4">
    <div class="card shadow card-bg">

        <div class="card-body">

            <?php $sql2 = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE project_status=2 AND assign_manager_db.am_user_id ={$manager_id} ORDER BY project_id DESC LIMIT {$offset},{$limit}";
            $res2 = mysqli_query($con, $sql2);
            if (mysqli_num_rows($res2) > 0) {
                $ii = 1;
            ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-warning">#</th>
                                <th class="text-warning">Project Name</th>
                                <th class="text-warning">Client Name</th>
                                <th class="text-warning">Event</th>
                                <th class="text-warning">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($res2)) {
                                $prDate = $row2['project_ldate'];
                                if ($prDate == "0000-00-00") {
                                    $datee = $row2['project_sdate'];
                                } else {
                                    $datee = $row2['project_sdate'] . " to " . $row2['project_ldate'];
                                }
                            ?>
                                <tr>
                                    <td class="text-light"><?php echo $ii; ?></td>
                                    <td class="text-light">
                                        <h6 class="m-b-0"><?php echo $row2['project_name']; ?></h6>
                                    </td>
                                    <td class="text-light"><?php echo $row2['c_name']; ?></td>
                                    <td class="text-light"><?php echo $row2['project_event']; ?></td>
                                    <td class="text-light"><?php echo $prDate ?></td>
                                </tr>


                            <?php
                                $ii++;
                            }

                            ?>

                        </tbody>
                    </table>
                </div>

            <?php
            } else {
                echo "<p class='text-center mt-4'> no project</p>";
            } ?>
        </div>

        <div class="card-footer">
            <nav aria-label="Page navigation example">
                <?php

                $sql_pg = "SELECT * FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client LEFT JOIN assign_manager_db ON assign_manager_db.am_project_id = projects_db.project_id WHERE project_status=2 AND assign_manager_db.am_user_id ={$manager_id}";
                $res_pg = mysqli_query($con, $sql_pg);
                if (mysqli_num_rows($res_pg) > 0) {

                    $total_records = mysqli_num_rows($res_pg);

                    $total_page = ceil($total_records / $limit);
                    echo '<ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/manager/projects/completed-project.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = "";
                        }
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/manager/projects/completed-project.php?pg=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/manager/projects/completed-project.php?pg=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                    }

                    echo '</ul>';
                }


                ?>



            </nav>

        </div>

    </div>
</div>


<?php include_once("../include/footer.php") ?>