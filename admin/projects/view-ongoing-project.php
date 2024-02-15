<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('TITLE', 'Projects');
define('PAGE', 'ongoing-project');
define('PAGE_T', 'Project Details');
include_once('../include/header.php');
?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<style>
    .nav-tabs {
        border-bottom: 2px solid #3d3d3d;
    }

    .nav-tabs .nav-item .nav-link {
        background-color: transparent;
        color: #9a9cab;
    }

    .nav-tabs .nav-item .active {
        background-color: transparent;
        border-bottom: 2px solid #83d8ae;
        color: #83d8ae;
    }

    .border-bottom-default {
        border-bottom: 1px solid #3d3d3d !important;
    }

    .border-default {
        border: 1px solid #3d3d3d !important;
    }

    .ql-toolbar button[type="button"] {
        cursor: pointer;
        border-radius: 4px;
        margin-right: 2px;
        opacity: 0.9;
        filter: invert(1.3);

    }

    .ql-editor {
        cursor: pointer;
        border-radius: 4px;
        margin-right: 2px;
        opacity: 1;
        filter: invert(1.2);
        border: none;

    }
</style>
<?php
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];
//  AND projects_db.cus_id={$cusID}

$prid = $_GET['prid'];
if (isset($_GET['prid'])) {
    $prid = $_GET['prid'];

    $sql = "SELECT*FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_id={$prid} AND projects_db.cus_id={$cusID}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $event = $row['project_event'];

            $suggestion = $row['project_note'];
            $projectName = $row['project_name'];
            $clName = $row['c_name'];
            $clPhone = $row['c_phone'];
            $clEmail = $row['c_email'];
            $clAddress = $row['c_address'];
            $sdat = date_create($row['project_sdate']);
            $sdate = date_format($sdat, "d M Y");
            $ldate = $row['project_ldate'];

            $ldat = date_create($row['project_ldate']);
            $ldatee = date_format($ldat, "d M Y");
            $projstatus = $row['project_status'];
        }
        if ($ldate == "0000-00-00") {
            $date = $sdate;
        } else {
            $date = $sdate . " - " . $ldatee;
        }
        $urlp = $url . "/admin/projects/view-pending-project.php?prid=" . $prid;
        $urlc = $url . "/admin/projects/completed-project-details.php?prid=" . $prid;

        if ($projstatus == 0) {

            echo "<script> location.replace('{$urlp}');</script>";
        } elseif ($projstatus == 2) {

            echo "<script> location.replace('{$urlc}');</script>";
        }
    }
?>

    <!-- Tabs navs -->
    <ul class="nav nav-tabs" id="ex1" role="tablist">
        <li class="nav-item me-3" role="presentation">
            <a class="nav-link active" id="tab-detail" data-bs-toggle="tab" href="#tab-details" role="tab" aria-controls="tab-details" aria-selected="true">Details</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="tab-occasion" data-bs-toggle="tab" href="#tab-occasions" role="tab" aria-controls="tab-occasions" aria-selected="false">Occasions</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="tab-deliverable" data-bs-toggle="tab" href="#tab-deliverables" role="tab" aria-controls="tab-deliverables" aria-selected="false">Deliverables</a>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <a class="nav-link" id="tab-financial" data-bs-toggle="tab" href="#tab-financials" role="tab" aria-controls="tab-financials" aria-selected="false">Financials</a>
        </li>

    </ul>
    <!-- Tabs navs -->
    <div class="card-bg h-100 mx-0">
        <div class="card-body tab-content" id="ex1-content">
            <div class="tab-pane fade show active" id="tab-details" role="tabpanel" aria-labelledby="tab-detail">
                <div class="d-flex justify-content-between">
                    <div class="media align-items-center">
                        <div class="ms-3">
                            <p class='text-danger '><?php echo $event ?></p>
                            <h4 class="mt-3 text-warning"><?php echo $projectName; ?></h4>
                        </div>
                    </div>
                    <div>
                        <span class='text-success'> <small><i class="p-2 fas fa-circle"></i></small> In Progress</span>
                    </div>
                </div>
                <!-- project details start -->
                <div class="mt-3 px-2">

                    <div class="row">
                        <div class="col-4 col-md-2">
                            <span class="text-info"> Client Name</span>
                        </div>
                        <div class="col-8 text-light">
                            <?php echo "<span class='me-3'>:</span> " . $clName; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-md-2">
                            <span class="text-info"> Phone</span>
                        </div>
                        <div class="col-8 text-light">
                            <?php echo "<span class='me-3'>:</span> " . $clPhone; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 col-md-2">
                            <span class="text-info"> Email</span>
                        </div>
                        <div class="col-8 text-light">
                            <?php echo "<span class='me-3'>:</span> " . $clEmail; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-md-2">
                            <span class="text-info"> Address</span>
                        </div>
                        <div class="col-8 text-light">
                            <?php echo "<span class='me-3'>:</span> " . $clAddress; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 col-md-2">
                            <span class="text-info"> Date</span>
                        </div>
                        <div class="col-8 text-light">
                            <?php echo "<span class='me-3'>:</span> " . $date; ?>
                        </div>
                    </div>

                </div>
                <!-- project details end -->
                <div class="row mt-3 border-top border-dark">
                    <!-- assign manager -->
                    <div class="col-md-6 mt-4">
                        <span class="text-light font-weight-semibold me-3">Manager : </span>
                        <?php
                        $sql_manager = "SELECT * FROM assign_manager_db LEFT JOIN users_db ON assign_manager_db.am_user_id = users_db.u_id WHERE am_project_id ={$prid}";
                        $res_manager = mysqli_query($con, $sql_manager);
                        if (mysqli_num_rows($res_manager) > 0) {
                            while ($row_manager = mysqli_fetch_assoc($res_manager)) {
                                $manager = $row_manager['user_name'];
                                $amId = $row_manager['am_id'];
                            }
                            echo "<input type='hidden' value='{$amId}' id='am_manager'> <a class='mr-2 m-b-5' href='#'>
                                                    <span class='font-size-14 text-primary font-weight-semibold'>$manager</span>
                                                </a> <span type='button' class='px-2' id='updateManager'><i class='fas fa-pencil-alt'></i></span>";
                        } else {
                            echo "<small class='text-warning'>Not Assigned</small><button class='btn btn-sm ms-2 py-1 px-3 btn-outline-info btn-round' id='as-manager'>Assign</button>";
                        }
                        ?>

                    </div>
                    <div class="col-md-6">

                    </div>

                    <div class="col-12 mt-4">
                        <span class="text-light font-weight-semibold me-3">Team : </span>

                        <?php
                        $sqlteam = "SELECT * FROM assign_requirements LEFT JOIN users_db ON assign_requirements.ar_assign_to=users_db.u_id WHERE ar_project_id={$prid} GROUP BY users_db.u_id";
                        $resteam = mysqli_query($con, $sqlteam);
                        if (mysqli_num_rows($resteam) > 0) {
                            while ($rowteam = mysqli_fetch_assoc($resteam)) {
                                echo "<span class='text-primary me-3'>{$rowteam['user_name']}, </span>";
                            }
                        }
                        ?>

                    </div>
                    <div class="d-flex justify-content-between mt-4"> <button class="btn btn-outline-warning" id="view-quotation-btn">Quotation</button>
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#staticCompleteProject">Set Complete</button>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="tab-occasions" role="tabpanel" aria-labelledby="tab-occasion">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-deliverables" role="tabpanel" aria-labelledby="tab-deliverable">
                <div class="d-flex justify-content-between">
                    <p> <button class="btn btn-outline-warning btn-sm px-4 me-2" id="btn-import"> <i class="fas fa-download me-2"></i> import layout</button>
                        <button class="btn btn-outline-primary btn-sm px-4 ms-2" id="btn-layout"> <i class="fas fa-plus me-2"></i> create layout</button>
                        <button class="btn btn-outline-danger btn-sm px-4 ms-2" id="btn-layout-delete"> <i class="fas fa-trash-alt me-2"></i> Delete Layout</button>
                    </p>
                    <p class="text-end">
                        <button class="btn btn-outline-info btn-sm px-4 me-2" id="btn-suggestion" data-bs-target="#nsuggestionModal" data-bs-toggle="modal"> <i class="fas fa-file-alt me-2"></i> suggestions</button>
                    </p>
                </div>
                <div class="card mt-4" id="deliverable-body">

                </div>
            </div>

            <div class="tab-pane fade" id="tab-financials" role="tabpanel" aria-labelledby="tab-financial">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal Assign Manager start-->
    <div class="modal fade" id="assign-manager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalLabel">Assign Manager</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        <!--<span aria-hidden="true">&times;</span>-->
                    </button>
                </div>
                <form action="actions/update-project.php" method="post" id="form-am">
                    <div class="modal-body">
                        <input type="hidden" name="project_id" value="<?php echo $prid ?>">

                        <div class="modal-manager-assign" id="modal-am">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-outline-gray" data-dismiss="modal">Close</button>-->
                        <input type="submit" id="input-assign-btn" class="btn btn-outline-warning btn-round px-4" value="Assign" name="assign_manager">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Assign Manager end-->

    <!-- edit manager modal start -->
    <div class="modal fade" id="updateManagerModal">
        <div class="modal-dialog">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Change Manager</h5>
                    <button type="button" class="btn text-light" data-bs-dismiss="modal">
                        X
                    </button>
                </div>
                <div class="modal-body" id="modal-manager">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- edit manager modal end -->

    <!-- Modal Assign Employee start-->
    <div class="modal fade" id="assign-employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalLabel">Assign Employees</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        <!--<span aria-hidden="true">&times;</span>-->
                    </button>
                </div>
                <input type="hidden" id="name-classs">
                <form action="ajax/ongoing-project-ajax.php" id="form-req" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="project_id" value="<?php echo $prid ?>">
                        <input type="hidden" name="req-submit" value="1">
                        <div class="modal-employee-assign" id="modal-ae">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-outline-gray" data-dismiss="modal">Close</button>-->
                    <input type="button" id="assign-employee-btn" class="btn btn-outline-warning btn-round px-4" value="Save Changes">
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Assign Employee end-->

    <!-- modal add layout start  -->
    <div class="modal fade" id="modalLayout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="staticBackdropLabel">Deliverable Layout</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        <!--<span aria-hidden="true">&times;</span>-->
                    </button>
                </div>

                <div class="modal-body">
                    <form action="ajax/ongoing-project-ajax.php" method="post" id="form-layout">

                        <div class="form-group">
                            <small for="pp_title" class="text-light">Title</small>
                            <input type="text" required name="l_title" class="form-control" id="pp_title" placeholder="title">
                        </div>
                        <div class="card p-2 mt-3">
                            <p><span class=" text-light"> items</span> <span class="float-end"><button class="ms-4 btn-sm btn btn-outline-info btn-sm py-1" id="add_ppitems_btn"><i class="fas fa-plus me-2"></i>add more</button></span></p>
                            <div id="ppItems">
                                <div class="row">
                                    <div class="col-11 m-0 pr-0 form-group">
                                        <input type="text" class="pr-0 mr-0 form-control" id="layout-item1" value="" name="l_item[]" placeholder="desc">
                                    </div>
                                    <div class="col-1 m-0 pl-0">

                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-default me-3" data-bs-dismiss="modal">Close</button>-->
                    <button type="button" class="btn btn-secondary" id="save-layout">Save</button>
                </div>

            </div>
        </div>
    </div>
    <!-- modal add layout end  -->

    <!-- modal import deliverables start  -->
    <div class="modal fade" id="updateViewDeliverables">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Deliverable Layouts</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>
                <div class="modal-body py-4" id="modal-deliverable">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- modal import deliverables end -->


    <!-- modal import deliverables start  -->
    <div class="modal fade" id="deleteViewDeliverables">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Deliverable Layouts</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>
                <div class="modal-body py-4" id="modal-deliverable-delete">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- modal import deliverables end -->

    <!-- modal delete start -->
    <div class="modal fade" id="staticDeleteda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">

                <div class="modal-body py-4">
                    <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                    <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                    <p class="text-center text-info">You won't be able to revert this!</p>

                    <div class="text-center mt-4">
                        <input type="hidden" id="daid-id" value="">
                        <!-- <input type="hidden" name="btn_del_ongoing" value="1"> -->
                        <button type="button" class="me-2 btn btn-danger btn-del-da">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- modal delete end  -->

    <!-- modal delete start -->
    <div class="modal fade" id="staticDeletedid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">

                <div class="modal-body py-4">
                    <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                    <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                    <p class="text-center text-info">You won't be able to revert this!</p>

                    <div class="text-center mt-4">
                        <input type="hidden" id="did-id" value="">
                        <!-- <input type="hidden" name="btn_del_ongoing" value="1"> -->
                        <button type="button" class="me-2 btn btn-danger btn-del-did">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- modal delete end  -->

    <!-- Modal Assign post production start-->
    <div class="modal fade" id="assign-postproduction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalLabel">Assign Employee</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        <!--<span aria-hidden="true">&times;</span>-->
                    </button>
                </div>
                <form action="ajax/ongoing-project-ajax.php" method="post" id="form-app">
                    <div class="modal-body">
                        <input type="hidden" name="deliverablea_name" id="deliverablea_id" value="">

                        <div class="modal-pp-assign" id="modal-app">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-outline-gray" data-dismiss="modal">Close</button>-->
                        <input type="button" id="deliverable-assign-btn" class="btn btn-outline-warning btn-round px-4" value="Assign" name="assign_manager">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal post production end-->

    <!-- modal add deliverables item start -->
    <div class="modal fade" id="staticAddid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Deliverable item</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>

                <div class="modal-body py-4">
                    <input type="text" class="form-control mb-4" id="input-new-deliverable" required value="">

                    <div class="text-center mt-4">
                        <input type="hidden" id="add-did-id" value="">
                        <!-- <input type="hidden" name="btn_del_ongoing" value="1"> -->
                        <button type="button" class="me-2 btn btn-primary save-did-item">Save it!</button>
                        <!--<button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>-->
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- modal add deliverables item end  -->


    <!-- Modal new billing start-->
    <div class="modal fade" id="modal-billing" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-8">
                                <h5 class="modal-title text-light" id="exampleModalLabel"><?php echo $clName; ?></h5>
                                <h6 class='text-info'><?php echo $clAddress; ?> </h6>
                            </div>
                            <div class="col-4 text-end"><button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <!--<span aria-hidden="true">&times;</span>-->
                                </button></div>
                        </div>


                    </div>
                </div>

                <div class="modal-body">
                    <h5 class="text-light" id="total-amount"></h5>
                    <h6 class="text-danger" id="pending-amount"></h6>

                    <div class="modal-bill mt-3" id="modal-bill-body">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-outline-gray" data-bs-dismiss="modal">Close</button>-->
                    <input type="button" id="save-bill-btn" class="btn btn-outline-warning btn-round px-4" value="Save">
                </div>

            </div>
        </div>
    </div>
    <!-- Modal new billing end-->

    <!-- Modal view bill start-->
    <div class="modal fade" id="modalViewBill" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header py-3">
                    <h5 class="modal-title" id="exampleModalLabel">Bill</h5>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalViewBillBody">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $clName ?>" data-email="<?php echo $clEmail ?>" class="btn btn-danger billsendmail">Mail</button>
                    <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $clName ?>" data-phone="<?php echo $clPhone ?>" class="btn btn-success billsendwhatsapp"><i class="fab fa-whatsapp fa-lg"></i></button>
                    <a target="_blank" href="<?php echo $url . "/billing.php?bp=" . $prid ?>" type="button" class="btn btn-primary billsendprint">Print</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view bill end-->

    <!-- modal set complete start -->
    <form action="<?php echo $url . "/admin/projects/actions/update-project.php"; ?>" method="post">
        <div class="modal fade" id="staticCompleteProject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content card-bg">

                    <div class="modal-body py-4">
                        <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                        <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                        <p class="text-center text-info">You won't be able to revert this!</p>

                        <div class="text-center mt-4">
                            <input type="hidden" name="projectidd" value="<?php echo $prid ?>">
                            <input type="hidden" name="btn-set-complete" value="1">
                            <button type="submit" class="me-2 btn btn-outline-success">Yes, set complete!</button>
                            <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </form>
    <!-- modal set complete end  -->


    <!-- Modal view quotation start-->
    <div class="modal fade" id="modalViewQuotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header py-3">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Quotation</h5>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalViewQuotationBody">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer pt-1 pb-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="<?php echo $url . "/admin/projects/quotation-edit.php?prid=" . $prid ?>" type="button" class="btn btn-secondary me-4"> <i class="fas fa-pencil-alt"></i> Edit</a>
                    <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $clName ?>" data-email="<?php echo $clEmail ?>" class="btn btn-danger sendmail">Mail</button>
                    <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $clName ?>" data-phone="<?php echo $clPhone ?>" class="btn btn-success sendwhatsapp"><i class="fab fa-whatsapp fa-lg"></i></button>
                    <a target="_blank" href="<?php echo $url . "/quotation-print.php?qproject=" . $prid ?>" type="button" class="btn btn-primary">Print</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view quotation end-->

    <div class="modal fade" id="ndeliverableModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="eventNameId">Event Type</h5>
                    <button type="button" class="btn-close btn-secondary btn-closee" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body border border-dark">
                        <p class="text-white">Deliverables</p>
                        <div id="editor1" class="border border-secondary" style="min-height: 100px;">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="eventid" id="eventidd" value="">
                    <button type="button" class="btn btn-secondary btn-closee" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="save_deliverable">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="nsuggestionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-info">Suggestions</h5>
                    <button type="button" class="btn-close btn-secondary btn-closee" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class=" border border-dark">

                        <div id="editors1" class="border border-secondary" style="min-height: 140px;">
                            <?php echo $suggestion; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="eventid" id="eventidd" value="">
                    <button type="button" class="btn btn-secondary btn-closee" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="save_suggestions">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal delete  -->
    <div class="modal fade" id="modal-new-d" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">

                <div class="modal-body py-4">
                    <h3 class="text-center text-warning my-3">Add New Deliverable</h3>

                    <form action="<?php echo $url . "/admin/projects/ajax/quotation-ajax.php" ?>" method="post">
                        <div class="text-center mt-4">
                            <input type="text" name="delivtext" id="deliv-text-n" class="form-control my-4">
                            <input type="hidden" name="delivid" id="seventid-id" value="">
                            <input type="hidden" name="btn_snew_deliverable" value="1">
                            <button type="submit" class="me-2 btn btn-warning btn-firsthash save-n-d">Save</button>
                            <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- modal delete  -->
    <div class="modal fade" id="modal-delete-d" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">

                <div class="modal-body py-4">
                    <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                    <h4 class="text-center text-info mt-3">Are you sure?</h4>
                    <p class="text-center text-light">You won't be able to revert this!</p>
                    <form action="<?php echo $url . "/admin/projects/ajax/quotation-ajax.php" ?>" method="post">
                        <div class="text-center mt-4">
                            <input type="hidden" name="delivid" id="delivevent-id" value="">
                            <input type="hidden" name="btn_del_devent" value="1">
                            <button type="submit" class="me-2 btn btn-warning btn-firsthash">Yes, delete it!</button>
                            <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-d" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content card-bg">

                <div class="modal-body py-4">
                    <h3 class="text-center text-warning my-3">Update Deliverable</h3>

                    <form action="<?php echo $url . "/admin/projects/ajax/quotation-ajax.php" ?>" method="post">
                        <div class="text-center mt-4">
                            <input type="text" name="delivtext" id="deliv-text" class="form-control my-4">
                            <input type="hidden" name="delivid" id="udeliv-id" value="">
                            <input type="hidden" name="btn_supdate_deliverable" value="1">
                            <button type="submit" class="me-2 btn btn-warning btn-firsthash">Save</button>
                            <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        //btn view quotation
        $("#view-quotation-btn").click(function(e) {
            e.preventDefault();
            var prid = "<?php echo $prid ?>";
            $("#modalViewQuotation").modal('show');
            $.ajax({
                type: "post",
                url: "ajax/quotation-ajax.php",
                data: {
                    quotationviewajax: true,
                    projid: prid,
                },
                success: function(response) {
                    $("#modalViewQuotationBody").html(response);
                }
            });

        });

        //delete da
        $(".btn-del-da").click(function(e) {
            e.preventDefault();
            var daidd = $('#daid-id').val();
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    deletedassign: true,
                    daidd: daidd,
                },
                success: function(response) {
                    if (response == 0) {
                        alert('something went wrong');
                        $('#staticDeleteda').modal('hide');
                    } else {
                        $('#staticDeleteda').modal('hide');
                        deliverablestab();
                    }
                }
            });


        });

        //delete did
        $(".btn-del-did").click(function(e) {
            e.preventDefault();
            var didd = $('#did-id').val();
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    deleteddelivassign: true,
                    didd: didd,
                },
                success: function(response) {
                    if (response == 0) {
                        alert('something went wrong');
                        $('#staticDeletedid').modal('hide');
                    } else {
                        $('#staticDeletedid').modal('hide');
                        deliverablestab();
                    }
                }
            });


        });

        //save deliverable item
        $(".save-did-item").click(function(e) {
            e.preventDefault();
            var deliverable = $('#add-did-id').val();
            // alert(deliverable);
            var textinput = $("#input-new-deliverable").val();
            var projectid = "<?php echo $prid ?>";
            if (textinput == "") {
                alert("please enter deliverable name");
            } else {
                $.ajax({
                    type: "post",
                    url: "ajax/ongoing-project-ajax.php",
                    data: {
                        addnewdeliverable: true,
                        textinput: textinput,
                        deliverable: deliverable,
                        prid: projectid,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('not added, something went wrong');
                        } else {
                            deliverablestab();
                            $('#staticAddid').modal('hide');
                            $("#input-new-deliverable").val("");
                        }
                    }
                });
            }

        });

        //assign button
        $("#deliverable-assign-btn").click(function(e) {
            e.preventDefault();
            var selectUserVal = $("#select-deliverables option:selected").val();
            var delassid = $("#deliverablea_id").val();

            if (selectUserVal == 0) {
                alert('please choose employee');
            } else {

                $.ajax({
                    type: "post",
                    url: "ajax/ongoing-project-ajax.php",
                    data: {
                        assignemployeedel: true,
                        employeeid: selectUserVal,
                        delassid: delassid,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        } else {
                            $('#assign-postproduction').modal('hide');
                            deliverablestab();
                        }
                    }
                });
            }

        });


        //function for deliverables
        function deliverablestab() {
            var projectid = "<?php echo $prid ?>";
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    deliverabletab: true,
                    projectid: projectid,
                },
                success: function(response) {
                    $("#deliverable-body").html(response);

                    $('.deliverable-date').change(function() {
                        var daid = $(this).closest('td').find('input[name="daitemid"]').val();
                        var ddate = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                dadate: true,
                                daid: daid,
                                ddate: ddate,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert("something went wrong");
                                }
                            }
                        });

                    });

                    $(".btn-setc").click(function(e) {
                        e.preventDefault();
                        var daid = $(this).closest('td').find('input[name="daid"]').val();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                statusda: true,
                                daid: daid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                } else {
                                    deliverablestab();
                                }

                            }
                        });

                    });

                    $(".btn-setic").click(function(e) {
                        e.preventDefault();
                        var daid = $(this).closest('td').find('input[name="daid"]').val();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                statusdai: true,
                                daid: daid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                } else {
                                    deliverablestab();
                                }

                            }
                        });

                    });


                    $(".milestone-due-date").change(function(e) {
                        e.preventDefault();
                        var delivid = $(this).closest('h5').find('input[name="did"]').val();
                        var dddate = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                deliverableduedate: true,
                                delivid: delivid,
                                dddate: dddate,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert("something went wrong");
                                }
                            }
                        });
                    });

                    $(".btn-delete-da").click(function(e) {
                        e.preventDefault();
                        var daidc = $(this).closest('td').find('input[name="daidc"]').val();
                        $('#daid-id').val(daidc);
                        $('#staticDeleteda').modal('show');
                    });

                    $(".del-did-btn").click(function(e) {
                        e.preventDefault();
                        var did = $(this).closest('h5').find('input[name="did"]').val();
                        $('#did-id').val(did);

                        $('#staticDeletedid').modal('show');
                    });

                    $(".add-did-btn").click(function(e) {
                        e.preventDefault();
                        var didd = $(this).closest('h5').find('input[name="daidd"]').val();
                        $('#add-did-id').val(didd);

                        $('#staticAddid').modal('show');

                    });

                    $(".assign-da-user").click(function(e) {
                        e.preventDefault();
                        var assigndid = $(this).closest('td').find('input[name="delid"]').val();
                        // alert(assigndid);
                        $("#deliverablea_id").val(assigndid);
                        $('#assign-postproduction').modal('show');

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                assigndeliverablep: true,
                                assigndid: assigndid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                } else {
                                    $("#modal-app").html(response);
                                }
                            }
                        });

                    });

                }
            });
        }

        //tab deliverable
        $("#tab-deliverable").click(function(e) {
            deliverablestab();

        });


        //function for financial
        function financialtab() {
            var projectid = "<?php echo $prid ?>";
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    financialstabb: true,
                    project: projectid,
                },
                success: function(response) {
                    $("#tab-financials").html(response);

                    //new bill
                    $(".btn-new-bill").click(function(e) {
                        e.preventDefault();
                        var projectid = "<?php echo $prid ?>";
                        var tamount = $('#total-amt').html();
                        var pamount = $('#pending-amt').html();
                        $('#modal-billing').modal('show');
                        $('#total-amount').html(tamount);
                        $('#pending-amount').html(pamount);

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                newbillbody: true,
                                projectid: projectid,
                            },
                            success: function(response) {
                                $("#modal-bill-body").html(response);
                            }
                        });

                    });

                    //view bill
                    $(".btn-view-bill").click(function(e) {
                        e.preventDefault();
                        var billid = $(this).attr('data-id');
                        var quotee = "<?php echo $url . "/billing.php?bp=" ?>" + billid;
                        $(".billsendwhatsapp").attr('data-id', billid);
                        $(".billsendmail").attr('data-id', billid);
                        $(".billsendprint").attr('href', quotee);
                        $("#modalViewBill").modal('show');
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                viewbillbody: true,
                                billid: billid,
                            },
                            success: function(response) {
                                $("#modalViewBillBody").html(response);
                            }
                        });

                    });

                }
            });

        }
        //tab financials
        $("#tab-financial").click(function(e) {
            financialtab();

        });

        //button save bill
        $("#save-bill-btn").click(function(e) {
            e.preventDefault();
            var selectBillType = $("#billingControlSelect1 option:selected").val();
            var amnt = $('#input-amount').val();
            var project = "<?php echo $prid ?>";

            if (selectBillType == 5) {
                alert("please select bill type");
            } else if (amnt == "") {
                alert("please enter amount");
            } else {
                $.ajax({
                    type: "post",
                    url: "ajax/ongoing-project-ajax.php",
                    data: {
                        savenewbill: true,
                        projectid: project,
                        billtype: selectBillType,
                        amount: amnt,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        } else {
                            $('#modal-billing').modal('hide');
                            financialtab();
                            var billid = response;
                            $("#modalViewBill").modal('show');
                            $.ajax({
                                type: "post",
                                url: "ajax/ongoing-project-ajax.php",
                                data: {
                                    viewbillbody: true,
                                    billid: billid,
                                },
                                success: function(response) {
                                    $("#modalViewBillBody").html(response);
                                }
                            });
                        }

                    }
                });
            }

        });



        //assign manager
        $("#as-manager").on('click', function(e) {
            e.preventDefault();
            var prid = "<?php echo $prid; ?>";
            $("#assign-manager").modal('show');

            $("#input-assign-btn").attr("disabled", "");
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    assignM: true,
                    prid: prid,
                },

                success: function(response) {
                    $("#modal-am").html(response);
                    $("#input-assign-btn").removeAttr('disabled');

                }
            });

        });

        //edit manager
        $("#updateManager").on('click', function(e) {
            e.preventDefault();
            $("#updateManagerModal").modal("show");

            var prid = "<?php echo $prid; ?>";
            var amid = $("#am_manager").val();
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    manager: true,
                    m_prid: prid,
                    am_id: amid,

                },

                success: function(response) {
                    $("#modal-manager").html(response);
                }
            });

        });

        $("#save_deliverable").click(function(e) {
            e.preventDefault();
            var deli = $(".ql-editor").html();
            var eventid = $("#eventidd").val();
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    deliverables: true,
                    deltext: deli,
                    eventid: eventid,
                },
                success: function(response) {
                    if (response == 0) {
                        alert('something went wrong');
                    } else {
                        $("#ndeliverableModal").modal('hide');
                        $("#deliev" + eventid).html(deli);
                    }
                }
            });


        });

        $("#save_suggestions").click(function(e) {
            e.preventDefault();
            var deli = $("#editors1 > .ql-editor").html();

            var prid = "<?php echo $prid ?>";
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    suggestions: true,
                    sugtext: deli,
                    prid: prid,
                },
                success: function(response) {
                    if (response == 0) {
                        alert('something went wrong');
                    } else {
                        $("#nsuggestionModal").modal('hide');

                    }
                }
            });


        });

        //tab occasion
        $("#tab-occasion").click(function() {
            var projectid = "<?php echo $prid ?>";
            $("#tab-occasions").html('<div class="d-flex justify-content-center">\
                                <div class="spinner-border text-secondary" role="status">\
                                    <span class="sr-only">Loading...</span>\
                                </div>\
                            </div>');
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    eventsload: true,
                    proj: projectid,
                },
                success: function(response) {
                    $("#tab-occasions").html(response);

                    //edit deliverables
                    $(".btn-editd").click(function(e) {
                        e.preventDefault();
                        var did = $(this).attr('data-id');
                        var deid = "deliev" + did;
                        $("#eventidd").val(did);
                        var detext = $("#" + deid).html();
                        var dataname = $(this).attr('data-name');
                        $("#eventNameId").html(dataname);
                        $("#ndeliverableModal").modal('show');
                        // $("#editor1").html(dval);
                        const delta = quill.clipboard.convert(detext);
                        quill.setContents(delta, 'silent');

                    });

                    $(".btn-edit-ditem").click(function(e) {
                        e.preventDefault();
                        var eid = $(this).attr('data-id');
                        var text = $(this).attr('data-text');
                        $("#udeliv-id").val(eid);
                        $("#deliv-text").val(text);
                        $("#modal-edit-d").modal('show');
                    });
                    $(".btn-del-ditem").click(function(e) {
                        e.preventDefault();
                        var eid = $(this).attr('data-id');
                        $("#delivevent-id").val(eid);
                        $("#modal-delete-d").modal('show');
                    });
                    $(".btn-add-deliverables").click(function(e) {
                        e.preventDefault();
                        var eid = $(this).attr('data-id');
                        $("#seventid-id").val(eid);
                        $("#modal-new-d").modal('show');
                    });

                    //deliverable
                    $(".deliverable-e").change(function(e) {
                        e.preventDefault();
                        var textdel = $(this).val();
                        var eventid = $(this).closest('div').attr('data-event');
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                deliverablesEvent: true,
                                textdel: textdel,
                                eventid: eventid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                }
                            }
                        });

                    });

                    //comment
                    $(".comment-e").change(function(e) {
                        e.preventDefault();
                        var textcomment = $(this).val();
                        var eventid = $(this).closest('div').attr('data-event');

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                commentEvent: true,
                                textcomment: textcomment,
                                eventid: eventid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                }
                            }
                        });

                    });

                    //gathering
                    $(".g-input").change(function(e) {
                        e.preventDefault();
                        var ginput = $(this).val();
                        var eventid = $(this).closest('p').find('input[name="eventid"]').val();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                gatheringc: true,
                                ginput: ginput,
                                eventid: eventid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                }
                            }
                        });

                    });

                    //venue
                    $(".ven-input").change(function(e) {
                        e.preventDefault();
                        var vinput = $(this).val();
                        var eventid = $(this).closest('p').find('input[name="eventid"]').val();

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                editvenuec: true,
                                evinput: vinput,
                                eventid: eventid,
                            },
                            success: function(response) {
                                if (response == 0) {
                                    alert('something went wrong');
                                }
                            }
                        });

                    });

                    //employee assign to
                    $(".btn-assignto").click(function(e) {
                        e.preventDefault();
                        var rid = $(this).closest('td').find('input[name="erid"]').val();
                        var evntid = $(this).closest('td').find('input[name="evntid"]').val();
                        var evntdate = $(this).closest('td').find('input[name="evntdate"]').val();
                        var emptype = $(this).closest('td').find('input[name="emptype"]').val();
                        var classnm = ".cdiv" + rid;
                        
                        console.log("Event Date: "+evntdate);
                        console.log("rid: "+rid);
                        console.log("Event id: "+evntid);
                        console.log("Employee type: "+emptype);
                        console.log("Class name: "+classnm);

                        $("#name-classs").val(classnm);


                        $('#assign-employee').modal('show');

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                assignreq: true,
                                rid: rid,
                                evntid: evntid,
                                evntdate: evntdate,
                                emptype: emptype,
                            },
                            success: function(response) {
                                $("#modal-ae").html(response);
                            }
                        });


                    });


                }
            });

            $("#form-req").submit(function(e) {
                e.preventDefault();

            });

        });
        $("#assign-employee-btn").click(function(e) {
            e.preventDefault();
            var classs = $('#name-classs').val();

            var form = $("#form-req");
            var actionUrl = form.attr('action');

            $.ajax({
                type: "post",
                url: actionUrl,
                data: form.serialize(),
                success: function(response) {
                    $(classs).html(response);
                    $('#assign-employee').modal('hide');
                    // alert(response)
                }
            });

            return false;
        });


        $("#btn-layout").click(function(e) {
            e.preventDefault();
            $("#modalLayout").modal('show');
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

        $("#form-layout").submit(function(e) {
            e.preventDefault();

        });


        $("#save-layout").click(function(e) {
            e.preventDefault();
            var forml = $("#form-layout");
            var actionurl = forml.attr('action');
            $.ajax({
                type: "post",
                url: actionurl,
                data: forml.serialize(),
                success: function(response) {
                    if (response == 1) {
                        $('#modalLayout').modal('hide');
                        $('.litem-add-class').remove();
                        $("#pp_title").val("");
                        $("#layout-item1").val("");
                    } else {
                        alert('Oops! something went wrong');
                    }

                    // alert(response)
                }
            });

            return false;

        });


        $("#btn-import").click(function(e) {
            e.preventDefault();
            $("#updateViewDeliverables").modal('show');

            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    layoutdeliverable: true,
                },
                success: function(response) {
                    $("#modal-deliverable").html(response);


                    $(".btn-select-layout").click(function(e) {
                        e.preventDefault();
                        var layoutid = $(this).attr('data-layout');
                        var layoutname = $(this).attr('data-layoutname');
                        var project = "<?php echo $prid ?>";

                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                deliverablesave: true,
                                project: project,
                                layout: layoutid,
                                layoutname: layoutname,
                            },
                            success: function(response) {
                                if (response == 1) {

                                    $("#updateViewDeliverables").modal('hide');
                                    deliverablestab();
                                } else {
                                    alert('not saved, something went wrong');
                                    $("#updateViewDeliverables").modal('hide');
                                }
                            }
                        });

                    });


                }
            });

        });

        $("#btn-layout-delete").click(function(e) {
            e.preventDefault();
            $("#deleteViewDeliverables").modal('show');
            $.ajax({
                type: "post",
                url: "ajax/ongoing-project-ajax.php",
                data: {
                    layoutdeliverabledelete: true,
                },
                success: function(response) {
                    $("#modal-deliverable-delete").html(response);


                    $(".btn-del-dlayout").click(function(e) {
                        e.preventDefault();
                        var layoutid = $(this).attr('data-layout');
                        $(this).closest('h5').remove();
                        $.ajax({
                            type: "post",
                            url: "ajax/ongoing-project-ajax.php",
                            data: {
                                deliverabledeletelayout: true,
                                layout: layoutid,
                            },
                            success: function(response) {
                                if (response == 1) {

                                } else {
                                    alert('could not delete, something went wrong');

                                }
                            }
                        });

                    });


                }
            });
        });

        $(".sendwhatsapp").click(function(e) {
            e.preventDefault();
            var eventid = $(this).attr('data-id');

            var name = $(this).attr('data-name');
            var phone = $(this).attr('data-phone');

            var quote = "<?php echo $url . "/quotation-print.php?qproject=" ?>" + eventid;


            var url = "https://wa.me/+91" + phone + "?text=" +
                "Hello, " + name + "%0a" +
                "Download your Quotation from below link:" + "%0a" +
                "" + "%0a" +
                quote;

            window.open(url, '_blank').focus();

        });

        $(".sendmail").click(function(e) {
            e.preventDefault();
            var eventid = $(this).attr('data-id');

            var name = $(this).attr('data-name');
            var email = $(this).attr('data-email');

            var quote = "<?php echo $url . "/quotation-print.php?qproject=" ?>" + eventid;
            var subject = "Download Your Quotation";



            $.ajax({
                type: "post",
                url: "<?php echo $url . "/send-mail.php"; ?>",
                data: {
                    sendemail: true,
                    name: name,
                    email: email,
                    subject: subject,
                    messageurl: quote,
                },
                success: function(response) {

                    if (response == 1) {
                        alert("email sent successful")
                    } else {
                        alert("email not send! something went wrong");
                    }
                }
            });
        });

        $(".billsendwhatsapp").click(function(e) {
            e.preventDefault();
            var eventid = $(this).attr('data-id');

            var name = $(this).attr('data-name');
            var phone = $(this).attr('data-phone');

            var quote = "<?php echo $url . "/billing.php?bp=" ?>" + eventid;


            var url = "https://wa.me/+91" + phone + "?text=" +
                "Hello, " + name + "%0a" +
                "Download your Bill from below link:" + "%0a" +
                "" + "%0a" +
                quote;

            window.open(url, '_blank').focus();

        });

        $(".billsendmail").click(function(e) {
            e.preventDefault();
            var eventid = $(this).attr('data-id');

            var name = $(this).attr('data-name');
            var email = $(this).attr('data-email');

            var quote = "<?php echo $url . "/billing.php?bp=" ?>" + eventid;
            var subject = "Download Your Bill";



            $.ajax({
                type: "post",
                url: "<?php echo $url . "/send-mail.php"; ?>",
                data: {
                    sendemail: true,
                    name: name,
                    email: email,
                    subject: subject,
                    messageurl: quote,
                },
                success: function(response) {

                    if (response == 1) {
                        alert("email sent successful")
                    } else {
                        alert("email not send! something went wrong");
                    }
                }
            });
        });
    </script>
    <script>
        var eee = "#editor1";
        var quill = new Quill(eee, {
            theme: 'snow'
        });

        var eee1 = "#editors1";
        var quill1 = new Quill(eee1, {
            theme: 'snow'
        });
    </script>

<?php
} else {
    echo "<p class='text-center'>no data</p>";
}
include_once('../include/footer.php');
?>