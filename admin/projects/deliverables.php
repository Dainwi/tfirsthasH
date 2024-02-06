<?php
define('TITLE', 'Project Deliverables');
define('PAGE', 'deliverables');
define('PAGE_T', 'Deliverables');
include_once('../include/header.php');
$limit = 15;
if (isset($_GET['pg'])) {
    $page = $_GET['pg'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
?>


<?php
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];
?>

<div class="card card-bg rounded">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-2">
            <p class="text-primary"></p>
            <button type="button" class="btn btn-sm btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modalLayout"> <i class="bx bx-list-plus me-2"></i> add new</button>
        </div>
        <?php
        $sql_completed = "SELECT * FROM d_layout WHERE cus_id={$cusID} ORDER BY dl_id DESC LIMIT {$offset},{$limit}";
        $res_completed = mysqli_query($con, $sql_completed);
        if (mysqli_num_rows($res_completed) > 0) {
            $ii = $offset + 1;
        ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-warning">#</th>
                            <th class="text-warning">TITLE</th>
                            <th class="text-warning">DELIVERABLES</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row3 = mysqli_fetch_assoc($res_completed)) {



                        ?>
                            <tr>
                                <td class="text-light"><?php echo $ii; ?></td>
                                <td class="text-light"><?php echo $row3['dl_name']; ?></td>
                                <td> <button type="button" data-id="<?php echo $row3['dl_id']; ?>" data-name="<?php echo $row3['dl_name']; ?>" class="btn btn-outline-primary d-inline btn-sm btn-view"><i class="fas fa-eye"></i> VIEW</button></td>
                                <td>
                                    <button type="button" data-id="<?php echo $row3['dl_id']; ?>" data-name="<?php echo $row3['dl_name']; ?>" class="btn btn-outline-secondary d-inline btn-sm btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                    <button type="button" data-id="<?php echo $row3['dl_id']; ?>" class="btn btn-outline-danger d-inline btn-sm btn-delete"><i class="fas fa-trash-alt"></i></button>
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

            $sql_pg = "SELECT * FROM d_layout WHERE cus_id={$cusID}";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/deliverables.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/projects/deliverables.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/projects/deliverables.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }


            ?>



        </nav>
    </div>
</div>

<!-- modal add layout start  -->
<form action="actions/new-project-action.php" method="post" id="form-layout">
    <div class="modal fade" id="modalLayout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="staticBackdropLabel">New Deliverable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                    <div class="form-group">
                        <small for="pp_title" class="text-light">Title</small>
                        <input type="text" required name="title" class="form-control" id="pp_title" placeholder="title">
                    </div>
                    <div class="card p-2 mt-3">
                        <p><span class=" text-light"> Deliverables</span> <span class="float-end"><button class="ms-4 btn-sm btn btn-outline-info btn-sm py-1" id="add_ppitems_btn"><i class="fas fa-plus me-2"></i>add more</button></span></p>
                        <div id="ppItems">
                            <div class="row">
                                <div class="col-11 m-0 pr-0 form-group">
                                    <input type="text" required class="pr-0 mr-0 form-control" id="layout-item1" value="" name="l_item[]" placeholder="desc">
                                </div>
                                <div class="col-1 m-0 pl-0">

                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="deliverablesave" value="1">
                    <button type="button" class="btn btn-default me-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary" id="save-layout">Save</button>
                </div>

            </div>
        </div>
    </div>
</form>
<!-- modal add layout end  -->

<!-- Modal view quotation start-->
<div class="modal fade" id="modalViewDeliverables" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header py-3">
                <h5 class="modal-title text-warning" id="modalViewTitle">Deliverables</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalViewDeliverablesBody">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer pt-1 pb-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>


            </div>
        </div>
    </div>
</div>
<!-- Modal view quotation end-->

<!-- Modal view quotation start-->
<div class="modal fade" id="modalEditDeliverables" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header py-3">
                <h5 class="modal-title text-warning" id="modalEditTitle">Deliverables</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalEditDeliverablesBody">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer pt-1 pb-3">
                <button type="button" class="btn btn-outline-primary me-3" id="update-deliv">Update</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>


            </div>
        </div>
    </div>
</div>
<!-- Modal view quotation end-->



<!-- modal delete  -->
<div class="modal fade" id="modal-delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-info mt-3">Are you sure?</h4>
                <p class="text-center text-light">You won't be able to revert this!</p>
                <form action="<?php echo $url . "/admin/projects/actions/new-project-action.php" ?>" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="delivid" id="eventid-id" value="">
                        <input type="hidden" name="btn_del_deliverable" value="1">
                        <button type="submit" class="me-2 btn btn-warning btn-firsthash">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




<script>
    $("#update-deliv").click(function(e) {
        e.preventDefault();
        $("#form-deliv").submit();
    });
    $("#add_ppitems_btn").on('click', function(e) {
        e.preventDefault()
        $("#ppItems").append("<div class='row mt-1 litem-add-class'>\
                                <div class='col-11 m-0 pr-0 form-group'>\
                                    <input type='text' class='pe-0 me-0 form-control' required name='l_item[]' placeholder='desc'></div>\
                                <div class='col-1 m-0 ps-0'>\
                                    <button class='btn btn-sm btn-outline-danger btn-rounded btn-remove-ppitem'>X </button></div></div>");
        $(".btn-remove-ppitem").on('click', function(e) {
            e.preventDefault();
            $(this).closest(".row").remove();

        });
    });
    $(".btn-delete").click(function(e) {
        e.preventDefault();
        var eid = $(this).attr('data-id');
        $("#eventid-id").val(eid);
        $('#modal-delete').modal('show');

    });

    $(".btn-view").click(function(e) {
        e.preventDefault();
        var eid = $(this).attr('data-id');
        var ename = $(this).attr('data-name');
        $("#modalViewTitle").html(ename);
        $("#modalViewDeliverables").modal('show');
        $.ajax({
            type: "post",
            url: "actions/new-project-action.php",
            data: {
                viewdeliverable: true,
                did: eid,
            },
            success: function(response) {
                $("#modalViewDeliverablesBody").html(response);
            }
        });
    });

    $(".btn-edit").click(function(e) {
        e.preventDefault();
        var eid = $(this).attr('data-id');
        var ename = $(this).attr('data-name');
        $("#modalEditTitle").html(ename);
        $("#modalEditDeliverables").modal('show');
        $.ajax({
            type: "post",
            url: "actions/new-project-action.php",
            data: {
                editdeliverable: true,
                did: eid,
            },
            success: function(response) {
                $("#modalEditDeliverablesBody").html(response);
                $(".input-item").change(function(e) {
                    e.preventDefault();
                    var id = $(this).attr('data-id');
                    var data = $(this).val();
                    $.ajax({
                        type: "post",
                        url: "actions/new-project-action.php",
                        data: {
                            updatedeliv: true,
                            id: id,
                            data: data,
                        },
                        success: function(response) {
                            if (response == 0) {
                                alert("something went wrong");
                            }
                        }
                    });

                });


                $("#btn-addmoree").click(function(e) {
                    e.preventDefault();
                    $("#form-body").append("<div class='row mt-1 litem-add-class'>\
                                <div class='col-11 m-0 pr-0 form-group'>\
                                    <input type='text' class='pe-0 me-0 form-control' required name='l_item[]' placeholder='desc'></div>\
                                <div class='col-1 m-0 ps-0'>\
                                    <button class='btn btn-sm btn-outline-danger btn-rounded btn-remove-ppitem1'>X </button></div></div>");
                    $(".btn-remove-ppitem1").on('click', function(e) {
                        e.preventDefault();
                        $(this).closest(".row").remove();

                    });
                });
            }
        });
    });
</script>

<?php
include_once('../include/footer.php')
?>