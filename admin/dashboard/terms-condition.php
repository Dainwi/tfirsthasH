<?php
define('TITLE', 'Admin Dashboard');
define('PAGE', 'dashboard');
define('PAGE_T', 'Terms & Conditions');
include_once('../include/header.php');
$limit = 15;
if (isset($_GET['pg'])) {
    $page = $_GET['pg'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
$cusID=$_SESSION['user_id'];
?>


<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
?>

<div class="card card-bg rounded">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-2">
            <p class="text-primary"></p>
            <button type="button" class="btn btn-sm btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modal-addevent"> <i class="bx bx-list-plus me-2"></i> add new</button>
        </div>
        <?php
        $sql_completed = "SELECT * FROM terms_condition WHERE cus_id={$cusID} ORDER BY terms_id DESC LIMIT {$offset},{$limit}";
        $res_completed = mysqli_query($con, $sql_completed);
        if (mysqli_num_rows($res_completed) > 0) {
            $ii = $offset + 1;
        ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-warning">#</th>
                            <th class="text-warning">TERMS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row3 = mysqli_fetch_assoc($res_completed)) {



                        ?>
                            <tr>
                                <td class="text-light"><?php echo $ii; ?></td>
                                <td class="text-light"><?php echo $row3['terms_details']; ?></td>
                                <td><input type="hidden" name="tid" value="<?php echo $row3['terms_id'] ?>">
                                    <input type="hidden" name="terms" value="<?php echo $row3['terms_details'] ?>">
                                    <button type="button" class="btn btn-outline-secondary d-inline btn-sm btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                    <button type="button" class="btn btn-outline-danger d-inline btn-sm btn-delete"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php $ii++;
                        }  ?>

                    </tbody>
                </table>
            </div>
        <?php

        } else {
            echo "<div class='text-muted mx-auto text-center'>No Result Found</div>";
        } ?>

    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            $sql_pg = "SELECT * FROM terms_condition";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/dashboard/terms-condition.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/dashboard/terms-condition.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/dashboard/terms-condition.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>
</div>

<!-- modal add event start -->
<div class="modal fade" id="modal-addevent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Add New </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $url . "/admin/dashboard/actions.php" ?>" method="post">
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="floatingDesc" placeholder="Description" name="terms"></textarea>
                            <label for="floatingDesc" class="text-warning">Terms</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="add-terms" value="save">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal add event end -->

<!-- modal edit event start -->
<div class="modal fade" id="modal-editevent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Edit Term </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $url . "/admin/dashboard/actions.php" ?>" method="post">
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="floatingDesc1" placeholder="Description" name="terms"></textarea>
                            <label for="floatingDesc1" class="text-warning">Description</label>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="termsid" id="termid">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="edit-terms" value="update">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal edit event end -->

<!-- modal delete  -->
<div class="modal fade" id="modal-delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-info mt-3">Are you sure?</h4>
                <p class="text-center text-light">You won't be able to revert this!</p>
                <form action="<?php echo $url . "/admin/dashboard/actions.php" ?>" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="eventid" id="eventid-id" value="">
                        <input type="hidden" name="btn_del_terms" value="1">
                        <button type="submit" class="me-2 btn btn-warning btn-firsthash">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




<script>
    $(".btn-edit").click(function(e) {
        e.preventDefault();
        var eid = $(this).closest('td').find('input[name="tid"]').val();
        var edesc = $(this).closest('td').find('input[name="terms"]').val();

        $("#floatingDesc1").val(edesc);
        $("#termid").val(eid);
        $('#modal-editevent').modal('show');

    });
    $(".btn-delete").click(function(e) {
        e.preventDefault();
        var eid = $(this).closest('td').find('input[name="tid"]').val();
        $("#eventid-id").val(eid);

        $('#modal-delete').modal('show');

    });
</script>

<?php
include_once('../include/footer.php')
?>