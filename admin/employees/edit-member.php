<?php
define('TITLE', 'Edit Member');
define('PAGE', 'employee');
define('PAGE_T', 'Edit Members');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];

if (isset($_GET['mid'])) {
    $member = $_GET['mid'];

    $sql_profile = "SELECT * FROM users_db WHERE u_id={$member}";
    $res_profile = mysqli_query($con, $sql_profile);
    if (mysqli_num_rows($res_profile) > 0) {

        while ($row_profile = mysqli_fetch_assoc($res_profile)) {
            $uname = $row_profile['user_name'];

            $uphone = $row_profile['user_phone'];
            $uemail = $row_profile['user_email'];
            $poption = $row_profile['payment_option'];
            $utype = $row_profile['user_role'];
            $uaddress = $row_profile['user_address'];
            $pamount = $row_profile['payment_amount'];
        }

        if ($poption == 0) {
            $amnt = $pamount;
        } else {
            $amnt = "0";
        }
    }

    if ($utype == 2) {

        $result_array = array();
        $sql_type2 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$member}";
        $res_type2 = mysqli_query($con, $sql_type2);

        if (mysqli_num_rows($res_type2) > 0) {

            while ($r_type2 = mysqli_fetch_array($res_type2)) {
                $rolee = $r_type2['type_name'];

                array_push($result_array, $rolee);
                // echo "<small class='font-size-8 text-light'>" . $r_type2['type_name'] . ", " . "</small>";
            }
            $List = implode(', ', $result_array);
        } else {
            $List = "n/a";
        }
    }

    ?>
    <div class="container-fluid">
        <div class="card card-body card-bg">
            <div class="card-title">Personal Info</div>
            <div class="row">
                <div class="col-md-6 md-form mb-3">
                    <i class="anticon anticon-user prefix text-info"> <span class="ms-2">name</span> </i>
                    <input type="text" id="fname" class="form-control validate" value="<?php echo $uname; ?>">
                </div>
                <input type="hidden" value="<?php echo $member; ?>" id="uidd">
                <div class="col-md-6 md-form mb-3">
                    <i class="anticon anticon-mail prefix text-info"> <span class="ms-2">email</span></i>
                    <input type="email" id="femail" class="form-control validate" value="<?php echo $uemail; ?>">
                </div>
                <div class="col-md-6 md-form mb-3">
                    <i class="anticon anticon-phone prefix text-info"> <span class="ms-2">phone</span></i>
                    <input type="tel" id="fphone" class="form-control validate" value="<?php echo $uphone; ?>">
                </div>
                <div class="col-md-6 md-form mb-3">
                    <i class="anticon anticon-compass prefix text-info"> <span class="ms-2">address</span></i>
                    <input type="tel" id="faddress" class="form-control validate" value="<?php echo $uaddress; ?>">
                </div>
            </div>
            <p class="text-end"> <button class="btn btn-outline-info btn-sm py-1 px-4" id="updatePersonal">update</button>
            </p>

        </div>
        <?php if ($utype == 2) { ?>
            <div class="card card-body card-bg">

                <div class="text-info col-md-5 d-flex align-items-center mb-3"><span class="text-muted me-3 fw-bold">Type
                    </span>

                    <select class="form-select input-state text-info" aria-label="Default select example" name="empType">
                        <option value="0" class="text-white" <?php if ($poption == 0) {
                            echo "selected";
                        } ?>>In-house</option>
                        <option value="1" class="text-white" <?php if ($poption == 1) {
                            echo "selected";
                        } ?>>Freelancer</option>
                    </select>
                </div>

                <h5 class="text-info"><span class="text-muted me-3">Role :</span>
                    <?php echo $List ?> <button class="btn btn-outline-secondary btn-sm py-1 px-3 ms-4" data-bs-toggle="modal"
                        data-bs-target="#editrole">edit</button>
                </h5>
            </div>
        <?php } ?>

        <div class="card card-body card-bg">
            <div class="card-title">Payment Information</div>
            <?php
            if ($poption == 0) {
                echo "<div class='row g-3 align-items-center mb-4'>
  <div class='col-auto'>
    <label for='msalary' class='col-form-label'>Salary (Rs.)</label>
  </div>
  <div class='col-auto'>
    <input type='number' id='msalary' class='form-control' value='{$amnt}'>
  </div>
</div> <p class='border border-bottom border-dark'></p>";
            } ?>


            <!-- bank card  -->
            <?php
            $sqlBank = "SELECT * FROM payment_information WHERE pi_user_id= {$member}";
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


            <div class="d-flex justify-content-between align-items-center">
                <small class="text-info"><i class="anticon anticon-bank me-2"></i>Payment/Bank Details :</small>
                <div>
                    <?php if ($infoBank == 1) { ?>
                        <button class="btn btn-outline-secondary btn-sm py-1" id="edit-bank" data-bs-toggle="modal"
                            data-bs-target="#modalEditBank"><i class="anticon anticon-form me-2"></i>Edit</button>
                    <?php } else { ?>
                        <button class="btn btn-outline-secondary btn-sm py-1" id="add-bank" data-bs-toggle="modal"
                            data-bs-target="#modalAddBank"><i class="anticon anticon-plus-circle me-2"></i>Add</button>
                    <?php } ?>
                </div>
            </div>
            <?php if ($infoBank == 1) { ?>

                <div class="row">
                    <div class="col-md-6">
                        <small>Bank details:</small>
                        <p class="pb-0 mb-0 mt-2">Bank Name : <span class="ms-2 ">
                                <?php echo $bankName ?>
                            </span></p>
                        <p class="py-0 my-0">Branch Name : <span class="ms-2 ">
                                <?php echo $branchName ?>
                            </span></p>
                        <p class="py-0 my-0">IFSC Code : <span class="ms-2 ">
                                <?php echo $ifscCode ?>
                            </span></p>
                        <p class="py-0 my-0">Account Number : <span class="ms-2 ">
                                <?php echo $accountNumber ?>
                            </span></p>
                        <p class="py-0 my-0">Account Holder : <span class="ms-2 ">
                                <?php echo $accountHolder ?>
                            </span></p>
                        <p class="py-0 my-0">Account Type : <span class="ms-2 ">
                                <?php if ($accountType == 1) {
                                    echo "Saving Account";
                                } elseif ($accountType == 2) {
                                    echo "Current Account";
                                } else {
                                    echo "";
                                } ?>
                            </span></p>
                    </div>
                    <div class="col-md-6">
                        <small>UPI and more:</small>
                        <p class="pb-0 mb-0 mt-2">VPA address : <span class="ms-2 ">
                                <?php echo $vpaAddress ?>
                            </span></p>
                        <p class="py-0 my-0">PhonePay/GooglePay : <span class="ms-2 ">
                                <?php echo $phonePay ?>
                            </span></p>
                    </div>
                </div>
            <?php } else {
                echo "<p class='text-center mx-auto py-4'>please add Bank Details</p>";
            } ?>




        </div>

    </div>



    <!-- Modal -->
    <form action='employee-actions.php' method='post'>
        <div class="modal fade" id="editrole" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content card-bg">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="staticBackdropLabel">Update role</h5>
                        <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <!-- <span aria-hidden="true">&times;</span> -->
                        </button>
                    </div>
                    <div class="modal-body" id="modal-content1">
                        <?php
                        $sql2 = "SELECT * FROM employee_type WHERE cus_id={$cusID} OR cus_id=0";
                        $res2 = mysqli_query($con, $sql2);

                        if (mysqli_num_rows($res2) > 0) {
                            while ($row2 = mysqli_fetch_assoc($res2)) {
                                $emid = $row2['type_id'];
                                $sql1 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$member} AND employee_type.type_id={$emid}";
                                $res1 = mysqli_query($con, $sql1);
                                if (mysqli_num_rows($res1) > 0) {
                                    $check = "checked";
                                } else {
                                    $check = "";
                                }
                                echo "<div class='form-check'>
  <input class='form-check-input' type='checkbox' name='position[]' value='{$row2['type_id']}' id='flexCheckDefault{$row2['type_id']}' {$check}>
  <label class='form-check-label text-warning' for='flexCheckDefault{$row2['type_id']}'>
    {$row2['type_name']}
  </label>
</div>";
                            }
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="update_mtype" value="<?php echo $member; ?>">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- modal add bank -->
    <div class="modal fade bd-example-modal-lg" id="modalAddBank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 text-primary"> <i
                            class="anticon anticon-bank prefix text-primary me-2"></i>Add Bank/Payment Details :</h4>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="employee-actions.php" method="post">
                    <div class="modal-body mx-3">
                        <input type="hidden" value="<?php echo $member; ?>" name="empid">
                        <input type="hidden" name="add_membank" value="1">
                        <div class="row">
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Bank Name</span> </i>
                                <input type="text" name="bank_name" placeholder="bank name" class="form-control validate">
                            </div>

                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Branch Name</span> </i>
                                <input type="text" name="branch_name" placeholder="branch name"
                                    class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">IFSC Code</span> </i>
                                <input type="text" name="ifsc_code" placeholder="IFSC code" class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Account Number</span> </i>
                                <input type="number" name="account_number" placeholder="Account Number"
                                    class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Account Holder</span> </i>
                                <input type="text" name="account_holder" placeholder="Account Holder"
                                    class="form-control validate">
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
                                <input type="text" name="vpa_address" placeholder="vpa address"
                                    class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">PhonePe/GooglePay</span> </i>
                                <input type="number" name="phone_pay" placeholder="PhonePay/GooglePay"
                                    class="form-control validate">
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
    <div class="modal fade bd-example-modal-lg" id="modalEditBank" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 text-info"> <i
                            class="anticon anticon-bank prefix text-info me-2"></i>Bank/Payment Details :</h4>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="employee-actions.php" method="post">
                    <div class="modal-body mx-3">
                        <input type="hidden" value="<?php echo $member; ?>" name="empid1">
                        <input type="hidden" name="update_membank" value="1">
                        <div class="row">
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Bank Name</span> </i>
                                <input type="text" name="bank_name1" value="<?php echo $bankName ?>" placeholder="bank name"
                                    class="form-control validate">
                            </div>

                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Branch Name</span> </i>
                                <input type="text" name="branch_name1" value="<?php echo $branchName ?>"
                                    placeholder="branch name" class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">IFSC Code</span> </i>
                                <input type="text" name="ifsc_code1" value="<?php echo $ifscCode ?>" placeholder="IFSC code"
                                    class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Account Number</span> </i>
                                <input type="number" name="account_number1" value="<?php echo $accountNumber ?>"
                                    placeholder="Account Number" class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Account Holder</span> </i>
                                <input type="text" name="account_holder1" <?php echo $accountHolder ?>
                                    placeholder="Account Holder" class="form-control validate">
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
                                    <option value='2' <?php if ($accountType == 2) { echo "selected";} ?>>Current Account</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3 row">
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">Vpa Address</span> </i>
                                <input type="text" name="vpa_address1" value="<?php echo $vpaAddress ?>"
                                    placeholder="vpa address" class="form-control validate">
                            </div>
                            <div class="col-md-6 md-form mb-3">
                                <i class="anticon prefix text-info"> <span class="ms-2">PhonePe/GooglePay</span> </i>
                                <input type="number" name="phone_pay1" value="<?php echo $phonePay ?>"
                                    placeholder="PhonePay/GooglePay" class="form-control validate">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-secondary btn-tone" type="submit" id="btn-update">update <i
                                class=" anticon anticon-export ml-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $("#msalary").change(function (e) {
            e.preventDefault();
            var salary = $("#msalary").val();
            var uidd = $("#uidd").val();

            $.ajax({
                type: "post",
                url: "employee-actions.php",
                data: {
                    editmsalary: true,
                    salary: salary,
                    uidd: uidd,
                },
                success: function (response) {
                    if (response == 0) {
                        $("#msalary").val(response);
                    }
                }
            });
        });

        $("#updatePersonal").click(function (e) {
            e.preventDefault();
            var uidd = $("#uidd").val();
            var filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            $("#femail").change(function (e) {
                e.preventDefault();
                if (filter.test($("#femail").val())) {
                    $("#femail").removeClass("is-invalid");
                }
            });

            $("#fphone").keyup(function (e) {
                e.preventDefault();
                $("#fphone").removeClass("is-invalid");
            });
            $("#fname").keyup(function (e) {
                e.preventDefault();
                $("#fname").removeClass("is-invalid");
            });
            var fname = $("#fname").val();
            var email = $("#femail").val();
            var phone = $("#fphone").val();
            var address = $("#faddress").val();
            if (fname == "") {
                $("#fullName").addClass("is-invalid");
            } else if (!filter.test(email)) {
                $("#email").addClass("is-invalid");
            } else if (phone == "") {
                $("#phone").addClass("is-invalid");
            } else {
                $.ajax({
                    type: "post",
                    url: "employee-actions.php",
                    data: {
                        updatempersonal: true,
                        uidd: uidd,
                        fname: fname,
                        email: email,
                        phone: phone,
                        address: address,

                    },
                    success: function (response) {
                        if (response == 1) {
                            alert("update successful");
                        } else {
                            alert("something went wrong");
                        }
                    }
                });
            }
        });

        $(".input-state").on("change", function () {
            var feildOption = $(this).val();
            var emp = "<?php echo $member; ?>";
            $.ajax({
                type: "post",
                url: "ajax-action.php",
                data: {
                    changeemployetype: true,
                    feildOption: feildOption,
                    emp: emp,
                },
                success: function (response) {
                    if (response == 0) {
                        alert("something went wrong");
                    }
                }
            });
        });
    </script>



    <?php
} else {
    echo "<p class='text-center'>no data found</p>";
}
include_once('../include/footer.php');
?>