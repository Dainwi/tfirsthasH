<?php
define('TITLE', 'Team Types');
define('PAGE', 'employee-type');
define('PAGE_T', 'Team Type');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];

// echo $cusID;

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<div class="card-bg px-4 pt-4 d-flex justify-content-between">
    <h5 class="border-bottom">TEAM CATEGORIES</h5> <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Add Category</button>
</div>
<div class="card card-bg">
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM employee_type WHERE cus_id={$cusID} OR cus_id=0";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            $ii = 1;
        ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-info">#</th>
                        <th scope="col" class="text-center text-info">Category Name</th>
                        <th scope="col" class="text-center text-info">Type</th>
                        <th scope="col" class="text-center"></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($res)) {
                        $typep = $row['type_production'];
                        if ($typep == 0) {
                            $type = "On Production";
                        } else {
                            $type = "Post Production";
                        }
                    ?>
                        <tr>
                            <th scope="row"><?php echo $ii; ?></th>
                            <td class="text-center td-type"><?php echo $row['type_name']; ?></td>
                            <td class="text-center td-type"><?php echo $type ?></td>

                            <td class="text-center">
                                <input type="hidden" name="type-name" value="<?php echo $row['type_name'] ?>">
                                <input type="hidden" name="type-id" value="<?php echo $row['type_id'] ?>">
                                <button class="btn btn-lg text-info p-1 border-0 me-2 btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-lg text-danger p-1 border-0 ms-2 btn-delete"><i class="fas fa-trash-alt"></i></button>
                            </td>

                        </tr>
                    <?php
                        $ii++;
                    }
                    ?>

                </tbody>
            </table>
        <?php
        } else {
            echo "<p class='text-center'>no category</p>";
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Team Category</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $url . "/admin/employees/employee-actions.php" ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">

                        <input type="text" name="emptype" required placeholder="category name" class="form-control" id="employeetype" aria-describedby="type">
                        <input type="hidden" name="type-submit" value="1">
                    </div>
                    <div class="mb-3">
                        <select name="ptype" class="form-control">
                            <option value="2">select type...</option>
                            <option value="0">On Production</option>
                            <option value="1">Post Production</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="mx-3 btn btn-warning btn-firsthash">Save </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Category</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $url . "/admin/employees/employee-actions.php" ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">

                        <input type="text" name="emptype" required placeholder="enter employee category" class="form-control" id="input-cat" aria-describedby="type">
                        <input type="hidden" name="type-update" id="input-id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="mx-3 btn btn-warning btn-firsthash">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal delete  -->
<div class="modal fade" id="deleteModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                <p class="text-center">You won't be able to revert this!</p>
                <form action="<?php echo $url . "/admin/employees/employee-actions.php" ?>" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="type-delete" id="input-typeid" value="">

                        <button type="submit" class="me-2 btn btn-info">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".btn-edit").click(function(e) {
            e.preventDefault();
            var typeid = $(this).closest('td').find('input[name="type-id"]').val();
            var type = $(this).closest('td').find('input[name="type-name"]').val();
            $("#input-cat").val(type);
            $("#input-id").val(typeid);
            $("#editModal").modal('show');


        });

        $(".btn-delete").click(function(e) {
            var typeid = $(this).closest('td').find('input[name="type-id"]').val();
            e.preventDefault();
            $("#input-typeid").val(typeid);
            $("#deleteModal").modal('show');

        });
    });
</script>
<?php
include_once('../include/footer.php');
?>