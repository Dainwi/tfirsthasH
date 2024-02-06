<?php
define('TITLE', 'Add Member');
define('PAGE', 'add-employee');
define('PAGE_T', 'Add Member');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
$userID = $_SESSION['user_id'];
?>

<div class="container">
    <div class="card card-bg">
        <div class="card-body">

            <div class="container">
                <form action="<?php echo $url . "/admin/employees/employee-actions.php" ?>" method="post" id="form-employee">

                    <!-- personal information tab start -->

                    <div class="container-fluid card card-bg card-body">
                        <h5 class="font-weight-semibold text-secondary text-center mb-4">Member Information :</h5>
                        <div class="row mb-4">

                            <input type="hidden" name="employee_type" id="emtype" value="">
                            <input type="hidden" name="payment_type" id="empay" value="">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputState" class="text-muted">Member Type</label>
                                    <select id="inputState" class="form-select" aria-label="Default select example" name="empType">
                                        <option value="0" selected>Choose...</option>
                                        <option value="1">Manager</option>
                                        <option value="2">In-house</option>
                                        <option value="3">Freelancer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 d-none" id="role-field">
                                    <label for="choose_role" class="text-muted ml-2">Choose Role</label>
                                    <select id="choose_role" class="js-states form-control" tabindex="-1" style="display: none; width: 100%" required name="position[]" multiple="multiple">
                                        <?php
                                        $sql1 = "SELECT * FROM employee_type WHERE cus_id={$userID} OR cus_id=0";
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
                        </div>

                        <div class="row">

                            <div class="col-md-6 form-group mb-2">

                                <input name="name" value="" required type="text" class="form-control" id="fullName" placeholder="Full Name">
                            </div>


                            <div class="col-md-6 form-group mb-2">
                                <input name="email" value="" required type="email" class="form-control" id="email" placeholder="Email">
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <input name="phone" value="" required type="number" class="form-control" id="phone" placeholder="Mobile No.">
                            </div>


                        </div>

                    </div>
                    <?php
                    if (isset($_SESSION['user_exist'])) {
                        echo "<p class='text-center text-danger'>{$_SESSION["user_exist"]}</p>";

                        unset($_SESSION['user_exist']);
                    } ?>
                    <div class="text-center">
                        <input type="hidden" name="save-member" value="1">
                        <button class="d-inline-block btn btn-outline-info px-5 my-4" type="button" id="save-btn">Save </button>
                    </div>

                    <!-- personal information tab end -->



                </form>
            </div>


        </div>
    </div>
</div>


<script>
    //add employee
    $("#fullName").keyup(function(e) {
        e.preventDefault();
        $("#fullName").removeClass("is-invalid");
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


    $("#save-btn").click(function(e) {
        e.preventDefault();

        var fname = $("#fullName").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var feild = $("#inputState option:selected").val();


        if (feild == 0) {
            $("#inputState").addClass("is-invalid");


        } else if (fname == "") {
            $("#fullName").addClass("is-invalid");
        } else if (!filter.test(email)) {
            $("#email").addClass("is-invalid");
        } else if (phone == "") {
            $("#phone").addClass("is-invalid");
        } else {
            $('#form-employee').submit();

        }
    });



    $("#inputState").on("change", function() {
        var feildOption = $("#inputState option:selected").val();
        $("#inputState").removeClass("is-invalid");
        if (feildOption == 2) {
            $("#role-field").removeClass("d-none");
            $("#emtype").val('2');
            $("#empay").val('0');
        } else if (feildOption == 3) {
            $("#role-field").removeClass("d-none");
            $("#emtype").val('2');
            $("#empay").val('1');
        } else {
            $("#role-field").addClass("d-none");
            $("#emtype").val('1');
            $("#empay").val('0');
        }
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