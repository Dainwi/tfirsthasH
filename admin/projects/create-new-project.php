<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('TITLE', 'Create Project');
define('PAGE', 'new-project');
define('PAGE_T', 'New Project');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
?>



<form action="<?php echo $url . "/admin/projects/actions/new-project-action.php"; ?>" method="post">
    <div class="container-fluid">
        <!-- client info start -->

        <?php
        if (isset($_SESSION['msg'])) {
            echo "<div class='alert alert-danger light-alert text-center'>{$_SESSION["msg"]}</div>";
            unset($_SESSION['msg']);
        }


        ?>

        <div class="card card-bg">
            <div class="card-body pt-2">
                <p class="text-info">Client Info :</p>

                <div class="col-md-4 mb-3">
                    <small>Client Type</small>
                    <select class="form-control" name="select-client" id="clientType">
                        <option value="0">Choose....</option>
                        <option value="1">New Client</option>
                        <option value="2">Existing Client</option>
                    </select>
                </div>

                <div class="row" id="client-info">

                </div>

            </div>
        </div>
        <!-- client info end -->


        <!-- project info start -->
        <div class="card card-bg">
            <div class="card-body pt-2">
                <p class="text-info">Project Info :</p>

                <div class="row">
                    <div class="col-md-4">
                        <small>Project Name</small>
                        <input type="text" class="form-control" name="project_name" required placeholder="project name*">
                    </div>
                    <div class="col-md-4">
                        <small>Event</small>
                        <select class="form-control" name="select-event" id="eventType">

                            <option value="0" selected>Choose Event...</option>
                            <?php
                            $sqlet = "SELECT * FROM event_type WHERE cus_id = {$_SESSION["user_id"]} OR cus_id=0";
                            $reset = mysqli_query($con, $sqlet);
                            if (mysqli_num_rows($reset) > 0) {
                                while ($rowet = mysqli_fetch_assoc($reset)) {
                                    $etitle = $rowet['et_title'];
                                    echo "<option value='{$etitle}'>{$etitle}</option>";
                                }
                            }
                            ?>

                            <option value="1">+ Add new</option>

                        </select>
                    </div>

                </div>

            </div>
        </div>
        <!-- project info end -->

        <!-- event start -->
        <div class="card card-bg">
            <div class="card-body pt-2">
                <p class="text-info">Function(s) : <button class="ms-4 btn btn-sm btn-outline-primary py-1 px-3 btn-round add-more-event"> <i class="fas fa-plus"></i> New Function</button></p>

                <div class="add-new">
                    <div class="add-form mb-1">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control nevent-select" name="event_name[]">

                                        <option value="0" selected>Choose function....</option>
                                        <?php
                                        $sqlel = "SELECT * FROM events_list WHERE cus_id = {$_SESSION['user_id']} OR cus_id=0";
                                        $resel = mysqli_query($con, $sqlel);
                                        if (mysqli_num_rows($resel) > 0) {
                                            while ($rowel = mysqli_fetch_assoc($resel)) {
                                                $eltitle = $rowel['el_title'];
                                                echo "<option value='{$eltitle}'>{$eltitle}</option>";
                                            }
                                        }
                                        ?>

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="add_venue[]" class="form-control" id="formGroupExampleInput2" placeholder="Event Venue">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-affix m-b-10">
                                        <i class="prefix-icon anticon anticon-calendar"></i>
                                        <input type="date" name="date[]" class="form-control" placeholder="select date" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <button class="btn btn-outline-warning btn-sm py-1 px-4 add_more mt-2">Add More</button>
            </div>
        </div>
        <!-- event end -->
        <p class="text-end"><input type="hidden" name="submit-project" value="1">
            <button type="submit" class="btn btn-outline-warning btn-round px-5" id="btn-save" disabled>Save</button>
        </p>
    </div>
</form>


<!-- <button id="show-success">modal</button> -->

<!-- Modal -->
<div class="modal fade" id="searchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="staticBackdropLabel">Search Client</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body px-5">
                    <input type="text" class="form-control mb-4" id="search-input" value="" placeholder="Search client">
                </div>
                <div class="card-body" id="items-body">

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="neweventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="staticBackdropLabel">Event Type</h5>
                <button type="button" class="btn-close btn-secondary btn-closee" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body px-5">
                    <input type="text" class="form-control mb-4" id="newevent-type" value="" placeholder="event type">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-closee" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="save_eventype">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="eventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="staticBackdropLabel">New Function</h5>
                <button type="button" class="btn-close btn-secondary btn-closee1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body px-5">
                    <input type="text" class="form-control mb-4" id="newevent-title" value="" placeholder="function name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-closee1" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="save_event">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <p class="text-center py-4"><i class='fas fa-check-circle text-success' style='font-size:5rem'></i></p>
            <h4 class="text-center text-white pb-4">new event added</h4>
        </div>
    </div>
</div>

<div class="invisible" id="all-events-list">
    <select class="form-control nevent-select" name="event_name[]">

        <option value="0" selected>Choose event....</option>
        <?php
        $sqlel = "SELECT * FROM events_list WHERE cus_id = {$_SESSION['user_id']} OR cus_id=0";
        $resel = mysqli_query($con, $sqlel);
        if (mysqli_num_rows($resel) > 0) {
            while ($rowel = mysqli_fetch_assoc($resel)) {
                $eltitle = $rowel['el_title'];
                echo "<option value='{$eltitle}'>{$eltitle}</option>";
            }
        }
        ?>



    </select>
</div>


<script>
    // $("#show-success").click(function(e) {
    //     e.preventDefault();
    //     $("#successModal").modal('show');
    //     setTimeout(function() {
    //         $('#successModal').modal('hide')
    //     }, 1000);
    // });
    $(document).ready(function() {
        $("#save_event").click(function(e) {
            e.preventDefault();
            var evtitle = $("#newevent-title").val();

            var neweoption = "<option value='" + evtitle + "'>" + evtitle + "</option>";


            $.ajax({
                type: "post",
                url: "<?php echo $url . "/admin/projects/ajax/new-project-ajax.php"; ?>",
                data: {
                    neweventtitle: true,
                    evtitle: evtitle,
                },
                success: function(response) {
                    if (response == 1) {
                        $("#newevent-title").val("");
                        $(".nevent-select").append(neweoption);
                        $('#eventModal').modal('hide');
                        $("#successModal").modal('show');
                        setTimeout(function() {
                            $('#successModal').modal('hide')
                        }, 1000);
                    } else {
                        alert('something went wrong');
                    }
                }
            });


        });



        $("#clientType").on("change", function() {
            var feildOption = $("#clientType option:selected").val();

            if (feildOption == 0) {
                $("#client-info").html("");

            } else if (feildOption == 1) {
                $("#client-info").html('<div class="col-md-4 mt-2"> <div class="form-floating">\
                    <input type="text" class="form-control" id="floatingName" name="name" required placeholder="*Full Name">\
                <label for="floatingName">*Full Name</label></div></div>\
                <div class="col-md-4 mt-2"> <div class="form-floating">\
                    <input type="number" id="floatingPhone" class="form-control" name="phone" required placeholder="*Phone No.">\
                <label for="floatingPhone">*Phone No.</label></div></div>\
                <div class="col-md-4 mt-2"> <div class="form-floating">\
                    <input type="email" id="floatingEmail" class="form-control" name="email" required placeholder="*Email">\
               <label for="floatingEmail">*Email</label></div> </div>\
                <div class="col-md-4 mt-2"> <div class="form-floating">\
                    <input type="address" id="floatingAddress" class="form-control" name="address" required placeholder="*Address">\
                <label for="floatingAdd">*Address</label></div></div>');

            } else {
                $("#client-info").html('');
                $('#searchModal').modal('show');
            }

        });

        $("#eventType").on("change", function() {
            var pType1 = $("#eventType option:selected").val();

            if (pType1 == 0) {
                $("#event-other").html("");
                $("#btn-save").attr('disabled', true);

            } else if (pType1 == 1) {
                $("#btn-save").attr('disabled', false);
                $('#neweventModal').modal('show');
            } else {
                $("#btn-save").attr('disabled', false);
                $("#event-other").html("");

            }
        });

        $(".add-more-event").click(function(e) {
            e.preventDefault();
            $('#eventModal').modal('show');
        });

        // $(".nevent-select").on("change", function() {
        //     var event = $(this).val();

        //     // var pType1 = $("#eventType option:selected").val();

        //     if (event == 0) {

        //     } else if (event == 1) {
        //         $(this).val('0')
        //         $('#eventModal').modal('show');
        //     }
        // });

        $(".btn-closee").click(function(e) {
            e.preventDefault();
            $("#eventType").val('0');
            $('#neweventModal').modal('hide');
        });






        $("#save_eventype").click(function(e) {
            e.preventDefault();
            var ettile = $("#newevent-type").val();

            var newoption = "<option value='" + ettile + "'>" + ettile + "</option>";


            $.ajax({
                type: "post",
                url: "<?php echo $url . "/admin/projects/ajax/new-project-ajax.php"; ?>",
                data: {
                    newetitle: true,
                    etitle: ettile,
                },
                success: function(response) {
                    if (response == 1) {

                        $("#newevent-type").val("");
                        $("#eventType option[value='1']").remove();
                        $("#eventType").append(newoption);
                        $("#eventType").append("<option value='1'>Add New</option>");
                        $('#neweventModal').modal('hide');
                        $("#eventType").val(ettile);
                    } else {
                        alert('something went wrong');
                    }
                }
            });

        });


        $('#search-input').keyup(function(e) {
            e.preventDefault();
            var searchv = $(this).val();
            $.ajax({
                type: "post",
                url: "<?php echo $url . "/admin/projects/ajax/new-project-ajax.php"; ?>",
                data: {
                    searchclient: true,
                    searchvalue: searchv,
                },

                success: function(response) {
                    $("#items-body").html(response);

                    $(".btn-add").click(function(e) {
                        e.preventDefault();
                        var cid = $(this).closest('div').find('input[name="cid"]').val();
                        var cname = $(this).closest('div').find('input[name="cname"]').val();
                        var cemail = $(this).closest('div').find('input[name="cemail"]').val();
                        var cphone = $(this).closest('div').find('input[name="cphone"]').val();
                        var caddress = $(this).closest('div').find('input[name="caddress"]').val();


                        var htmll = '<div class="col-md-4 mt-2">\
                            <input type="hidden" value="' + cid + '" class="form-control" name="clid"><input type="text" class="form-control" value="' + cname + '" readonly>\
                        </div>\
                        <div class="col-md-4 mt-2">\
                            <input type="number" class="form-control" value="' + cphone + '" readonly>\
                        </div>\
                        <div class="col-md-4 mt-2">\
                            <input type="email" class="form-control" value="' + cemail + '" readonly>\
                        </div>\
                        <div class="col-md-4 mt-2">\
                            <input type="address" class="form-control" value="' + caddress + '" readonly>\
                        </div>';

                        $("#client-info").html(htmll);
                        $('#searchModal').modal('hide');

                    });
                }
            });

        });

        //event script
        $(document).on("click", ".remove-btn", function() {
            $(this).closest(".add-form").remove();
        });
        $(".add_more").click(function(e) {
            e.preventDefault();
            var elist = $("#all-events-list").html();
            $(".add-new").append(
                '<div class="add-form mb-1"> <div class="row">\
                <div class="col-md-4">\
                <div class="form-group">' + elist + ' </div>\
                </div>\
                <div class="col-md-4">\
            <div class="form-group">\
                <input type="text" name="add_venue[]" class="form-control" id="formGroupExampleInput2" placeholder="Event Venue">\
            </div>\
            </div>\
            <div class="col-md-3">\
            <div class="form-group">\
            <div class="input-affix m-b-10">\
                <i class="prefix-icon anticon anticon-calendar"></i>\
                <input type="date" name="date[]" class="form-control" placeholder="select date" required>\
            </div>\
        </div>\
            </div>\
            <div class="col-md-1"><button class="btn btn-sm btn-danger py-1 px-3 mt-2 remove-btn"><i class="fas fa-trash-alt"></i></button></div>\
        </div></div>'
            );
        });

    });
</script>


<?php
include_once('../include/footer.php');
?>