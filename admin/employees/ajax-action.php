<?php
include_once("../../config.php");

if (isset($_POST['changeemployetype'])) {
    $feildOption = $_POST['feildOption'];
    $emp = $_POST['emp'];
    $sql = "UPDATE users_db SET payment_option={$feildOption} WHERE u_id={$emp}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['editData'])) {
    $userId = $_POST['userid'];
    $sql = "SELECT * FROM users_db WHERE u_id={$userId}";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $uname = $row['user_name'];
            $username = $row['user_username'];
            $uphone = $row['user_phone'];
            $uemail = $row['user_email'];
            $uphoto = $row['user_photo'];
            $utype = $row['user_role'];
            $uaddress = $row['user_address'];
        }
    }
    $output1 = "";
    $output1 .= "<div class='row mb-3'>
        <label for='inputName' class='col-sm-2 col-form-label text-light'>Name</label>
        <div class='col-sm-10'>
            <input type='text' class='form-control' name='emp-name' required id='inputName' value='{$uname}'>
        </div>
    </div>
    <div class='row mb-3'>
        <label for='inputEmail3' class='col-sm-2 col-form-label text-light'>Email</label>
        <div class='col-sm-10'>
            <input type='email' class='form-control' name='emp-email' required id='inputEmail3' value='{$uemail}'>
        </div>
    </div>
    <div class='row mb-3'>
        <label for='inputPhone' class='col-sm-2 col-form-label text-light'>Phone</label>
        <div class='col-sm-10'>
            <input type='number' class='form-control' name='emp-phone' required id='inputPhone' value='{$uphone}'>
        </div>
    </div>
    <input type='hidden' name='emp-id' value='{$userId}'>
    <div class='mt-5 mb-2 text-info'> Designation : </div>
";

    if ($utype == 1) {
        $output1 .= "Manager";
    } else {
        $sql2 = "SELECT * FROM employee_type";
        $res2 = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res2) > 0) {
            while ($row2 = mysqli_fetch_assoc($res2)) {
                $emid = $row2['type_id'];
                $sql1 = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userId} AND employee_type.type_id={$emid}";
                $res1 = mysqli_query($con, $sql1);
                if (mysqli_num_rows($res1) > 0) {
                    $check = "checked";
                } else {
                    $check = "";
                }
                $output1 .= "<div class='form-check'>
  <input class='form-check-input' type='checkbox' name='position[]' value='{$row2['type_id']}' id='flexCheckDefault{$row2['type_id']}' {$check}>
  <label class='form-check-label text-warning' for='flexCheckDefault{$row2['type_id']}'>
    {$row2['type_name']}
  </label>
</div>";
            }
        }
    }


    echo $output1;
}

if (isset($_POST['loadData'])) {
    $userId = $_POST['userid'];

    $sql = "SELECT * FROM users_db WHERE u_id={$userId}";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $uname = $row['user_name'];
            $username = $row['user_username'];
            $uphone = $row['user_phone'];
            $uemail = $row['user_email'];
            $uphoto = $row['user_photo'];
            $utype = $row['user_role'];
            $uaddress = $row['user_address'];
        }
    }



    $sqlBank = "SELECT * FROM payment_information WHERE pi_user_id= {$userId}";
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
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-md-flex align-items-center">
                        <div class="text-center text-sm-left ">
                            <div class="avatar avatar-image d-block" style="width: 150px; height:150px">
                                <img src="<?php

                                            if ($uphoto == 0) {
                                                echo $url . "/assets/images/avatars/profile-image.png";
                                            } else {
                                                echo $url . "/assets/images/profile/" . $uphoto;
                                            }
                                            ?>" alt="" class="img-fluid w-75">
                            </div>

                        </div>
                        <div class="text-center text-sm-left m-v-15 ps-4">
                            <h2 class="mb-3 text-white"><?php echo $uname ?></h2>
                            <p class="text-white font-size-13"><?php echo $username ?></p>
                            <p class="text-opacity font-size-13">
                                <?php $sql_type = "SELECT users_db.u_id,employee_position.*,employee_type.* FROM users_db INNER JOIN employee_position ON users_db.u_id = employee_position.ep_user_id INNER JOIN employee_type ON employee_position.ep_type = employee_type.type_id WHERE users_db.u_id={$userId};";
                                $res_type = mysqli_query($con, $sql_type);

                                if ($res_type) {
                                    while ($r_type = mysqli_fetch_assoc($res_type)) {
                                        echo "<span class='bg-warning btn-round px-2' style='color:#000'>" . $r_type['type_name'] . "</span> ";
                                    }
                                } ?></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="d-md-block d-none border-left col-1"></div>
                        <div class="col text-left">
                            <ul class="list-unstyled m-t-10">
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light mb-3">

                                        <span>Email : </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uemail ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light mb-3">

                                        <span>Phone : </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uphone ?></p>
                                </li>
                                <li class="row">
                                    <p class="col-sm-4 col-4 font-weight-semibold text-light mb-3">

                                        <span>Address : </span>
                                    </p>
                                    <p class="col font-weight-semibold text-light"> <?php echo $uaddress ?></p>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <p class="card-title text-gray"><i class="anticon anticon-bank me-2"></i>Payment/Bank Details :</p>

            </div>

            <?php if ($infoBank == 1) {  ?>

                <div class="row">
                    <div class="col-md-6">
                        <small>Bank details:</small>
                        <p class="pb-0 mb-0 mt-2">Bank Name : <span class="ms-2 font-weight-semibold"><?php echo $bankName ?></span></p>
                        <p class="py-0 my-0">Branch Name : <span class="ms-2 font-weight-semibold"><?php echo $branchName ?></span></p>
                        <p class="py-0 my-0">IFSC Code : <span class="ms-2 font-weight-semibold"><?php echo $ifscCode ?></span></p>
                        <p class="py-0 my-0">Account Number : <span class="ms-2 font-weight-semibold"><?php echo $accountNumber ?></span></p>
                        <p class="py-0 my-0">Account Holder : <span class="ms-2 font-weight-semibold"><?php echo $accountHolder ?></span></p>
                        <p class="py-0 my-0">Account Type : <span class="ms-2 font-weight-semibold"><?php if ($accountType == 1) {
                                                                                                        echo "Saving Account";
                                                                                                    } elseif ($accountType == 2) {
                                                                                                        echo "Current Account";
                                                                                                    } else {
                                                                                                        echo "";
                                                                                                    } ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <small>UPI and more:</small>
                        <p class="pb-0 mb-0 mt-2">VPA address : <span class="ms-2 font-weight-semibold"><?php echo $vpaAddress ?></span></p>
                        <p class="py-0 my-0">PhonePay/GooglePay : <span class="ms-2 font-weight-semibold"><?php echo $phonePay ?></span></p>
                    </div>
                </div>
            <?php } else {
                echo "<p class='text-center mx-auto py-4'>Bank Info not Added</p>";
            } ?>
        </div>
    </div>

<?php
}
?>