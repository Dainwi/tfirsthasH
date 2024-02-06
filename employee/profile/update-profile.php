<?php
include_once("../../config.php");

if (isset($_POST['add_bank'])) {

    if (isset($_POST['bank_name']) || isset($_POST['vpa_address']) || isset($_POST['phone_pay'])) {

        $employee = mysqli_real_escape_string($con, $_POST['empid']);

        if (isset($_POST['bank_name'])) {
            $bankName = mysqli_real_escape_string($con, $_POST['bank_name']);
        } else {
            $bankName = "";
        }

        if (isset($_POST['branch_name'])) {
            $branchName = mysqli_real_escape_string($con, $_POST['branch_name']);
        } else {
            $branchName = "";
        }
        if (isset($_POST['ifsc_code'])) {
            $ifscCode = mysqli_real_escape_string($con, $_POST['ifsc_code']);
        } else {
            $ifscCode = "";
        }
        if (isset($_POST['account_number'])) {
            $accountNumber = mysqli_real_escape_string($con, $_POST['account_number']);
        } else {
            $accountNumber = "";
        }
        if (isset($_POST['account_holder'])) {
            $accountHolder = mysqli_real_escape_string($con, $_POST['account_holder']);
        } else {
            $accountHolder = "";
        }
        if (isset($_POST['account_type'])) {
            $accountType = mysqli_real_escape_string($con, $_POST['account_type']);
        } else {
            $accountType = "";
        }
        if (isset($_POST['vpa_address'])) {
            $vpaAddress = mysqli_real_escape_string($con, $_POST['vpa_address']);
        } else {
            $vpaAddress = "";
        }
        if (isset($_POST['phone_pay'])) {

            $phonePay = mysqli_real_escape_string($con, $_POST['phone_pay']);
        } else {
            $phonePay = "";
        }

        $sql_bank = "INSERT INTO payment_information(pi_user_id,pi_bank_name,pi_bank_holder,pi_bank_account,pi_bank_ifsc,pi_bank_branch,pi_account_type,pi_vpa,pi_phonepay) VALUES('$employee','$bankName','$accountHolder','$accountNumber','$ifscCode','$branchName','$accountType','$vpaAddress','$phonePay')";
        $res_bank = mysqli_query($con, $sql_bank);
        if ($res_bank) {
            echo "<script>window.location=document.referrer;</script>";
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}

if (isset($_POST['update_bank'])) {

    $employee1 = mysqli_real_escape_string($con, $_POST['empid1']);


    if (isset($_POST['bank_name1'])) {
        $bankName1 = mysqli_real_escape_string($con, $_POST['bank_name1']);
    } else {
        $bankName1 = "";
    }

    if (isset($_POST['branch_name1'])) {
        $branchName1 = mysqli_real_escape_string($con, $_POST['branch_name1']);
    } else {
        $branchName1 = "";
    }
    if (isset($_POST['ifsc_code1'])) {
        $ifscCode1 = mysqli_real_escape_string($con, $_POST['ifsc_code1']);
    } else {
        $ifscCode1 = "";
    }
    if (isset($_POST['account_number1'])) {
        $accountNumber1 = mysqli_real_escape_string($con, $_POST['account_number1']);
    } else {
        $accountNumber1 = "";
    }
    if (isset($_POST['account_holder1'])) {
        $accountHolder1 = mysqli_real_escape_string($con, $_POST['account_holder1']);
    } else {
        $accountHolder1 = "";
    }
    if (isset($_POST['account_type1'])) {
        $accountType1 = mysqli_real_escape_string($con, $_POST['account_type1']);
    } else {
        $accountType1 = "";
    }
    if (isset($_POST['vpa_address1'])) {
        $vpaAddress1 = mysqli_real_escape_string($con, $_POST['vpa_address1']);
    } else {
        $vpaAddress1 = "";
    }
    if (isset($_POST['phone_pay1'])) {

        $phonePay1 = mysqli_real_escape_string($con, $_POST['phone_pay1']);
    } else {
        $phonePay1 = "";
    }

    $sql_update_bank = "UPDATE payment_information SET pi_bank_name = '$bankName1', pi_bank_holder= '$accountHolder1', pi_bank_account = '$accountNumber1', pi_bank_ifsc = '$ifscCode1', pi_bank_branch = '$branchName1', pi_account_type ='$accountType1', pi_vpa = '$vpaAddress1', pi_phonepay ='$phonePay1' WHERE pi_user_id = {$employee1}";
    $res_update_bank = mysqli_query($con, $sql_update_bank);
    if ($res_update_bank) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}
