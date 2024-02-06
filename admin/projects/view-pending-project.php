<?php
define('TITLE', 'Projects');
define('PAGE', 'pending-project');
define('PAGE_T', 'View Project');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$prid = $_GET['prid'];
?>

<div class="container mt-3">
    <?php
    if (isset($_SESSION['approve-error'])) {
        echo "<p class='alert alert-warning light-alert text-center'>{$_SESSION["approve-error"]}</p>";
        unset($_SESSION['approve-error']);
    }

    ?>
    <div class="card shadow rounded card-bg">
        <div class="card-body">
            <?php
            if (isset($_GET['prid'])) {
            ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client= clients_db.c_id WHERE project_id={$prid}";
                        $res = mysqli_query($con, $sql);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $project = $row['project_name'];
                                $client_name = $row['c_name'];
                                $phone = $row['c_phone'];
                                $email = $row['c_email'];
                                $event = $row['project_event'];
                                $sdat = date_create($row['project_sdate']);
                                $sdate = date_format($sdat, "d M Y");
                                $ldate = $row['project_ldate'];

                                $ldat = date_create($row['project_ldate']);
                                $ldatee = date_format($ldat, "d M Y");
                                $projstatus = $row['project_status'];
                            }
                            $urlc = $url . "/admin/projects/completed-project-details.php?prid=" . $prid;
                            $urlo = $url . "/admin/projects/view-ongoing-project.php?prid=" . $prid;

                            if ($projstatus == 2) {

                                echo "<script> location.replace('{$urlc}');</script>";
                            } elseif ($projstatus == 1) {

                                echo "<script> location.replace('{$urlo}');</script>";
                            }
                        }

                        ?>

                        <h4 class="card-title mb-4"><?php echo $project ?></h4>
                        <span class="badge rounded-pill bg-info mb-3"><?php echo $event ?></span>
                        <div class="container">
                            <p class="mb-1 text-light"><span class="fw-bold me-3">Name : </span> <?php echo $client_name ?></p>
                            <p class="my-1 text-light"><span class="fw-bold me-3">Phone : </span> <?php echo $phone ?></p>
                            <p class="my-1 text-light"><span class="fw-bold me-3">Email : </span> <?php echo $email ?></p>
                            <p class="my-1 text-light"><span class="fw-bold me-3">Date : </span> <?php if ($ldate == "0000-00-00") {
                                                                                                        echo $sdate;
                                                                                                    } else {
                                                                                                        echo $sdate . " - " . $ldatee;
                                                                                                    }  ?></p>

                        </div>
                        <div class="badge rounded-pill bg-danger mt-4">Pending Approval</div>

                    </div>
                    <div class="col-md-6 border-start border-secondary">

                        <div>
                            <a href="edit-pending-project.php?pid=<?php echo $prid; ?>" class="mt-3 col-5 btn btn-sm btn-secondary px-4"><i class="fas fa-pencil-alt"></i> Edit</a>
                        </div>
                        <div>
                            <a href="" data-id="<?php echo $prid; ?>" class="mt-3 col-5 btn btn-sm btn-secondary px-3 btn-quotationv"><i class="fas fa-file"></i> Quotation</a>
                        </div>
                        <div>
                            <a href="actions/update-project.php?updateprj&prid=<?php echo $prid ?>" class="mt-3 col-5 btn btn-sm btn-success"><i class="fas fa-check-circle"></i> Approve</a>
                        </div>

                        <div>

                            <button class="mt-3 col-5 btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-trash-alt"></i> Delete</button>
                        </div>
                    </div>

                </div>
            <?php } else {
                echo "<p class='text-center mt-4'>nothing found</p>";
            } ?>
        </div>
    </div>

    <div class="card card-bg">
        <div class="card-body">
            <p>events :</p>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="text-info">#</th>
                            <th scope="col" class="text-info">Event name</th>
                            <th scope="col" class="text-info">Date</th>
                            <th scope="col" class="text-info">Event Venue</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql1 = "SELECT * FROM events_db WHERE event_pr_id={$prid}";
                        $res1 = mysqli_query($con, $sql1);

                        if (mysqli_num_rows($res1)) {
                            $ie = 1;
                            while ($row1 = mysqli_fetch_assoc($res1)) {
                                $datt = date_create($row1['event_date']);

                        ?>
                                <tr>
                                    <td class="text-light"><?php echo $ie; ?></td>
                                    <td class="text-light"><?php echo $row1['event_name']; ?></td>
                                    <td class="text-light"><?php echo date_format($datt, "d M Y"); ?></td>
                                    <td class="text-light"><?php echo $row1['event_venue']; ?></td>
                                </tr>

                        <?php
                                $ie++;
                            }
                        }

                        ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
<!-- modal delete  -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg py-3">

            <div class="modal-body">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-info">Are you sure?</h4>
                <p class="text-center text-warning">You won't be able to revert this!</p>
                <form action="actions/update-project.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="prjid" value="<?php echo $prid; ?>">
                        <input type="hidden" name="btn_del_project" value="1">
                        <button type="submit" class="me-2 btn btn-secondary">Yes, delete it!</button>
                        <button type="button" class="ml-2 btn btn-light" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

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
                <a href="" type="button" class="btn btn-secondary me-4 btn-edit-quotation"> <i class="fas fa-pencil-alt"></i> Edit</a>
                <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $client_name ?>" data-email="<?php echo $email ?>" class="btn btn-danger sendmail">Mail</button>
                <button type="button" data-id="<?php echo $prid ?>" data-name="<?php echo $client_name ?>" data-phone="<?php echo $phone ?>" class="btn btn-success sendwhatsapp"><i class="fab fa-whatsapp fa-lg"></i></button>
                <a target="_blank" href="<?php echo $url . "/quotation-print.php?qproject=" . $prid ?>" type="button" class="btn btn-primary">Print</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal view quotation end-->

<script>
    $(".btn-quotationv").click(function(e) {
        e.preventDefault();
        var projid = $(this).attr('data-id');

        var qurl = "<?php echo $url . "/admin/projects/quotation.php?prid=" ?>" + projid;

        $(".btn-edit-quotation").attr("href", qurl);
        $.ajax({
            type: "post",
            url: "<?php echo $url . "/admin/projects/actions/quotation-action.php"; ?>",
            data: {
                quotationpending: true,
                prid: projid,
            },
            success: function(response) {

                if (response == 0) {
                    alert('something went wrong');
                } else if (response == 1) {
                    $("#modalViewQuotation").modal('show');
                    $.ajax({
                        type: "post",
                        url: "ajax/quotation-ajax.php",
                        data: {
                            quotationviewajax: true,
                            projid: projid,
                        },
                        success: function(response) {
                            $("#modalViewQuotationBody").html(response);
                        }
                    });
                } else {
                    window.location.href = (response);
                }
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
</script>



<?php
include_once('../include/footer.php');
?>