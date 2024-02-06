<?php
define('TITLE', 'Projects');
define('PAGE', 'pending-project');
define('PAGE_T', 'Pending Projects');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];
?>

<div class="container mt-4">
    <?php
    if (isset($_SESSION['approve-error'])) {
        echo "<p class='alert alert-warning light-alert text-center'>{$_SESSION["approve-error"]}</p>";
        unset($_SESSION['approve-error']);
    }

    ?>
    <div class="row">
        <?php

        $sql_pending = "SELECT*FROM projects_db LEFT JOIN clients_db ON clients_db.c_id=projects_db.project_client WHERE project_status=0 AND projects_db.cus_id = '$cusID' ORDER BY project_id DESC";
        $res_pending = mysqli_query($con, $sql_pending);
        



        $rows = mysqli_num_rows($res_pending);

        if ($rows > 0) {
            while ($row = mysqli_fetch_assoc($res_pending)) {

        ?>
                <div class="col-md-4">
                    <div class="card shadow rounded card-bg">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <small class="text-warning"><?php echo $row['project_event']; ?></small>
                                <div class="card-toolbar">
                                    <a class="text-success mx-2" href="edit-pending-project.php?pid=<?php echo $row['project_id']; ?>">
                                        <i class="fas fa-pencil-alt font-size-20"></i>
                                    </a>
                                    <a class="text-primary mx-2" href="view-pending-project.php?prid=<?php echo $row['project_id']; ?>">
                                        <i class="fas fa-eye font-size-20"></i>
                                    </a>
                                    <a class="text-danger del-pending-pr mx-2" href="<?php echo $row['project_id']; ?>" type="button">
                                        <i class="fas fa-trash-alt font-size-20"></i>
                                    </a>

                                </div>
                            </div>

                            <h5><?php echo $row['project_name']; ?></h5>


                            <div class="row">
                                <div class="col-4">
                                    <small class="text-info"> Client</small>
                                </div>
                                <div class="col-8">
                                    <small>: <?php echo $row['c_name']; ?> </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <small class="text-info"> Mobile</small>
                                </div>
                                <div class="col-8">
                                    <small>: <?php echo $row['c_phone']; ?></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <small class="text-info"> Event Date</small>
                                </div>
                                <div class="col-8">
                                    <small>:
                                        <?php
                                        $sdate = date_create($row['project_sdate']);
                                        $sdt = date_format($sdate, "d M Y");
                                        $ldt1 = date_create($row['project_ldate']);
                                        $ldt = date_format($ldt1, "d M Y");
                                        $ldate = $row['project_ldate'];

                                        if ($ldate == "0000-00-00") {
                                            echo $sdt;
                                        } else {

                                            echo $sdt . " " . "-" . " " . $ldt;
                                        }


                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mt-3 d-flex">
                                <a href="actions/update-project.php?updateprj&prid=<?php echo $row['project_id']; ?>" class="btn btn-sm btn-round btn-outline-success d-inline">approve</a>
                                <a href="#" data-id="<?php echo $row['project_id'] ?>" data-name="<?php echo $row['c_name']; ?>" data-phone="<?php echo $row['c_phone']; ?>" data-email="<?php echo $row['c_email']; ?>" class="ms-auto btn btn-sm btn-outline-info btn-round d-inline btn-quotationv">quotation</a>


                            </div>


                        </div>
                    </div>
                </div>



        <?php
            }
        } else {
            echo "<p class='text-danger text-center mt-4'>No Pending Projects</p>";
        }

        ?>

    </div>
</div>

<!-- modal delete  -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center text-info mt-3">Are you sure?</h4>
                <p class="text-center text-light">You won't be able to revert this!</p>
                <form action="actions/update-project.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="prjid" id="proj-pen" value="">
                        <input type="hidden" name="btn_del_project" value="1">
                        <button type="submit" class="me-2 btn btn-warning btn-firsthash">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-light" data-bs-dismiss="modal">close</button>
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
                <button type="button" data-id="" data-name="" data-email="" class="btn btn-danger sendmail">Mail</button>
                <button type="button" data-id="" data-name="" data-phone="" class="btn btn-success sendwhatsapp"><i class="fab fa-whatsapp fa-lg"></i></button>
                <a target="_blank" href="" type="button" class="btn btn-primary btn-print-quotation">Print</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal view quotation end-->

<script>
    $(".del-pending-pr").on('click', function(e) {
        e.preventDefault();
        var ddd = $(this).attr('href');

        $("#proj-pen").val(ddd);

        $("#staticBackdrop").modal('show');
    });

    $(".btn-quotationv").click(function(e) {
        e.preventDefault();
        var projid = $(this).attr('data-id');
        var cname = $(this).attr('data-name');
        var cphone = $(this).attr('data-phone');
        var cemail = $(this).attr('data-email');

        var qurl = "<?php echo $url . "/admin/projects/quotation.php?prid=" ?>" + projid;
        var purl = "<?php echo $url . "/quotation-print.php?qproject=" ?>" + projid;

        $(".btn-edit-quotation").attr("href", qurl);
        $(".btn-print-quotation").attr("href", purl);
        $(".sendwhatsapp").attr("data-id", projid);
        $(".sendwhatsapp").attr("data-name", cname);
        $(".sendwhatsapp").attr("data-phone", cphone);

        $(".sendmail").attr("data-id", projid);
        $(".sendmail").attr("data-name", cname);
        $(".sendmail").attr("data-email", cemail);
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