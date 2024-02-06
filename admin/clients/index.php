<?php
define('TITLE', 'Clients');
define('PAGE', 'clients');
define('PAGE_T', 'Clients');
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

<form action="<?php echo $url . "/admin/clients/index.php"; ?>" method="get">
    <div class="container card-bg p-2">
        <div class="row">
            <div class="col-md-5 ">
                <div class="input-group">

                    <input type="text" name="search" class="form-control border border-primary" placeholder="Search Clients">
                    <button type="submit" class="btn btn-outline-primary" id="btn-search-client">Search</button>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                if (isset($_GET['search'])) {
                    echo "<p class='text-center'>Search for : <span class='font-weight-bold'> {$_GET["search"]}</span></p>";
                }

                ?>

            </div>
            <div class="col-md-3 text-end">
                <!--<button type="button" class="btn btn-outline-danger btn-sm py-1 btn-delete-all" data-bs-target="#staticDeleteClients" data-bs-toggle="modal">Delete All</button>-->
            </div>
        </div>
    </div>

</form>
<div class="card container card-bg p-3 mt-3">

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class='text-warning'>#</th>
                    <th><small class="text-warning">Name</small></th>
                    <th><small class="text-warning">Phone</small></th>
                    <th><small class="text-warning">Email</small></th>
                    <!-- <th><small class="text-warning">Authentication</small></th> -->
                    <th><small class="text-warning">Projects</small></th>
                    <th><small class="text-warning"></small></th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (isset($_GET['search'])) {
                    $termS = $_GET['search'];
                    if ($termS != "") {
                        // $sql = "SELECT * FROM clients_db WHERE cus_id=$cusID AND LOWER(c_name) LIKE LOWER('%$termS%') OR c_phone LIKE '%$termS%' OR LOWER(c_email) LIKE LOWER('%$termS%')";
                        $sql = "SELECT * FROM clients_db WHERE cus_id = $cusID AND (LOWER(c_name) LIKE LOWER('%$termS%') OR c_phone LIKE '%$termS%' OR LOWER(c_email) LIKE LOWER('%$termS%'))";

                    } else {
                        $sql = "SELECT * FROM clients_db WHERE cus_id={$cusID} ORDER BY c_id DESC LIMIT {$offset},{$limit}";
                    }
                } else {
                    $sql = "SELECT * FROM clients_db WHERE cus_id={$cusID} ORDER BY c_id DESC LIMIT {$offset},{$limit}";
                }


                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($res)) {
                ?>
                        <tr>
                            <td class='text-light'><?php echo $i; ?></td>
                            <td class="c-name text-light"><?php echo $row['c_name'] ?></td>
                            <td class="c-phone text-light"><?php echo $row['c_phone'] ?></td>
                            <td class="c-email text-light"><?php echo $row['c_email'] ?></td>
                            <!-- <td class='text-center'>
                                <?php
                                $verify = $row['c_auth'];
                                ?>
                                <form action="clients-auth.php" method="post" id="form-toggle">

                                    <input type="hidden" name="clid" value="<?php echo $row['c_id']; ?>">
                                    <input type="hidden" name="clname" value="<?php echo $row['c_name']; ?>">
                                    <input type="hidden" name="clemail" value="<?php echo $row['c_email']; ?>">
                                    <input type="hidden" name="clphone" value="<?php echo $row['c_phone']; ?>">
                                    <input type="hidden" name="togglebtn1" value="1">
                                    <div class="form-check form-switch text-center">
                                        <input type="checkbox" class="form-check-input switch-toogle border shadow" style="border: 1px solid #000 !important;" id="customSwitch<?php echo $i; ?>" value="<?php echo $verify; ?>" name="verify" <?php if ($verify == 1) {
                                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                                } ?>>
                                        <label class="custom-control-label form-check-label" for="customSwitch<?php echo $i; ?>"></label>
                                    </div>
                                </form>

                            </td> -->
                            <td>
                                <div> <input type="hidden" name="cl_id" value="<?php echo $row['c_id']; ?>">
                                    <button class="btn btn-sm btn-outline-primary view-project">View</button>
                                </div>
                            </td>
                            <td>
                                <div> <input type="hidden" name="client_id" value="<?php echo $row['c_id']; ?>">
                                    <input type="hidden" name="client_name" value="<?php echo $row['c_name']; ?>">
                                    <input type="hidden" name="client_email" value="<?php echo $row['c_email']; ?>">
                                    <input type="hidden" name="client_phone" value="<?php echo $row['c_phone']; ?>">
                                    <input type="hidden" name="client_address" value="<?php echo $row['c_address']; ?>">
                                    <button class="btn btn-sm btn-secondary d-inline edit-client"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-sm btn-danger btn-tone d-inline delete-client"><i class="fas fa-trash-alt"></i> </button>
                                </div>
                            </td>

                        </tr>


                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>no result found</td></tr>";
                }

                ?>

            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    <nav aria-label="Page navigation example">
        <?php
        if (isset($_GET['search']) && $_GET['search'] != "") {
        } else {

            $sql_pg = "SELECT * FROM clients_db";
            $res_pg = mysqli_query($con, $sql_pg);
            if (mysqli_num_rows($res_pg) > 0) {

                $total_records = mysqli_num_rows($res_pg);

                $total_page = ceil($total_records / $limit);
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/clients/index.php?pg=" . ($page - 1) . "'>Prev</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/clients/index.php?pg=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo "<li class='page-item'><a class='page-link' href='{$url}/admin/clients/index.php?pg=" . ($page + 1) . "'>Next</a></li>";
                } else {
                    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                }

                echo '</ul>';
            }
        }

        ?>



    </nav>
</div>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdropDelclient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3">Are you sure?</h4>
                <p class="text-center">You won't be able to revert this!</p>
                <form action="clients-auth.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="delclientid" id="del-client" value="">
                        <input type="hidden" name="btn_del_client" value="1">
                        <button type="submit" class="mr-2 btn btn-secondary">Yes, delete it!</button>
                        <button type="button" class="ml-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-project" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="exampleModalLabel">Projects</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <!--<span aria-hidden="true">&times;</span>-->
                </button>
            </div>
            <div class="modal-body py-3" id="modal-project-body">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalClientEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="exampleModalLabel">Edit Client</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <!--<span aria-hidden="true">&times;</span>-->
                </button>
            </div>
            <form action="clients-auth.php" method="post">
                <div class="modal-body" id="client-body-modal">
                    <div class="form-group">
                        <small for="formGroupName">Client Name</small>
                        <input type="text" class="form-control" name="cl_name" required id="formGroupName" placeholder="name">
                    </div>
                    <div class="form-group">
                        <small for="formGroupPhone">Client Phone</small>
                        <input type="number" class="form-control" name="cl_phone" required id="formGroupPhone" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <small for="formGroupEmail">Client Email</small>
                        <input type="email" class="form-control" name="cl_email" required id="formGroupEmail" placeholder="email">
                    </div>
                    <div class="form-group">
                        <small for="formGroupAddress">Client Address</small>
                        <textarea class="form-control" aria-label="With textarea" name="cl_address" id="formGroupAddress" placeholder="address" required rows="3"></textarea>
                    </div>
                    <input type="hidden" name="clientid" id="editClid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary px-3">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- modal delete  -->
<div class="modal fade" id="staticDeleteClients" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-white mt-3">Are you sure?</h4>
                <p class="text-center text-warning">You won't be able to revert this! All Clients will delete including projects, tasks, and more</p>
                <form action="clients-auth.php" method="post">
                    <div class="text-center mt-4">

                        <input type="hidden" name="btn_del_allclients" value="1">
                        <button type="submit" class="me-2 btn btn-danger">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).on('change', '.switch-toogle', function() {

        $(this).closest('td').find('#form-toggle').submit();

    });
    $(".view-project").on('click', function(e) {
        e.preventDefault();
        $("#modal-project").modal('show');
        var clid = $(this).closest('td').find("input[name='cl_id']").val();


        $.ajax({
            type: "post",
            url: "clients-project.php",
            data: {
                projectData: true,
                clid: clid,
            },
            success: function(response) {
                $("#modal-project-body").html(response);
            }
        });

    });

    $(".edit-client").on('click', function(e) {
        e.preventDefault();
        $("#modalClientEdit").modal('show');
        var clid = $(this).closest('td').find("input[name='client_id']").val();
        var clname = $(this).closest('td').find("input[name='client_name']").val();
        var clphone = $(this).closest('td').find("input[name='client_phone']").val();
        var clemail = $(this).closest('td').find("input[name='client_email']").val();
        var claddress = $(this).closest('td').find("input[name='client_address']").val();

        $("#editClid").val(clid);
        $("#formGroupName").val(clname);
        $("#formGroupPhone").val(clphone);
        $("#formGroupEmail").val(clemail);
        $("#formGroupAddress").val(claddress);

    });

    // $(".delete-client").on('click', function(e) {
    //     e.preventDefault();
    //     $("#staticBackdropDelclient").modal('show');
    //     var clid = $(this).closest('td').find("input[name='client_id']").val();
    //     $("#del-client").val(clid);

    // });
</script>

<?php
include_once("../include/footer.php");
?>