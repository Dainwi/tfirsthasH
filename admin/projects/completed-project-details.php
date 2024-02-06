<?php
define('TITLE', 'Completed Projects');
define('PAGE', 'completed-project');
define('PAGE_T', 'Project Details');
include_once('../include/header.php');
?>
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
</style>
<?php



include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID=$_SESSION['user_id'];

$prid = $_GET['prid'];
if (isset($_GET['prid'])) {
    $prid = $_GET['prid'];

    $sql = "SELECT*FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_id={$prid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $event = $row['project_event'];
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
        $urlo = $url . "/admin/projects/view-ongoing-project.php?prid=" . $prid;

        if ($projstatus == 0) {

            echo "<script> location.replace('{$urlp}');</script>";
        } elseif ($projstatus == 1) {

            echo "<script> location.replace('{$urlo}');</script>";
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
                        <span class='text-success'> <small><i class="p-2 fas fa-check"></i></small> Completed</span>
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
                        $prid = mysqli_real_escape_string($con, $prid); // Sanitizing input to prevent SQL Injection

// SQL query to find the manager assigned to a project
$sql_manager = "SELECT * FROM assign_manager_db LEFT JOIN users_db ON assign_manager_db.am_user_id = users_db.u_id WHERE am_project_id = {$prid}";
$res_manager = mysqli_query($con, $sql_manager);

if (mysqli_num_rows($res_manager) > 0) {
    while ($row_manager = mysqli_fetch_assoc($res_manager)) {
        $manager = $row_manager['user_name'];
        $amId = $row_manager['am_id'];
    }
    echo "<input type='hidden' value='{$amId}' id='am_manager'> <a class='mr-2 m-b-5' href='#'>
            <span class='font-size-14 text-primary font-weight-semibold'>$manager</span>
          </a> ";
} else {
    echo "<small class='text-warning'>Not Assigned</small>";
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

                <div class="card" id="deliverable-body">

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
                    <button type="button" class="btn btn-danger">Mail</button>
                    <button type="button" class="btn btn-success"><i class="fab fa-whatsapp fa-lg"></i></button>
                    <button type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view bill end-->

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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger">Mail</button>
                    <button type="button" class="btn btn-success"><i class="fab fa-whatsapp fa-lg"></i></button>
                    <button type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view quotation end-->


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

        //function for deliverables
        function deliverablestab() {
            var projectid = "<?php echo $prid ?>";
            $.ajax({
                type: "post",
                url: "ajax/completed-project-ajax.php",
                data: {
                    deliverabletab: true,
                    projectid: projectid,
                },
                success: function(response) {
                    $("#deliverable-body").html(response);

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
                url: "ajax/completed-project-ajax.php",
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
                            url: "ajax/completed-project-ajax.php",
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
                        $("#modalViewBill").modal('show');
                        $.ajax({
                            type: "post",
                            url: "ajax/completed-project-ajax.php",
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
                url: "ajax/completed-project-ajax.php",
                data: {
                    eventsload: true,
                    proj: projectid,
                },
                success: function(response) {
                    $("#tab-occasions").html(response);

                }
            });

            $("#form-req").submit(function(e) {
                e.preventDefault();

            });

        });


        $("#form-layout").submit(function(e) {
            e.preventDefault();

        });
    </script>

<?php
} else {
    echo "<p class='text-center'>no data</p>";
}
include_once('../include/footer.php');
?>