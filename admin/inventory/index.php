<?php
define('TITLE', 'Inventory');
define('PAGE', 'inventory');
define('PAGE_T', 'Inventory');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$limit = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $ii = ($page - 1) * $limit + 1;
} else {
    $page = 1;
    $ii = 1;
}
$offset = ($page - 1) * $limit;
?>


<div class="container-fluid">
    <div class="p-2 card-bg rounded">
        <div class="d-flex justify-content-between align-items-center px-3 mb-3">

            <button data-bs-toggle="modal" data-bs-target="#modalInventory" class="btn-secondary btn-sm btn" type="button">
                <i class="fas fa-plus"></i> create new

            </button>
        </div>

        <?php

        $sql2 = "SELECT * FROM inventory_db WHERE cus_id={$_SESSION['user_id']} ORDER BY inventory_id DESC LIMIT {$offset},{$limit}";
        $res2 = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res2) > 0) {

        ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-info">#</th>
                            <th class="text-info">Product Name</th>
                            <th class="text-info">Price</th>
                            <th class="text-info">Date</th>
                            <th class="text-info">Assign to</th>
                            <th class="text-info"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row2 = mysqli_fetch_assoc($res2)) {
                            $inid = $row2['inventory_id'];
                        ?>

                            <tr>
                                <td><?php echo $ii; ?></td>
                                <td><?php echo $row2['product_name']; ?></td>
                                <td><?php echo "Rs. " . $row2['product_amount']; ?></td>
                                <td><?php echo $row2['i_date']; ?></td>
                                <td><?php echo $row2['assign_to']; ?></td>

                                <td><input type="hidden" name="inid" value="<?php echo $inid ?>">
                                    <button type="button" class="btn btn-sm btn-secondary btn-tone btn-view"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger btn-tone btn-del"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php $ii++;
                        }  ?>

                    </tbody>
                </table>
            </div>
        <?php } else {
            echo "<p class='mt-4 text-center'>no data</p>";
        } ?>

    </div>

    <div class="card-footer">
        <nav aria-label="Page navigation example">
            <?php

            $sql_pg = "SELECT * FROM inventory_db WHERE cus_id={$_SESSION['user_id']}";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/inventory/index.php?page=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/inventory/index.php?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/inventory/index.php?page=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalInventory">
    <form action="new-inventory.php" method="post">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="modalInventoryTitle">New Inventory</h5>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal">
                        X
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" name="p-name" class="form-control mt-3" placeholder="Product Name*">
                        </div>
                        <div class="col-6">

                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <div class="input-group mb-3 mt-4">

                                <span class="input-group-text text-info" id="basic-addon1">Rs. </span>

                                <input type="number" name="amount" class="form-control" placeholder="Amount*" aria-label="Amount" aria-describedby="basic-addon1" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <small for="formGroupExampleInput" class="my-0 py-0 text-info">Purchase Date</small>
                                <input type="date" name="date" class="form-control" id="formGroupExampleInput" placeholder="purchase date" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <small for="formGroupExampleInput" class="my-0 py-0 text-info">Assign to</small>
                                <input type="text" name="assign-to" class="form-control" id="formGroupExampleInput" placeholder="assign to">
                            </div>
                        </div>
                    </div>

                    <small class="mb-2 pb-3 text-warning">more info :</small>
                    <div id="more-info">

                    </div>
                    <button type="button" class="btn btn-sm btn-secondary btn-tone mt-2 add-more"><i class="fas fa-plus-circle"></i> add more</button>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="inventory" value="1">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary px-4">Save </button>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Modal view-->
<div class="modal fade" id="modalViewNote">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-body py-4" id="modal-view-in">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdropDel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-3">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                <p class="text-center text-info">You won't be able to revert this!</p>
                <form action="new-inventory.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="inid" id="in-id" value="">
                        <input type="hidden" name="btn_del_inventory" value="1">
                        <button type="submit" class="mr-2 btn btn-secondary">Yes, delete it!</button>
                        <button type="button" class="ml-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    //event script
    $(document).on("click", ".remove-btn", function() {
        $(this).closest(".add-form").remove();
    });
    $(".add-more").click(function(e) {
        e.preventDefault();
        $("#more-info").append(
            ' <div class="add-form"> <div class="row">\
                <div class="col-md-4 mr-0 pr-0">\
                <div class="form-group">\
                    <input type="text" name="info1[]" class="form-control" id="formGroupExampleInput" placeholder="info 1*" required>\
                </div>\
                </div>\
                <div class="col-md-4 mr-0 pr-0">\
            <div class="form-group">\
                <input type="text" name="info2[]" class="form-control" id="formGroupExampleInput2" placeholder="info 2" >\
            </div>\
            </div>\
            <div class="col-md-3 mr-0 pr-0">\
            <div class="form-group">\
                <input type="text" name="info3[]" class="form-control" id="formGroupExampleInput2" placeholder="info 3">\
            </div>\
            </div>\
            <div class="col-md-1 ml-0 pl-1"><button class="btn btn-sm btn-outline-danger remove-btn"><i class="fas fa-trash"></i></button></div>\
        </div></div>'
        );
    });

    $(".btn-del").on('click', function(e) {
        e.preventDefault();
        var ddd = $(this).closest('td').find("input[name='inid']").val();

        $("#in-id").val(ddd);

        $("#staticBackdropDel").modal('show');
    });

    $(".btn-view").on('click', function(e) {
        e.preventDefault();
        var inventory = $(this).closest('td').find("input[name='inid']").val();

        $.ajax({
            type: "post",
            url: "new-inventory.php",
            data: {
                viewin: true,
                inid: inventory,
            },

            success: function(response) {
                $("#modal-view-in").html(response);
                $("#modalViewNote").modal('show');
            }
        });


    });
</script>
<?php
include_once("../include/footer.php");
?>