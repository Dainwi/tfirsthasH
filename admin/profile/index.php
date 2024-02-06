<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('TITLE', 'Admin Profile');
define('PAGE', 'dashboard');
define('PAGE_T', 'Profile');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$sql_profile = "SELECT * FROM admin WHERE c_id={$admin_id}";
$res_profile = mysqli_query($con, $sql_profile);
if (mysqli_num_rows($res_profile) > 0) {
    while ($row_profile = mysqli_fetch_assoc($res_profile)) {
        $uname = $row_profile['c_name'];
        $uphone = $row_profile['c_phone'];
        $uemail = $row_profile['c_email'];
        $uphoto = $row_profile['c_photo'];
        $uaddress = $row_profile['address'];
    }
}
?>


<div class="container">
    <div class="card card-bg">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-md-flex align-items-center">
                        <div class="text-center text-sm-left ">
                            <div class="avatar avatar-image d-block" style="width: 150px; height:150px">
                                <img src="<?php

                                if ($uphoto == '0') {
                                    echo $url . "/assets/images/avatars/profile-image.png";
                                } else {
                                    echo $url . "/assets/images/profile/" . $uphoto;
                                }
                                ?>" alt="" class="img-fluid w-75">
                            </div>
                            <button class="mx-auto mt-4 btn btn-secondary btn-tone pt-2 px-4" id="edit-photo">
                                <i class="anticon anticon-picture mr-2"></i> edit photo
                            </button>
                        </div>
                        <div class="text-center text-sm-left m-v-15 p-l-30">
                            <h2 class="m-b-5">
                                <?php echo $uname ?>
                            </h2>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="d-md-block d-none border-left col-1"></div>
                        <div class="col">
                            <ul class="list-unstyled m-t-10">
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                        <span>Email: </span>
                                    </p>
                                    <p class="col font-weight-semibold">
                                        <?php echo $uemail ?>
                                    </p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                        <span>Phone: </span>
                                    </p>
                                    <p class="col font-weight-semibold">
                                        <?php echo $uphone ?>
                                    </p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-5 font-weight-semibold text-dark m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-compass"></i>
                                        <span>Address: </span>
                                    </p>
                                    <p class="col font-weight-semibold">
                                        <?php echo $uaddress ?>
                                    </p>
                                </li>
                            </ul>

                            <div class="d-flex">
                                <button class="ml-auto btn btn-secondary btn-tone pt-2 px-4" data-bs-toggle="modal"
                                    data-bs-target="#modalEditForm">
                                    <i class="anticon anticon-form mr-2"></i> edit
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-bg">
        <div class="card-header">
            <h4 class="card-title">Change Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="ajax-profile.php">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-semibold" for="oldPassword">Old Password:</label>
                        <input type="password" class="form-control" name="old-password" required id="oldPassword"
                            placeholder="Old Password">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-semibold" for="newPassword">New Password:</label>
                        <input type="password" class="form-control" name="new-password" required id="newPassword"
                            placeholder="New Password">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-semibold" for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirm-password" required
                            id="confirmPassword" placeholder="Confirm Password">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="hidden" name="admin-id" value="<?php echo $admin_id ?>">
                        <button type="submit" class="btn btn-secondary mt-4" id="pass-btn" disabled>Change</button>
                    </div>
                </div>
                <?php if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger mt-3 alert-dismissible fade show'>
    {$_SESSION["error"]}
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
</div>";
                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success mt-3 alert-dismissible fade show'>
    {$_SESSION["success"]}
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
</div>";
                    unset($_SESSION['success']);
                }
                ?>
            </form>
        </div>
    </div>
</div>

<!-- modal  -->
<div class="modal fade" id="modalEditPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Edit Profile Picture</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" id="uploadFormImage" method="post">
                <div class="modal-body mx-3">
                    <img src="" class="rounded img-fluid mx-auto d-block d-none" id="frame" width="200px">
                    <div class="custom-file mt-3">
                        <input class="custom-file-input form-control" type="file" name="img" id="formFile"
                            onchange="preview()">
                        <label class="custom-file-label form-label" for="formFile">Choose Image</label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary btn-tone" id="btn-photo-update">update <i
                            class=" anticon anticon-export ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal  -->
<div class="modal fade" id="modalEditForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Edit Profile</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="md-form mb-3">
                    <i class="anticon anticon-user prefix text-gray"> <span class="ml-2">name</span> </i>
                    <input type="text" id="fname" class="form-control validate" value="<?php echo $uname; ?>">
                </div>
                <input type="hidden" value="<?php echo $admin_id; ?>" id="uidd">
                <div class="md-form mb-3">
                    <i class="anticon anticon-mail prefix text-gray"> <span class="ml-2">email</span></i>
                    <input type="email" id="femail" class="form-control validate" value="<?php echo $uemail; ?>">
                </div>
                <div class="md-form mb-3">
                    <i class="anticon anticon-phone prefix text-gray"> <span class="ml-2">phone</span></i>
                    <input type="tel" id="fphone" class="form-control validate" value="<?php echo $uphone; ?>">
                </div>
                <div class="md-form mb-3">
                    <i class="anticon anticon-compass prefix text-gray"> <span class="ml-2">address</span></i>
                    <input type="tel" id="faddress" class="form-control validate" value="<?php echo $uaddress; ?>">
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-secondary btn-tone" id="btn-update">update <i
                        class=" anticon anticon-export ml-1"></i></button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.10/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        $("#btn-update").click(function (e) {
            e.preventDefault();
            var uname = $("#fname").val();
            var uemail = $("#femail").val();
            var uphone = $("#fphone").val();
            var uidd = $("#uidd").val();
            var uaddress = $("#faddress").val();

            $.ajax({
                type: "post",
                url: "ajax-profile.php",
                data: {
                    updatepro: true,
                    name: uname,
                    email: uemail,
                    phone: uphone,
                    address: uaddress,
                    userid: uidd,

                },
                success: function (response) {
                    if (response == 1) {
                        $("#modalEditForm").modal("hide");
                        Swal.fire({
                            icon: 'success',
                            text: 'Update Successful',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false

                        });
                        window.setTimeout(function () {
                            location.reload();
                        }, 1500);

                    } else {
                        alert("update failed")
                    }
                }
            });

        });

        //update profilepic
        $("#edit-photo").click(function (e) {
            e.preventDefault();
            $("#modalEditPhoto").modal("show");
            var uid = $("#uidd").val();

            $("#uploadFormImage").submit(function (e) {
                e.preventDefault();
                var form = new FormData(this);
                form.append('imgUpdate', true);
                form.append('userid', uid);

                $.ajax({
                    type: "POST",
                    url: "ajax-profile.php",
                    data: form,
                    // dataType:'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        if (response != 1) {
                            $('#formFile').val("");
                            $('#frame').addClass('d-none');
                            frame.src = "";
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: response,

                            });

                        } else {
                            $("#modalEditPhoto").modal("hide");
                            $('#formFile').val("");
                            $('#frame').addClass('d-none');
                            frame.src = "";
                            location.reload();
                        }


                    }
                });

            });


        });

        $("#confirmPassword").keyup(function (e) {
            var npass = $("#newPassword").val();
            var cpass = $(this).val();

            if (cpass === npass) {
                $(this).removeClass('is-invalid');
                $("#pass-btn").removeAttr('disabled');
            } else {
                $(this).addClass('is-invalid');

                $("#pass-btn").attr('disabled', true);

            }

        });

    });
</script>
<script>
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
        $('#frame').removeClass('d-none');

    }
</script>

<?php
include_once('../include/footer.php');
?>