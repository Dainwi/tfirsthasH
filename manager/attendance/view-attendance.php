<?php
define('TITLE', 'View Attendance');
define('PAGE', 'attendance');
define('PAGE_T', 'Attendance');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$today = date("d-m-Y");
$date = date("Y-m-d");

$user = $_GET['user'];
$sql = "SELECT user_name, u_id,user_phone,user_email,user_photo FROM users_db WHERE u_id={$user}";
$res = mysqli_query($con, $sql);
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $name = $row['user_name'];
        $phone = $row['user_phone'];
        $photo = $row['user_photo'];
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-5 border border-dark card-bg rounded py-2 shadow px-3 d-flex align-items-center justify-content-start">
            <div class="avatar avatar-image" style="height: 65px; width: 65px;">
                <img src="<?php

                            if ($photo == 0) {
                                echo $url . "/assets/images/avatars/profile-image.png";
                            } else {
                                echo $url . "/assets/images/profile/" . $photo;
                            }
                            ?>" alt="" class="img-fluid w-100">
            </div>
            <div class="ms-4">
                <h4 class="text-uppercase me-2 mb-0 pb-0"><?php echo $name ?></h4>
                <p class="mt-0 pt-0"> [<i class="anticon anticon-phone"></i><?php echo $phone ?>]</p>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-6 container-fluid border border-dark rounded py-2 card-bg shadow round px-3">
            <div class="row">
                <div class="col-md-6 col-7">
                    <small>select date</small>
                    <div class="input-group mb-3">


                        <button type='button' class="input-group-text btn-secondary btn-sub"><i class="fas fa-minus"></i></button>

                        <input type="text" class="fw-bold form-control text-center" id="year-input" value="<?php echo date("Y") ?>" readonly>

                        <button type='button' class="input-group-text btn-secondary btn-add"><i class="fas fa-plus"></i></button>

                    </div>
                </div>


                <div class="col-5">
                    <small>select month</small>
                    <div class="input-group">


                        <?php
                        $selected_month = date('m'); //current month

                        echo '<select class="form-control" id="month" name="month">' . "\n";
                        for ($i_month = 1; $i_month <= 12; $i_month++) {
                            $selected = ($selected_month == $i_month ? ' selected' : '');
                            echo '<option value="' . $i_month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $i_month, 1)) . '</option>' . "\n";
                        }
                        echo '</select> </div>' . "\n";
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow card-bg round mt-3">
        <div class="card-body">
            <div class=" table-responsive">
                <table class="table ">
                    <thead>
                        <tr class="border-bottom">
                            <th scope="col" class="text-light text-center font-size-12">Date</th>
                            <th scope="col" class="text-light text-center font-size-12">Time In</th>
                            <th scope="col" class="text-light text-center font-size-12">Time Out</th>
                            <th scope="col" class="text-light text-center font-size-12">Status</th>

                        </tr>
                    </thead>
                    <tbody id="t-body">
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>



    <script>
        $(document).ready(function() {


            var year2 = parseInt($("#year-input").val());
            var month2 = $('#month option:selected').val();
            var userid = <?php echo $user; ?>;
            $.ajax({
                type: "post",
                url: "load-data.php",
                data: {
                    dataload: true,
                    userId: userid,
                    year: year2,
                    month: month2,

                },
                success: function(response) {
                    $("#t-body").html(response);
                }
            });


            $(" .btn-sub").click(function(e) {
                e.preventDefault();
                var year = parseInt($("#year-input").val());
                var yyy = year - 1;
                $("#year-input").val(yyy);
                var month = $('#month option:selected').val();
                var userid = <?php echo $user; ?>;

                $("#t-body").html('<tr>\
                            <td colspan="3" class="text-center">\
                                <div class="d-flex justify-content-center">\
                                    <div class="spinner-border" role="status">\
                                        <span class="sr-only">Loading...</span>\
                                    </div>\
                                </div>\
                            </td>\
                        </tr>');

                $.ajax({
                    type: "post",
                    url: "load-data.php",
                    data: {
                        dataload: true,
                        userId: userid,
                        year: yyy,
                        month: month,

                    },
                    success: function(response) {
                        $("#t-body").html(response);
                    }
                });
            });
            $(".btn-add").click(function(e) {
                e.preventDefault();
                var year1 = parseInt($("#year-input").val());
                var yyy = year1 + 1;
                $("#year-input").val(yyy);
                var month = $('#month option:selected').val();
                var userid = <?php echo $user; ?>;

                $("#t-body").html('<tr>\
                            <td colspan="3" class="text-center">\
                                <div class="d-flex justify-content-center">\
                                    <div class="spinner-border" role="status">\
                                        <span class="sr-only">Loading...</span>\
                                    </div>\
                                </div>\
                            </td>\
                        </tr>');
                $.ajax({
                    type: "post",
                    url: "load-data.php",
                    data: {
                        dataload: true,
                        userId: userid,
                        year: yyy,
                        month: month,

                    },
                    success: function(response) {
                        $("#t-body").html(response);
                    }
                });
            });


            $('#month').on('change', function() {
                var yyy = parseInt($("#year-input").val());
                var month = $('#month option:selected').val();
                var userid = <?php echo $user; ?>;
                $("#t-body").html('<tr>\
                            <td colspan="3" class="text-center">\
                                <div class="d-flex justify-content-center">\
                                    <div class="spinner-border" role="status">\
                                        <span class="sr-only">Loading...</span>\
                                    </div>\
                                </div>\
                            </td>\
                        </tr>');
                $.ajax({
                    type: "post",
                    url: "load-data.php",
                    data: {
                        dataload: true,
                        userId: userid,
                        year: yyy,
                        month: month,

                    },
                    success: function(response) {
                        $("#t-body").html(response);
                    }
                });

            });

        });
    </script>


    <?php
    include_once('../include/footer.php');
    ?>