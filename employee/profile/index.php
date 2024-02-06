<?php
define('TITLE', 'Profile Page');
define('PAGE', 'profile');
define('PAGE_T', 'Profile');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$sql_profile = "SELECT * FROM users_db WHERE u_id={$employee_id}";
$res_profile = mysqli_query($con, $sql_profile);
if (mysqli_num_rows($res_profile) > 0) {
    while ($row_profile = mysqli_fetch_assoc($res_profile)) {
        $uname = $row_profile['user_name'];
        $username = $row_profile['user_username'];
        $uphone = $row_profile['user_phone'];
        $uemail = $row_profile['user_email'];
        $uphoto = $row_profile['user_photo'];
        $utype = $row_profile['user_role'];
        $uaddress = $row_profile['user_address'];
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
                                            ?>" alt="" class="img-fluid w-75" style="width: 100px;">
                            </div>
                            <button class="mx-auto mt-4 btn btn-secondary btn-tone pt-2 px-4" id="edit-photo">
                                <i class="anticon anticon-picture me-2"></i> edit photo
                            </button>
                        </div>
                        <div class="text-center text-sm-left m-v-15 p-l-30">
                            <h2 class="m-b-5 text-light"><?php echo $uname ?></h2>
                            <p class="text-secondary font-size-13"><?php echo $username ?></p>
                            <p class="text-info m-b-20">
                                <?php if ($utype == 1) {
                                    echo "manager";
                                } else {
                                    $sqlt = "SELECT * FROM employee_position LEFT JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE employee_position.ep_user_id={$employee_id}";
                                    $rest = mysqli_query($con, $sqlt);
                                    if (mysqli_num_rows($rest) > 0) {
                                        while ($rowt = mysqli_fetch_assoc($rest)) {
                                            echo $rowt['type_name'] . ",";
                                        }
                                    }
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="d-md-block d-none border-left col-1"></div>
                        <div class="col">
                            <ul class="list-unstyled m-t-10">
                                <li class="row">
                                    <p class="col-sm-3 col-3  text-primary m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                        <span>Email </span>
                                    </p>
                                    <p class="col "> : <?php echo $uemail ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-3 col-3  text-primary m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                        <span>Phone </span>
                                    </p>
                                    <p class="col "> : <?php echo $uphone ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-3 col-3  text-primary m-b-5">
                                        <i class="m-r-10 text-primary anticon anticon-compass"></i>
                                        <span>Address </span>
                                    </p>
                                    <p class="col "> : <?php echo $uaddress ?></p>
                                </li>
                            </ul>

                            <div class="d-flex">
                                <button class="ml-auto btn btn-secondary btn-tone pt-2 px-4" data-bs-toggle="modal" data-bs-target="#modalEditForm">
                                    <i class="fas fa-pencil-alt me-2"></i> edit
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- bank card  -->
    <?php
    $sqlBank = "SELECT * FROM payment_information WHERE pi_user_id= {$employee_id}";
    $resBank = mysqli_query($con, $sqlBank);
    if (mysqli_num_rows($resBank) > 0) {
        $infoBank = 1;
        while ($rowBank = mysqli_fetch_assoc($resBank)) {
            $bankName = $rowBank['pi_bank_name'];
            $branchName = $rowBank['pi_bank_branch'];
            $ifscCode = $rowBank['pi_bank_ifsc'];
            $accountNumber = $rowBank['pi_bank_account'];
            $accountHolder = $rowBank['pi_bank_holder'];
            $accountType = $rowBank['pi_account_type'];
            $vpaAddress = $rowBank['pi_vpa'];
            $phonePay = $rowBank['pi_phonepay'];
        }
    } else {
        $infoBank = 0;
    }

    ?>

    <div class="card rounded card-bg">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <p class="card-title text-info"><i class="anticon anticon-bank me-2"></i>Payment/Bank Details :</p>
                <div> <?php if ($infoBank == 1) { ?>
                        <button class="btn btn-sm btn-secondary btn-tone" id="edit-bank" data-bs-toggle="modal" data-bs-target="#modalEditBank"><i class="anticon anticon-form me-2"></i>Edit</button>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-secondary btn-tone" id="add-bank" data-bs-toggle="modal" data-bs-target="#modalAddBank"><i class="anticon anticon-plus-circle me-2"></i>Add</button>
                    <?php } ?>
                </div>
            </div>
            <?php if ($infoBank == 1) {  ?>

                <div class="row">
                    <div class="col-md-6">
                        <small>Bank details:</small>
                        <p class="pb-0 mb-0 mt-2">Bank Name : <span class="ms-2 "><?php echo $bankName ?></span></p>
                        <p class="py-0 my-0">Branch Name : <span class="ms-2 "><?php echo $branchName ?></span></p>
                        <p class="py-0 my-0">IFSC Code : <span class="ms-2 "><?php echo $ifscCode ?></span></p>
                        <p class="py-0 my-0">Account Number : <span class="ms-2 "><?php echo $accountNumber ?></span></p>
                        <p class="py-0 my-0">Account Holder : <span class="ms-2 "><?php echo $accountHolder ?></span></p>
                        <p class="py-0 my-0">Account Type : <span class="ms-2 "><?php if ($accountType == 1) {
                                                                                    echo "Saving Account";
                                                                                } elseif ($accountType == 2) {
                                                                                    echo "Current Account";
                                                                                } else {
                                                                                    echo "";
                                                                                } ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <small>UPI and more:</small>
                        <p class="pb-0 mb-0 mt-2">VPA address : <span class="ms-2 "><?php echo $vpaAddress ?></span></p>
                        <p class="py-0 my-0">PhonePay/GooglePay : <span class="ms-2 "><?php echo $phonePay ?></span></p>
                    </div>
                </div>
            <?php } else {
                echo "<p class='text-center mx-auto py-4'>please add Bank Details</p>";
            } ?>

        </div>
    </div>
    <!-- <div class="card card-bg rounded">
        <div class="card-header">
            <h4 class="card-title text-warning">Change Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="ajax-profile.php">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="" for="oldPassword">Old Password:</label>
                        <input type="password" class="form-control" name="old-password" required id="oldPassword" placeholder="Old Password">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="" for="newPassword">New Password:</label>
                        <input type="password" class="form-control" name="new-password" required id="newPassword" placeholder="New Password">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="" for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirm-password" required id="confirmPassword" placeholder="Confirm Password">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="hidden" name="admin-id" value="<?php echo $employee_id ?>">
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
    </div> -->

</div>
<!-- modal  -->
<div class="modal fade" id="modalEditPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 text-warning">Edit Profile Picture</h4>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" id="uploadFormImage" method="post">
                <div class="modal-body mx-3">
                    <img src="" class="rounded img-fluid mx-auto d-block d-none" id="frame" width="200px">
                    <div class="custom-file mt-3">
                        <input class="custom-file-input" type="file" name="img" id="formFile" onchange="preview()">
                        <label class="custom-file-label text-light" for="formFile">Choose Image</label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-outline-secondary" id="btn-photo-update">update <i class=" anticon anticon-export ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal  -->
<div class="modal fade" id="modalEditForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 text-warning">Edit Profile</h4>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="md-form mb-3">
                    <i class="anticon anticon-user prefix text-info"> <span class="ms-2">name</span> </i>
                    <input type="text" id="fname" class="form-control validate" value="<?php echo $uname; ?>">
                </div>
                <input type="hidden" value="<?php echo $employee_id; ?>" id="uidd">
                <div class="md-form mb-3">
                    <i class="anticon anticon-mail prefix text-info"> <span class="ms-2">email</span></i>
                    <input type="email" id="femail" class="form-control validate" value="<?php echo $uemail; ?>">
                </div>
                <div class="md-form mb-3">
                    <i class="anticon anticon-phone prefix text-info"> <span class="ms-2">phone</span></i>
                    <input type="tel" id="fphone" class="form-control validate" value="<?php echo $uphone; ?>">
                </div>
                <div class="md-form mb-3">
                    <i class="anticon anticon-compass prefix text-info"> <span class="ms-2">address</span></i>
                    <input type="tel" id="faddress" class="form-control validate" value="<?php echo $uaddress; ?>">
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-outline-secondary" id="btn-update">update <i class="fas fa-export ms-1"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- modal add bank -->
<div class="modal fade bd-example-modal-lg" id="modalAddBank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 text-primary"> <i class="anticon anticon-bank prefix text-primary me-2"></i>Add Bank/Payment Details :</h4>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update-profile.php" method="post">
                <div class="modal-body mx-3">
                    <input type="hidden" value="<?php echo $employee_id; ?>" name="empid">
                    <input type="hidden" name="add_bank" value="1">
                    <div class="row">
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Bank Name</span> </i>
                            <input type="text" name="bank_name" placeholder="bank name" class="form-control validate">
                        </div>

                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Branch Name</span> </i>
                            <input type="text" name="branch_name" placeholder="branch name" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">IFSC Code</span> </i>
                            <input type="text" name="ifsc_code" placeholder="IFSC code" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Account Number</span> </i>
                            <input type="number" name="account_number" placeholder="Account Number" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Account Holder</span> </i>
                            <input type="text" name="account_holder" placeholder="Account Holder" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form form-group mb-3">
                            <i class="anticon prefix text-info invisible"> <span class="ms-2">choose type</span> </i>
                            <select class='form-control' name='account_type'>
                                <option value='0' disabled selected>choose account type</option>
                                <option value='1'>Saving Account</option>
                                <option value='2'>Current Account</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 row">
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Vpa Address</span> </i>
                            <input type="text" name="vpa_address" placeholder="vpa address" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">PhonePe/GooglePay</span> </i>
                            <input type="number" name="phone_pay" placeholder="PhonePay/GooglePay" class="form-control validate">
                        </div>
                    </div>


                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-secondary btn-tone" type="submit" id="btn-update">submit <i class=" anticon anticon-export ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- modal update bank -->
<div class="modal fade bd-example-modal-lg" id="modalEditBank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content card-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 text-info"> <i class="anticon anticon-bank prefix text-info me-2"></i>Bank/Payment Details :</h4>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update-profile.php" method="post">
                <div class="modal-body mx-3">
                    <input type="hidden" value="<?php echo $employee_id; ?>" name="empid1">
                    <input type="hidden" name="update_bank" value="1">
                    <div class="row">
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Bank Name</span> </i>
                            <input type="text" name="bank_name1" value="<?php echo $bankName ?>" placeholder="bank name" class="form-control validate">
                        </div>

                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Branch Name</span> </i>
                            <input type="text" name="branch_name1" value="<?php echo $branchName ?>" placeholder="branch name" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">IFSC Code</span> </i>
                            <input type="text" name="ifsc_code1" value="<?php echo $ifscCode ?>" placeholder="IFSC code" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Account Number</span> </i>
                            <input type="number" name="account_number1" value="<?php echo $accountNumber ?>" placeholder="Account Number" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Account Holder</span> </i>
                            <input type="text" name="account_holder1" <?php echo $accountHolder ?> placeholder="Account Holder" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form form-group mb-3">
                            <i class="anticon prefix text-info invisible"> <span class="ms-2">choose type</span> </i>
                            <select class='form-control' name='account_type1'>
                                <option value='0' <?php if ($accountType == 0) {
                                                        echo "selected";
                                                    } ?>>Choose Account Type</option>
                                <option value='1' <?php if ($accountType == 1) {
                                                        echo "selected";
                                                    } ?>>Saving Account</option>
                                <option value='2' <?php if ($accountType == 2) {
                                                        echo "selected";
                                                    } ?>>Current Account</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 row">
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">Vpa Address</span> </i>
                            <input type="text" name="vpa_address1" value="<?php echo $vpaAddress ?>" placeholder="vpa address" class="form-control validate">
                        </div>
                        <div class="col-md-6 md-form mb-3">
                            <i class="anticon prefix text-info"> <span class="ms-2">PhonePe/GooglePay</span> </i>
                            <input type="number" name="phone_pay1" value="<?php echo $phonePay ?>" placeholder="PhonePay/GooglePay" class="form-control validate">
                        </div>
                    </div>


                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-secondary btn-tone" type="submit" id="btn-update">update <i class=" anticon anticon-export ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.10/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $("#btn-update").click(function(e) {
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
                success: function(response) {
                    if (response == 1) {
                        $("#modalEditForm").modal("hide");
                        Swal.fire({
                            icon: 'success',
                            text: 'Update Successful',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false

                        });
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);

                    } else {
                        alert("update failed")
                    }
                }
            });

        });

        //update profilepic
        $("#edit-photo").click(function(e) {
            e.preventDefault();
            $("#modalEditPhoto").modal("show");
            var uid = $("#uidd").val();

            $("#uploadFormImage").submit(function(e) {
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
                    success: function(response) {
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
        $("#confirmPassword").keyup(function(e) {
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
include_once("../include/footer.php");
?>