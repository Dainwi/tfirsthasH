<?php
define('TITLE', 'Projects');
define('PAGE', 'pending-project');
define('PAGE_T', 'Quotation');
include_once('../include/header.php');

include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
if (isset($_GET['prid'])) {
    $date = date("Y-m-d");
    $prid = $_GET['prid'];

    $sqlq = "SELECT * FROM quotation_db WHERE q_project_id ={$prid}";
    $resq = mysqli_query($con, $sqlq);
    if (mysqli_num_rows($resq) > 0) {
        while ($rowq = mysqli_fetch_assoc($resq)) {
            $q_id = $rowq['q_id'];
            $q_date = $rowq['q_date'];
            $q_photobook = $rowq['q_photobook'];
            $extra_deliverables = $rowq['extra_deliverables'];
            $package_cost = $rowq['q_package_cost'];
            $addon_cost = $rowq['q_addons_cost'];
            $grand_total = $rowq['grand_total'];
        }
    }

    $sql15 = "SELECT add_price FROM addons_db WHERE add_quotation_id ={$q_id}";
    $res15 = mysqli_query($con, $sql15);
    if ($res15) {
        $addPrice = 0;
        while ($row15 = mysqli_fetch_assoc($res15)) {
            $addPrice += $row15['add_price'];
        }
        $total_price = $package_cost + $addPrice;


        $sql16 = "UPDATE quotation_db SET grand_total='{$total_price}',q_addons_cost='{$addPrice}' WHERE q_id={$q_id}";
        $res16 = mysqli_query($con, $sql16);
    }

    $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$prid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cname = $row['c_name'];
            $caddress = $row['c_address'];
            $event = $row['project_event'];
            $cemail = $row['c_email'];
        }
    }
?>

    <div class="card card-bg">
        <div class="card-body">
            <div class="mt-2 row">
                <div class="col-md-6 col-7 inline-block">

                    <h4 class="ps-2 text-info">Quotation To:</h4>
                    <address class="ps-2 mt-2">
                        <span class="font-weight-semibold text-light"><?php echo $cname ?></span><br>
                        <span class="text-light"><?php echo $caddress ?></span><br>
                        <span></span>
                    </address>
                </div>
                <div class="col-md-6 col-5 text-end">
                    <h3 class="w-100 text-warning text-right">QUOTATION</h3>
                    <div class="text-dark text-uppercase d-inline-block mt-2">
                        <span class="font-weight-semibold text-light">Date :</span>
                        <div class="ms-2 text-light float-end"><?php echo $q_date ?></div>
                    </div>

                </div>
            </div>

            <div class="inline-block ps-2">
                <span class="font-weight-semibold text-light">Event : <?php echo $event ?></span>
                <br><br>
                <span class="font-weight-semibold text-info">Event Details :</span>
            </div>

            <div class="mt-2">
                <div class="table-responsive">
                    <?php
                    $sql1 = "SELECT * FROM events_db WHERE event_pr_id = {$prid}";
                    $res1 = mysqli_query($con, $sql1);
                    if (mysqli_num_rows($res1) > 0) {

                    ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-warning">Dates</th>
                                    <th class="text-warning">Event and Venue</th>
                                    <th class="text-warning">Gathering</th>
                                    <th class="text-warning">Requirements</th>
                                    <th class="text-warning">Deliverables</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row1 = mysqli_fetch_assoc($res1)) {
                                    $eve = strtotime($row1['event_date']);
                                    $eventdate = date("d/m/Y", $eve);
                                    $eid = $row1['event_id'];
                                ?>
                                    <tr>
                                        <th class="text-light"><?php echo $eventdate ?></th>
                                        <td class="text-light"><?php echo $row1['event_name'] . " | " . $row1['event_venue']; ?></td>
                                        <td class="text-light">
                                            <input type="hidden" name="evnt_id" value="<?php echo $row1['event_id'] ?>">
                                            <input type="number" class="form-control g-input" style="max-width: 90px;" name="gathering" value="<?php echo $row1['event_gathering'] ?>" id="">

                                        </td>
                                        <td class="text-light">
                                            <input type="hidden" name="evnt_id" value="<?php echo $row1['event_id'] ?>">
                                            <input type="hidden" name="evnt_name" value="<?php echo $row1['event_name'] ?>">
                                            <input type="hidden" name="evnt_date" value="<?php echo $row1['event_date'] ?>">
                                            <button class="btn btn-sm btn-outline-secondary px-5 btn-requirement">
                                                <i class="fas fa-pencil-alt"></i></button>
                                            <?php
                                            $sqlr = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type = employee_type.type_id WHERE er_event=$eid";
                                            $resr = mysqli_query($con, $sqlr);
                                            if (mysqli_num_rows($resr) > 0) {
                                                echo "<ul>";
                                                while ($rowr = mysqli_fetch_assoc($resr)) {
                                            ?>

                                                    <li><?php echo $rowr['type_name']; ?></li>


                                            <?php
                                                }
                                                echo "</ul>";
                                            }
                                            ?>

                                        </td>
                                        <td class="text-light">
                                            <div class="form-group">
                                                <input type="hidden" name="evnt_id" value="<?php echo $row1['event_id'] ?>">
                                                <textarea class="form-control textarea-class deliverables" required placeholder="deliverables" rows="4"><?php echo $row1['event_deliverables'] ?></textarea>
                                            </div>
                                        </td>


                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>



        </div>

    </div>

    <div class="mt-1 card card-bg">
        <div class="card-body row">
            <div class="col-md-6">
                <p class="text-info">Photobook</p>
                <textarea class="form-control textarea-class photobook" id="photobook1" placeholder="photobook" rows="3"><?php echo $q_photobook  ?></textarea>
            </div>
            <div class="col-md-6">
                <p class="text-info">Other Deliverable</p>
                <textarea class="form-control textarea-class" id="odeliverable" placeholder="deliverable" rows="3"><?php echo $extra_deliverables  ?></textarea>
            </div>
        </div>
    </div>

    <div class="mt-1 card card-bg">
        <div class="card-body row">
            <div class="col-md-6">
                <p class="text-info">Expense Details : <button type="button" class="btn btn-sm ms-4 btn-outline-warning btn-round py-1" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i>add</button></p>
                <div class="m-l-25">
                    <?php
                    $sql_add = "SELECT * FROM addons_db WHERE add_quotation_id ={$q_id}";
                    $res_add = mysqli_query($con, $sql_add);
                    if (mysqli_num_rows($res_add) > 0) {
                        while ($row_add = mysqli_fetch_assoc($res_add)) {
                            $addid = $row_add['add_id'];
                    ?>
                            <ul>
                                <li class="mt-2 fst-normal text-light"><?php echo $row_add['add_title'] . " (Rs" . $row_add['add_price'] . ")"; ?> <p class="ml-4 d-inline">
                                        <input type="hidden" name="add_id" value="<?php echo $addid ?>">
                                        <!-- <button class="btn btn-icon btn-primary btn-sm btn-tone mr-1 btn-edit-pp">
                                                    <i class="anticon anticon-edit"></i>
                                                </button> -->
                                        <a href="<?php echo $url . "/admin/projects/actions/quotation-action.php?deladdid=" . $addid . "&qid=" . $q_id; ?>" class="btn py-1 btn-outline-danger btn-sm btn-round ms-1">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </p>
                                </li>
                                <ol>
                                    <?php
                                    $sql_add_items = "SELECT * FROM addons_items WHERE addons_id ={$addid}";
                                    $res_add_items = mysqli_query($con, $sql_add_items);
                                    if (mysqli_num_rows($res_add_items) > 0) {
                                        while ($row_add_items = mysqli_fetch_assoc($res_add_items)) {
                                    ?>
                                            <li><?php echo $row_add_items['addons_items_desc'] ?></li>

                                    <?php    }
                                    } ?>
                                </ol>
                            </ul>
                    <?php }
                    } else {
                        echo "<small>no expense added</small>";
                    } ?>
                </div>
            </div>

        </div>
    </div>

    <form action="actions/quotation-action.php" id="form-quotation" method="post">
        <div class="row mt-4 card card-bg card-body">
            <div class="col-sm-12">

                <div class="float-end text-end">
                    <input type="hidden" class="input_addons" value="<?php echo $addPrice ?>">
                    <input type="hidden" name="total_price" class="grand_total_price" value="<?php echo $total_price ?>">
                    <div class="input-group mb-3">

                        <span class="input-group-text">Package cost ₹</span>

                        <input type="number" name="package_value" class="form-control" onchange="keyupFunction()" onkeyup="keyupFunction()" id="package-cost-id" required value="<?php echo $package_cost ?>" aria-label="Amount (to the nearest dollar)">

                    </div>
                    <p>AddOns : <?php echo "Rs " . $addPrice; ?> </p>
                    <hr>
                    <h3><span class="font-weight-semibold text-light">Grand Total : Rs </span> <span class="text-light grand_totall"><?php echo $total_price; ?></span></h3>
                </div>



            </div>
            <div class="col-6">
                <input type="hidden" name="quotation_iddd" id="quotation-id1" value="<?php echo $q_id ?>">
                <input type="hidden" value="1" name="save_quote">
                <input type="button" name="save-quotationp" class="btn btn-secondary px-5" id="save-update-quotation" value="save">
            </div>

        </div>
    </form>

    <!-- Modal -->
    <form action="actions/quotation-action.php" method="post">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content card-bg">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="staticBackdropLabel">Requirements</h5>
                        <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="eventid" id="modalEventid">
                    <div class="modal-body" id="modal-content">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="requirementChange" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning btn-firsthash">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- modal add addons start  -->
    <div class="modal fade" id="modalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="staticBackdropLabel">Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="actions/quotation-action.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="addons_quotation_id" value="<?php echo $q_id ?>">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-2">
                                    <small for="pp_title">Title</small>
                                    <input type="text" required name="add_title" class="form-control" id="add_title" placeholder="title">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <small>Price </small>
                                    <input type="number" required name="add_price" class="form-control" id="add_price" placeholder="Rs. 0">
                                </div>
                            </div>
                        </div>



                        <div class="card p-2">
                            <p><span class="border-bottom border-secondary text-info"> info</span> <span class="float-end">
                                    <button type="button" class="ms-4 btn btn-sm btn-outline-primary btn-round py-1" id="addons_items_btn"><i class="anticon anticon-plus mr-2"></i>add more</button></span></p>
                            <div id="addItems">
                                <div class="row">
                                    <div class="col-11 m-0 pe-0 form-group">
                                        <input type="text" class="pe-0 me-0 form-control" value="" name="add_item_desc[]" placeholder="desc">
                                    </div>
                                    <div class="col-1 m-0 ps-0">

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default mr-3" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning btn-firsthash px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal add addons end  -->

    <!-- Modal view quotation start-->
    <div class="modal fade" id="modalViewQuotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header py-3">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Quotation</h5>
                    <a type="button" class="btn-close btn-secondary" href="<?php echo $url . "/admin/projects/view-pending-project.php?prid=" . $prid ?>" aria-label="Close"></a>
                </div>
                <div class="modal-body" id="modalViewQuotationBody">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer pt-1 pb-3">
                    <a type="button" class="btn btn-secondary" href="<?php echo $url . "/admin/projects/view-pending-project.php?prid=" . $prid ?>">Close</a>
                    <button type="button" class="btn btn-danger">Mail</button>
                    <button type="button" class="btn btn-success"><i class="fab fa-whatsapp fa-lg"></i></button>
                    <button type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view quotation end-->

    <script>
        function viewquotation() {
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
        }

        $("#save-update-quotation").click(function(e) {
            e.preventDefault();

            var totalprice = $('.grand_total_price').val();
            var packagecost = $('#package-cost-id').val();
            var quotationid = $('#quotation-id1').val();

            $.ajax({
                type: "post",
                url: "actions/quotation-action.php",
                data: {
                    savequotationn: true,
                    totalprice: totalprice,
                    packagecost: packagecost,
                    quotationid: quotationid,
                },
                success: function(response) {
                    if (response == 1) {
                        viewquotation();
                    } else if (response == 0) {
                        alert("something went wrong");
                    }

                    // alert(response)
                }
            });



        });


        function keyupFunction() {
            var addon = $(".input_addons").val();
            var package = $("#package-cost-id").val();
            if (package == "") {
                var package = 0;
            }
            var totalG = parseFloat(addon) + parseFloat(package);

            $(".grand_total_price").val(totalG);

            $(".grand_totall").text(totalG);

        }

        $(document).ready(function() {
            $(".g-input").change(function(e) {
                e.preventDefault();
                var input = $(this).val();
                var eventid = $(this).closest('td').find('input[name="evnt_id"]').val();

                $.ajax({
                    type: "post",
                    url: "ajax/quotation-ajax.php",
                    data: {
                        gathering: true,
                        input: input,
                        eventid: eventid,
                    },

                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        }
                    }
                });

            });

            $(".deliverables").change(function(e) {
                e.preventDefault();
                var deli = $(this).val();
                var eventid = $(this).closest('td').find('input[name="evnt_id"]').val();
                $.ajax({
                    type: "post",
                    url: "ajax/quotation-ajax.php",
                    data: {
                        deliverables: true,
                        deltext: deli,
                        eventid: eventid,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        }
                    }
                });


            });

            $(".btn-requirement").click(function(e) {
                e.preventDefault();
                var eventid = $(this).closest('td').find('input[name="evnt_id"]').val();
                var eventname = $(this).closest('td').find('input[name="evnt_name"]').val();
                var eventdate = $(this).closest('td').find('input[name="evnt_date"]').val();

                $("#modalEventid").val(eventid);
                $("#staticBackdropLabel").html(eventname + " <small>[" + eventdate + "]</small>");
                $("#staticBackdrop").modal('show');

                $.ajax({
                    type: "post",
                    url: "ajax/quotation-ajax.php",
                    data: {
                        requirements: true,
                        eid: eventid,
                    },
                    success: function(response) {
                        $("#modal-content").html(response);
                    }
                });

            });


            $("#odeliverable").change(function(e) {
                e.preventDefault();
                var deliverable = $(this).val();
                var qid = "<?php echo $q_id ?>";
                $.ajax({
                    type: "post",
                    url: "ajax/quotation-ajax.php",
                    data: {
                        deliverablesave: true,
                        qid: qid,
                        deliverable: deliverable,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        }
                    }
                });
            });



            $("#photobook1").change(function(e) {
                e.preventDefault();
                var photobook = $(this).val();
                var qid = "<?php echo $q_id ?>";
                $.ajax({
                    type: "post",
                    url: "ajax/quotation-ajax.php",
                    data: {
                        photobooksave: true,
                        qid: qid,
                        photobook: photobook,
                    },
                    success: function(response) {
                        if (response == 0) {
                            alert('something went wrong');
                        }
                    }
                });
            });


            $("#addons_items_btn").on('click', function(e) {
                e.preventDefault()
                $("#addItems").append("<div class='row mt-1'>\
                                <div class='col-11 m-0 pr-0 form-group'>\
                                    <input type='text' class='pr-0 mr-0 form-control' required name='add_item_desc[]' placeholder='desc'></div>\
                                <div class='col-1 m-0 pl-0'>\
                                    <button class='py-1 ms-0 m-0 btn btn-sm btn-icon btn-outline-danger btn-round btn-remove-additem'>\
                                        x\
                                    </button></div></div>");
                $(".btn-remove-additem").on('click', function(e) {
                    e.preventDefault();
                    $(this).closest(".row").remove();

                });
            });

        });
    </script>

<?php
}
include_once('../include/footer.php');
?>