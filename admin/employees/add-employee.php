<?php
define('TITLE', 'Add Employee');
define('PAGE', 'add-employee');
define('PAGE_T', 'Add Employee');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID = $_SESSION['user_id'];
?>

<div class="container">
    <div class="card card-bg">
        <div class="card-body">
            <div class="container">
                <form action="<?php echo $url . "/admin/employees/employee-actions.php" ?>" method="post" id="form-employee">
                    <div class="tab-content" id="pills-tabContent">
                        <!-- personal information tab start -->
                        <div class="tab-pane fade show active" id="pills-personal" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="container-fluid">
                                <h5 class="font-weight-semibold text-secondary text-center mb-4">Personal Information :</h5>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="inputState" class="text-muted">Employee Type</label>
                                            <select id="inputState" class="form-select" aria-label="Default select example" name="empType">
                                                <option value="0" selected>Choose...</option>
                                                <option value="1">Manager</option>
                                                <option value="2">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3 d-none" id="role-field">
                                            <label for="choose_role" class="text-muted ml-2">Choose Role</label>
                                            <select class="js-states form-control" tabindex="-1" style="display: none; width: 100%" required name="position[]" multiple="multiple">
                                                <?php
                                                $sql1 = "SELECT * FROM employee_type WHERE cus_id={$cusID}";
                                                $res = mysqli_query($con, $sql1);
                                                if (mysqli_num_rows($res) > 0) {
                                                    while ($row1 = (mysqli_fetch_assoc($res))) {

                                                ?>
                                                        <option value="<?php echo $row1['type_id'] ?>"><?php echo $row1['type_name'] ?></option>

                                                <?php
                                                    }
                                                }

                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <label for="full_name" class="invisible">Full Name</label>
                                        <input name="name" value="" required type="text" class="form-control" id="fullName" placeholder="Full Name">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <label for="user_name" class="invisible">username</label>
                                        <input name="username" value="" required type="text" id="username" class="form-control" placeholder="Username">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <input name="email" value="" required type="email" class="form-control" id="email" placeholder="Email">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <input name="phone" value="" required type="number" class="form-control" id="phone" placeholder="Mobile No.">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <input name="address" value="" required type="text" class="form-control" id="address" placeholder="Address">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <input name="password" required type="password" class="form-control" id="password" placeholder="Password">
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <input name="c_password" required type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password">
                                    </div>

                                </div>

                            </div>
                            <div class="text-center">
                                <button class="d-inline-block btn btn-primary px-4 my-4" type="button" id="next-btn">next <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                        <!-- personal information tab end -->

                        <!-- payment information tab start -->
                        <div class="tab-pane fade" id="pills-payment" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="container-fluid">

                                <h5 class="font-weight-semibold text-secondary text-center mb-4">Payment Information :</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="choose_role" class="text-muted d-block ms-2">Choose Payment Type</label>
                                        <select class="js-states form-control" tabindex="-1" style="display: none; width: 100%" name="payment_type" id="payment-type">
                                            <option value="5" selected>choose type</option>
                                            <option value="0">Salaried</option>
                                            <option value="1">Task Based</option>
                                        </select>
                                        <input type="hidden" name="emp_save" value="1">
                                    </div>

                                    <div class="col-md-6" id="c-selected">

                                    </div>
                                </div>
                                <div class="row" id="bank-info">
                                    <a type="button" class="col-12 text-primary mb-4" onClick="addbank()">+ Add bank account information <span class="text-muted">(optional)</span> </a>


                                </div>
                            </div>
                            <div class="text-center">
                                <button class="d-inline-block btn btn-secondary my-4 btn-prev" id="btn-prev"><i class="fas fa-arrow-left"></i> previous</button>
                                <input class="d-inline-block btn btn-warning btn-firsthash px-4 my-4" type="submit" id="submit-btn" value="save">
                            </div>
                        </div>
                        <!-- payment information tab end -->

                    </div>
                </form>
            </div>
            <?php
            if (isset($_SESSION['user_exist'])) {
                echo "<script>alert('username or email already exists');</script>";

                unset($_SESSION['user_exist']);
            } ?>

        </div>
    </div>
</div>


<script>
    //view modal
    $(".btn-view").on("click", function(e) {
        e.preventDefault();
        var eventid = $(this).closest("div").find("input[name='emp_id']").val();

        $.ajax({
            type: "post",
            url: "ajax-load-data.php",
            data: {
                loadData: true,
                userid: eventid,
            },
            success: function(response) {
                $("#staticBackdrop").modal("show");
                $("#modal-content").html(response);
            },
        });
    });

    //del user
    $(".btn-delete").on("click", function(e) {
        e.preventDefault();
        var empid = $(this).closest("div").find("input[name='emp_id']").val();
        $("#del-user").val(empid);
        $("#staticBackdropDeluser").modal("show");
    });

    //edit modal
    $(".btn-edit").on("click", function(e) {
        e.preventDefault();
        var eventid = $(this).closest("div").find("input[name='emp_id']").val();

        $.ajax({
            type: "post",
            url: "ajax-load-data.php",
            data: {
                editData: true,
                userid: eventid,
            },
            success: function(response) {
                $("#staticBackdrop1").modal("show");
                $("#modal-content1").html(response);
            },
        });
    });

    //add employee
    $("#fullName").keyup(function(e) {
        e.preventDefault();
        $("#fullName").removeClass("is-invalid");
    });

    $("#username").keyup(function(e) {
        e.preventDefault();
        $("#username").removeClass("is-invalid");
    });

    var filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    $("#email").change(function(e) {
        e.preventDefault();
        if (filter.test($("#email").val())) {
            $("#email").removeClass("is-invalid");
        }
    });

    $("#phone").keyup(function(e) {
        e.preventDefault();
        $("#phone").removeClass("is-invalid");
    });

    $("#address").keyup(function(e) {
        e.preventDefault();
        $("#address").removeClass("is-invalid");
    });

    $("#password").keyup(function(e) {
        e.preventDefault();
        $("#password").removeClass("is-invalid");
    });

    $("#confirmPassword").keyup(function(e) {
        e.preventDefault();
        var password = $("#password").val();
        var cpass = $("#confirmPassword").val();
        if (password == cpass) {
            $("#confirmPassword").removeClass("is-invalid");
        }
    });

    $("#next-btn").click(function(e) {
        e.preventDefault();

        var fname = $("#fullName").val();
        var username = $("#username").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var address = $("#address").val();
        var pass = $("#password").val();
        var compass = $("#confirmPassword").val();
        var feild = $("#inputState option:selected").val();

        if (feild == 0) {
            $("#inputState").addClass("is-invalid");
        } else if (fname == "") {
            $("#fullName").addClass("is-invalid");
        } else if (username == "") {
            $("#username").addClass("is-invalid");
        } else if (!filter.test(email)) {
            $("#email").addClass("is-invalid");
        } else if (phone == "") {
            $("#phone").addClass("is-invalid");
        } else if (pass == "") {
            $("#password").addClass("is-invalid");
        } else if (address == "") {
            $("#address").addClass("is-invalid");
        } else if (compass == "") {
            $("#confirmPassword").addClass("is-invalid");
        } else if (compass != pass) {
            $("#confirmPassword").addClass("is-invalid");
        } else {
            $("#pills-personal").removeClass("show active");
            $("#pills-payment").addClass("active show");
        }
    });

    $("#btn-prev").click(function(e) {
        e.preventDefault();
        $("#pills-payment").removeClass("show active");
        $("#pills-personal").addClass("active show");
    });

    $("#salary").change(function(e) {
        e.preventDefault();
        $("#salary").removeClass("is-invalid");
    });

    $("#submit-btn").click(function(e) {
        e.preventDefault();
        var paymentType = $("#payment-type option:selected").val();
        var salary = $("#salary").val();
        if (paymentType == 5) {
            $("#payment-type").addClass("is-invalid");
        } else if (salary == "") {
            $("#salary").addClass("is-invalid");
        } else {
            $("#form-employee").submit();
        }
    });

    $("#payment-type").on("change", function() {
        $("#payment-type").removeClass("is-invalid");
    });

    $("#inputState").on("change", function() {
        var feildOption = $("#inputState option:selected").val();
        $("#inputState").removeClass("is-invalid");
        if (feildOption == 2) {
            $("#role-field").removeClass("d-none");
        } else {
            $("#role-field").addClass("d-none");
        }
    });

    function addbank() {
        $("#bank-info").html(
            "<div class='col-12'> <a type='button' class='mb-4 text-primary' onClick='removebank()'>- Add bank account information <span class='text-muted'>(optional)</span> </a></div>\
                                        <div class='col-md-6 mb-2'>\
                                            <input type='hidden' value='1' name='bank'><input type='text' class='form-control' name='bank_name' placeholder='Bank Name'>\
                                        </div>\
                                        <div class='col-md-6 mb-2'>\
                                            <input type='text' class='form-control' name='bank_branch' placeholder='Branch Name'>\
                                        </div>\
                                        <div class='col-md-6 mb-2'>\
                                            <input type='text' class='form-control' name='bank_ifsc' placeholder='IFSC Code'>\
                                        </div>\
                                        <div class='col-md-6 mb-2'>\
                                            <input type='number' class='form-control' name='bank_account' placeholder='Account Number'>\
                                        </div>\
                                        <div class='col-md-6 mb-2'>\
                                            <input type='text' class='form-control' name='bank_holder' placeholder='Account Holder Name'>\
                                        </div>\
                                        <div class='col-md-6 form-group'>\
                                            <select class='form-control' name='account_type'>\
                                                <option value='0' disabled selected>choose account type</option>\
                                                <option value='1'>Saving Account</option>\
                                                <option value='2'>Current Account</option>\
                                            </select>\
                                        </div>\
                                        <div class='col-md-6 mb-3 mt-3'>\
                                            <input type='text' class='form-control' name='vpa' placeholder='vpa address'>\
                                        </div>\
                                        <div class='col-md-6 mb-3 mt-3'>\
                                            <input type='number' class='form-control' name='phonepay' placeholder='phonepay/googlepay'>\
                                        </div>"
        );
    }

    function removebank() {
        $("#bank-info").html(
            '<a type="button" class="col-12 text-primary mb-4" onClick="addbank()">+ Add bank account information <span class="text-muted">(optional)</span> </a>'
        );
    }

    $(document).ready(function() {
        $("#payment-type").on("change", function() {
            var selectVal = $("#payment-type option:selected").val();

            if (selectVal == 0) {
                $("#c-selected").html(
                    "<label class='invisible'>Payment amount</label><label class='sr-only' for='inlineFormInputGroup'>Payment amount</label>\
                           <div class='input-group flex-nowrap'> \
        <span class='input-group-text' id='basic-addon1'>Rs</span>\
                                            <input type='number' class='form-control' id='salary' required name='salary_amount' placeholder='Salary per month' aria-label='Username' aria-describedby='basic-addon1'></div>"
                );
            } else if (selectVal == 1) {
                $("#c-selected").html(
                    ""
                );
            } else {
                $("#c-selected").html("");
            }
        });
    });
</script>



</div>
<div class="page-footer">
    <a type="button" class="page-footer-item page-footer-item-left"><i data-feather="arrow-left" class="footer-left"></i>Back</a>
    <a href="http://firsthash.in" type="button" class="page-footer-item page-footer-item-right">Copyright © <?php echo date('Y'); ?> First Hash</a>
</div>

</div>

</div>

<script>
    $(".page-footer-item-left").click(function(e) {
        e.preventDefault();
        window.history.back();
    });
    $(".page-footer-item-right").click(function(e) {
        e.preventDefault();

    });
</script>




<!-- Javascripts -->

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="../../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
<script src="../../assets/plugins/select2/js/select2.min.js"></script>
<script src="../../assets/plugins/select2/js/select2.full.min.js"></script>

<!-- <script src="../../assets/plugins/pace/pace.min.js"></script> -->
<script src="../../assets/js/main.min.js"></script>
<script src="../../assets/js/pages/select2.js"></script>

</body>

</html>